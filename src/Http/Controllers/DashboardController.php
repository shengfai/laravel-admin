<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Shengfai\LaravelAdmin\Services\MenuService;

/**
 * 控制台首页
 * Class DashboardController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class DashboardController extends Controller
{

    /**
     * 控制台
     *
     * @param \Shengfai\LaravelAdmin\Services\MenuService $service
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function console(MenuService $service)
    {
        // 获取登录用户授权菜单
        $collection = $service->getAvailableMenusByUser();
        
        // 转化为树形结构
        $menus = DataHandler::arr2tree($collection->toArray());
        
        return view('admin::console', compact('menus'));
    }

    /**
     * 控制台首页
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        // 当前登录用户
        $this->user = \Auth::user();
        
        return $this->view();
    }
}
