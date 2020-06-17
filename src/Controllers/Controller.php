<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Session\SessionManager as Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shengfai\LaravelAdmin\Traits\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 控制台控制器基类
 * Class AdminController.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月8日
 */
abstract class Controller extends BaseController
{
    use Response;
    
    /**
     *
     * @var int
     */
    protected $page = 1;
    
    /**
     *
     * @var int
     */
    protected $perPage = 20;
    
    /**
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;
    
    /**
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;
    
    /**
     *
     * @var string
     */
    protected $formRequestErrors;
    
    /**
     *
     * @var string
     */
    protected $layout = 'admin::layouts.default';
    
    /**
     * 页面标题.
     *
     * @var string
     */
    protected $title;
    
    /**
     * 模板变量.
     *
     * @var array
     */
    protected $data = [];

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Session\SessionManager $session
     */
    public function __construct(Request $request, Session $session)
    {
        $this->request = $request;
        $this->session = $session;
        
        $this->page = (int)$request->input('page', 1);
        $this->perPage = (int)min($request->input('per_page', config('administrator.global_rows_per_page')), 100);
        
        // $this->formRequestErrors = $this->resolveDynamicFormRequestErrors($request);
    }

    /**
     * POST method to capture any form request errors.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function resolveDynamicFormRequestErrors(Request $request)
    {
        try {
            $config = app('itemconfig');
            $fieldFactory = app('admin.field_factory');
        } catch (\ReflectionException $e) {
            return;
        }
        if (array_key_exists('form_request', $config->getOptions())) {
            try {
                $model = $config->getFilledDataModel($request, $fieldFactory->getEditFields(), $request->id);
                
                $request->merge($model->toArray());
                $formRequestClass = $config->getOption('form_request');
                app($formRequestClass);
            } catch (HttpResponseException $e) {
                // Parses the exceptions thrown by Illuminate\Foundation\Http\FormRequest
                $errorMessages = $e->getResponse()->getContent();
                $errorsArray = json_decode($errorMessages);
                if (!$errorsArray && is_string($errorMessages)) {
                    return $errorMessages;
                }
                if ($errorsArray) {
                    return implode('.', array_dot($errorsArray));
                }
            }
        }
        
        return;
    }

    /**
     * 模板变量赋值
     *
     * @param mixed $name 要显示的模板变量
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
     * @param mix $dbQuery 数据库查询对象
     * @param array $where 查询规则
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
            return $this->view($result, 'form');
        }
    }

    /**
     * 列表集成处理方法.
     *
     * @param mixed $dbQuery 数据库查询对象
     * @param bool $isPage 是启用分页
     * @param bool $isDisplay 是否直接输出显示
     * @param bool $total 总记录数
     * @param array $result 结果集
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    protected function list($dbQuery = null, $isPage = true, $isDisplay = true, $total = false, $result = [])
    {
        // 操作模型
        $db = is_string($dbQuery) ? new $dbQuery() : $dbQuery;
        
        // 列表排序默认处理
        if ($this->request->has('action') && $this->request->action === 'resort') {
            return $this->resort($db);
        }
        
        // 列表数据查询与显示
        if (is_callable([
            $db,
            'getQuery'
        ]) && (null === $db->getQuery()->orders)) {
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
        
        if ($isDisplay) {
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
        
        if ($isDisplay) {
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
            list($rowHTML, $curPage, $maxNum) = [
                [],
                $page->currentPage(),
                $page->lastPage()
            ];
            foreach ([
                20,
                30,
                50,
                60,
                90,
                100,
                120,
                150,
                200
            ] as $num) {
                list($query['per_page'], $query['page']) = [
                    $num,
                    1
                ];
                $url = url(request()->getPathInfo()) . '?' . http_build_query($query);
                $rowHTML[] = "<option data-url='{$url}' " . ($per_page === $num ? 'selected' : '') .
                         " value='{$num}'>{$num}</option>";
            }
            list($pattern, $replacement) = [
                [
                    '|href="(.*?)"|',
                    '|pagination|'
                ],
                [
                    'data-open="$1"',
                    'pagination pull-right'
                ]
            ];
            $html = "<span class='pagination-trigger nowrap'>共 {$total} 条记录，每页显示 <select data-auto-none>" .
                     join('', $rowHTML) . "</select> 条，共 {$maxNum} 页当前显示第 {$curPage} 页。</span>";
            list($result['total'], $result['list'], $result['page']) = [
                $total,
                $page->all(),
                $html . preg_replace($pattern, $replacement, $page->render())
            ];
        } else {
            list($result['total'], $result['list'], $result['page']) = [
                $total,
                $page->all(),
                $page->render()
            ];
        }
        
        return $result;
    }

    /**
     * 排序
     *
     * @param mixed $dbManager
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function resort($dbManager)
    {
        // 获取模型
        if ($dbManager instanceof Builder) {
            $model = $dbManager->getModel();
        } else {
            $model = $dbManager;
        }
        
        // 更新排序
        foreach (request()->input() as $key => $value) {
            if (preg_match('/^_\d{1,}$/', $key) && preg_match('/^\d{1,}$/', $value)) {
                list($where, $update) = [
                    [
                        'id' => trim($key, '_')
                    ],
                    [
                        'sort' => $value
                    ]
                ];
                
                if (false === $model->where($where)->update($update)) {
                    return $this->error('列表排序失败, 请稍候再试');
                }
            }
        }
        
        return $this->success('列表排序成功, 正在刷新列表', '');
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
        if (empty($view) || !Str::contains($view, 'admin::')) {
            $method = \Route::current()->getActionMethod();
            $action = empty($view) ? in_array($method, [
                'create',
                'edit'
            ]) ? 'form' : $method : $view;
            $controller = $this->getCurrentControllerName(true);
            $view = "admin::{$controller}.{$action}";
        }
        
        return view($view, $data);
    }

    /**
     * 操作成功跳转的快捷方法.
     *
     * @param string $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param string $data 返回的数据
     * @param number $wait 跳转等待时间
     * @param array $header 发送的Header信息
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function success($msg = '操作成功！', $url = '', $data = '', $wait = 0.1, array $header = [])
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
            'wait' => $wait
        ];
        
        return $this->write($result, 200, $header);
    }

    /**
     * 操作失败跳转的快捷方法.
     *
     * @param string $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param string $data 返回的数据
     * @param number $wait 跳转等待时间
     * @param array $header 发送的Header信息
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function error($msg = '操作失败！', $url = '', $data = '', $wait = 1, array $header = [])
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
            'wait' => $wait
        ];
        
        return $this->write($result, 200, $header);
    }

    /**
     * 获取当前控制器名.
     *
     * @return string
     */
    protected function getCurrentControllerName($baseName = false)
    {
        $controller = Arr::first(explode('@', \Route::current()->getActionName('controller')));
        return $baseName ? strtolower(Str::before(class_basename($controller), 'Controller')) : $controller;
    }
}
