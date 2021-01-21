<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shengfai\LaravelAdmin\Traits\ResponseTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 控制器基类
 * Class Controller
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
abstract class Controller extends BaseController
{
    use ResponseTrait;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $formRequestErrors;

    /**
     * @var string
     */
    protected $layout = 'admin::layouts.default';

    /**
     * 页面标题
     *
     * @var string
     */
    protected $title;

    /**
     * 操作视图
     *
     * @var string
     */
    protected $view;

    /**
     * 模板变量
     *
     * @var array
     */
    protected $data = [];

    /**
     * 初始化
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        // 加载系统级参数
        $this->loadSysParameters($request);
        
        // 加载业务级参数
        if (method_exists($this, 'loadBizParameters')) {
            $this->loadBizParameters($request);
        }
    }

    /**
     * 系统参数初始化
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    final protected function loadSysParameters(Request $request)
    {
        $this->request = $request;
        $this->page = (int) $request->input('page', 1);
        $this->perPage = (int) min($request->input('per_page', config('administrator.global_rows_per_page')), 5000);
    }

    /**
     * 模板变量赋值
     *
     * @param mixed $name
     * @param mixed $value
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
     * 表单默认操作
     *
     * @param mix $dbQuery
     * @param array $where
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
        if (! request()->isMethod('post')) {
            $result['data'] = (null !== $pkValue) ? $db->where($pk, $pkValue)
                ->where($where)
                ->find()
                ->toArray() : [];
            return $this->view($result, 'form');
        }
    }

    /**
     * 列表集成处理方法
     *
     * @param mixed $dbQuery
     * @param bool $isPage
     * @param bool $isDisplay
     * @param bool $total
     * @param array $result
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
        
        if ($isDisplay) {
            return $this->view($result, 'index');
        }
        
        return $result;
    }

    /**
     * 分页显示
     *
     * @param LengthAwarePaginator $page
     * @param int $per_page
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
            list ($rowHTML, $curPage, $maxNum) = [[], $page->currentPage(), $page->lastPage()];
            foreach ([20, 50, 100, 200, 300, 500, 1000] as $num) {
                list ($query['per_page'], $query['page']) = [$num, 1];
                $url = url(request()->getPathInfo()) . '?' . http_build_query($query);
                $rowHTML[] = "<option data-url='{$url}' " . ($per_page === $num ? 'selected' : '') . " value='{$num}'>{$num}</option>";
            }
            list ($pattern, $replacement) = [
                ['|href="(.*?)"|', '|pagination|'],
                ['data-open="$1"', 'pagination pull-right']
            ];
            $html = "<span class='pagination-trigger nowrap'>共 {$total} 条记录，每页显示 <select data-auto-none>" . join('', $rowHTML) . "</select> 条，共 {$maxNum} 页当前显示第 {$curPage} 页。</span>";
            list ($result['total'], $result['list'], $result['page']) = [
                $total,
                $page->all(),
                $html . preg_replace($pattern, $replacement, $page->render())
            ];
        } else {
            list ($result['total'], $result['list'], $result['page']) = [
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
        $order_column = method_exists($model, 'getOrderColumnName') ? $model->getOrderColumnName() : 'sort';
        foreach (request()->input() as $key => $value) {
            if (preg_match('/^_\d{1,}$/', $key) && preg_match('/^\d{1,}$/', $value)) {
                list ($where, $update) = [
                    ['id' => trim($key, '_')],
                    [$order_column => $value]
                ];
                
                if (false === $model->where($where)->update($update)) {
                    return $this->error('列表排序失败, 请稍候再试');
                }
            }
        }
        
        return $this->success('列表排序成功, 正在刷新列表', '');
    }

    /**
     * 输出视图
     *
     * @param array $data
     * @param string $view
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    protected function view(array $data = [], string $view = '')
    {
        // 设置标题及模板变量
        ! isset($data['title']) && $data['title'] = $this->title;
        $data = array_merge($data, $this->data);
        
        // 操作视图
        $view = $this->view ?? $view;
        if (empty($view) || ! Str::contains($view, 'admin::')) {
            $method = \Route::current()->getActionMethod();
            $action = empty($view) ? in_array($method, ['create', 'edit']) ? 'form' : $method : $view;
            $controller = $this->getCurrentControllerName(true);
            $view = "admin::{$controller}.{$action}";
        }
        
        return view($view, $data);
    }

    /**
     * 操作成功跳转的快捷方法
     *
     * @param string $msg
     * @param string $url
     * @param string $data
     * @param number $wait
     * @param array $header
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
     * 操作失败跳转的快捷方法
     *
     * @param string $msg
     * @param string $url
     * @param string $data
     * @param number $wait
     * @param array $header
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
     * Get the authenticated user
     *
     * @return \App\Models\User
     */
    protected function user()
    {
        return Auth::user();
    }

    /**
     * 获取当前控制器名
     *
     * @param string $baseName
     * @return string
     */
    protected function getCurrentControllerName($baseName = false)
    {
        $controller = Arr::first(explode('@', \Route::current()->getActionName('controller')));
        return $baseName ? strtolower(Str::before(class_basename($controller), 'Controller')) : $controller;
    }
}
