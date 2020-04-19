<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Schema;
use Shengfai\LaravelAdmin\Traits\Response;

/**
 * 控制台控制器基类
 * Class AdminController.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月8日
 */
abstract class AdminController extends BaseController
{
    use Response;

    /**
     * 页面标题.
     *
     * @var string
     */
    protected $title;

    /**
     * 操作视图.
     *
     * @var string
     */
    protected $view;

    /**
     * 模板变量.
     *
     * @var array
     */
    protected $data = [];

    /**
     * 模板变量赋值
     *
     * @param mixed $name  要显示的模板变量
     * @param mixed $value 变量的值
     *
     * @return void
     */
    protected function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->data = array_merge($this->data, $name);
        } else {
            $this->data[$name] = $value;
        }
    }

    /**
     * 表单默认操作.
     *
     * @param mix    $dbQuery 数据库查询对象
     * @param array  $where   查询规则
     * @param string $pkField
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function form($dbQuery = null, $where = [], $pkField = '')
    {
        // 操作模型
        $db = is_string($dbQuery) ? new $dbQuery() : $dbQuery;
        $pk = empty($pkField) ? 'id' : $pkField;
        $pkValue = request()->input($pk, isset($where[$pk]) ? $where[$pk] : null);

        // 非POST请求, 获取数据并显示表单页面
        if (!request()->isMethod('post')) {
            $result['data'] = (null !== $pkValue) ? $db->where($pk, $pkValue)->where($where)->find()->toArray() : [];
            if (false !== $this->callback('_form_filter', $result['data'], [])) {
                return $this->view($result, 'form');
            }
        }
    }

    /**
     * 列表集成处理方法.
     *
     * @param mixed $dbQuery   数据库查询对象
     * @param bool  $isPage    是启用分页
     * @param bool  $isDisplay 是否直接输出显示
     * @param bool  $total     总记录数
     * @param array $result    结果集
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    protected function list($dbQuery = null, $isPage = true, $isDisplay = true, $total = false, $result = [])
    {
        // 操作模型
        $db = is_string($dbQuery) ? new $dbQuery() : $dbQuery;

        // 列表排序默认处理
        if ('resort' === request()->action) {
            // 获取模型
            if ($db instanceof Builder) {
                $model = $db->getModel();
            } else {
                $model = $db;
            }

            // 更新排序
            foreach (request()->input() as $key => $value) {
                if (preg_match('/^_\d{1,}$/', $key) && preg_match('/^\d{1,}$/', $value)) {
                    list($where, $update) = [['id' => trim($key, '_')], ['sort' => $value]];

                    if (false === $model->where($where)->update($update)) {
                        return $this->error('列表排序失败, 请稍候再试');
                    }
                }
            }

            return $this->success('列表排序成功, 正在刷新列表', '');
        }

        // 列表数据查询与显示
        if (is_callable([$db, 'getQuery']) && (null === $db->getQuery()->orders)) {
            if ($db instanceof Model) {
                $table = $db->getTable();
            } elseif ($db instanceof Builder) {
                $table = $db->getModel()->getTable();
            }

            // 获取字段
            $tableFields = Schema::getColumnListing($table);
            in_array('sort', $tableFields) && $db = $db->orderBy('sort', 'DESC');
            in_array('created_at', $tableFields) && $db = $db->orderBy('created_at', 'DESC');
        }

        // 列表数据查询与显示
        if ($isPage) {
            $per_page = intval(request()->get('per_page', cookie('page-rows')->getValue()));
            $page = $db->paginate($per_page);
            $result = $this->page($page, $per_page);
        } else {
            $result['list'] = $db->get();
        }

        if (false !== $this->callback('_data_filter', $result['list'], []) && $isDisplay) {
            return $this->view($result, 'index');
        }

        return $result;
    }

    /**
     * 索引查询.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|NULL[]
     */
    protected function search(string $keyword, bool $isDisplay = true)
    {
        // 执行搜索
        $resources = $this->buildSearchQuery($keyword);

        // 直接返回数据
        if (!$isDisplay) {
            return $resources;
        }

        // 添加路径
        $resources->withPath(request()->url());

        // 列表数据查询与显示
        $result = $this->page($resources);

        if (false !== $this->callback('_data_filter', $result['list'], []) && $isDisplay) {
            return $this->view($result, 'index');
        }

        return $result;
    }

    /**
     * 分页显示.
     *
     * @return unknown[]|string[]|NULL[]|number[]
     */
    protected function page(LengthAwarePaginator $page, int $per_page = 0)
    {
        // 每页条数
        $cookie = cookie('page-rows', $per_page = $per_page >= 10 ? $per_page : 20);

        // 分页请求参数
        $param = request()->query();
        unset($param['page']);
        unset($param['spm']);
        $page->appends($param);

        // 分页数据处理
        if (($total = $page->total()) > 0) {
            list($rowHTML, $curPage, $maxNum) = [[], $page->currentPage(), $page->lastPage()];
            foreach ([20, 30, 50, 60, 90, 100, 120, 150, 200] as $num) {
                list($query['per_page'], $query['page']) = [$num, 1];
                $url = url(request()->getPathInfo()).'?'.http_build_query($query);
                $rowHTML[] = "<option data-url='{$url}' ".($per_page === $num ? 'selected' : '').
                         " value='{$num}'>{$num}</option>";
            }
            list($pattern, $replacement) = [
                [
                    '|href="(.*?)"|',
                    '|pagination|',
                ],
                [
                    'data-open="$1"',
                    'pagination pull-right',
                ],
            ];
            $html = "<span class='pagination-trigger nowrap'>共 {$total} 条记录，每页显示 <select data-auto-none>".
                     join('', $rowHTML)."</select> 条，共 {$maxNum} 页当前显示第 {$curPage} 页。</span>";
            list($result['total'], $result['list'], $result['page']) = [
                $total, $page->all(), $html.preg_replace($pattern, $replacement, $page->render()),
            ];
        } else {
            list($result['total'], $result['list'], $result['page']) = [$total, $page->all(), $page->render()];
        }

        return $result;
    }

    /**
     * 输出视图.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(array $data = [], string $view = '')
    {
        // 设置标题及模板变量
        !isset($data['title']) && $data['title'] = $this->title;
        $data = array_merge($data, $this->data);

        // 操作视图
        if (is_null($this->view)) {
            // 操作方法
            $method = $this->getCurrentMethodName();
            $action = empty($view) ? in_array($method, ['create', 'edit']) ? 'form' : $method : $view;
            $controller = $this->getCurrentControllerName(true);
            $this->view = "admin::{$controller}.{$action}";
        }

        return view($this->view, $data);
    }

    /**
     * 操作成功跳转的快捷方法.
     *
     * @param string $msg    提示信息
     * @param string $url    跳转的URL地址
     * @param string $data   返回的数据
     * @param number $wait   跳转等待时间
     * @param array  $header 发送的Header信息
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function success($msg = '', $url = null, $data = '', $wait = 0.1, array $header = [])
    {
        if (is_null($url) && isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } elseif ('' !== $url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : url($url);
        }

        $result = [
            'code' => 1,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];

        return $this->write($result, 200, $header);
    }

    /**
     * 操作失败跳转的快捷方法.
     *
     * @param string $msg    提示信息
     * @param string $url    跳转的URL地址
     * @param string $data   返回的数据
     * @param number $wait   跳转等待时间
     * @param array  $header 发送的Header信息
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function error($msg = '', $url = null, $data = '', $wait = 1, array $header = [])
    {
        if (is_null($url)) {
            $url = \request()->method('ajax') ? '' : 'javascript:history.back(-1);';
        } elseif ('' !== $url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : $this->app['url']->build($url);
        }

        $result = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];

        return $this->write($result, 200, $header);
    }

    /**
     * 返回封装后的API数据到客户端.
     *
     * @param mixed  $data   要返回的数据
     * @param number $code   返回的code
     * @param string $msg    提示信息
     * @param string $type   返回数据格式
     * @param array  $header 发送的Header信息
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function result($data, $code = 0, $msg = '', $type = '', array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $type = $type ?: $this->getResponseType();

        return response($result, 200, $header)->header('Content-Type', $type);
    }

    /**
     * 获取当前的response 输出类型.
     *
     * @return string|mixed|\Illuminate\Config\Repository
     */
    protected function getResponseType()
    {
        $isAjax = request()->isXmlHttpRequest();

        return $isAjax ? config('api.defaultFormat') : 'html';
    }

    /**
     * 当前对象回调成员方法.
     *
     * @param string     $method
     * @param array|bool $data1
     * @param array|bool $data2
     *
     * @return bool
     */
    protected function callback($method, &$data1, $data2)
    {
        foreach ([
            $method,
            $this->getCurrentMethodName()."{$method}",
        ] as $_method) {
            if (method_exists($this, $_method) && false === $this->$_method($data1, $data2)) {
                return false;
            }
        }

        return true;
    }
}
