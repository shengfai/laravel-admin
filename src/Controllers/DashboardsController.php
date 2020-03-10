<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Support\Facades\Auth;
use Shengfai\LaravelAdmin\Services\MenuService;
use Shengfai\LaravelAdmin\Handlers\DataHandler;

/**
 * 控制台首页
 * Class DashboardsController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class DashboardsController extends Controller
{

    /**
     * 控制台
     *
     * @param MenuService $service
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function console(MenuService $service)
    {
        // 获取登录用户授权菜单
        $collection = $service->getAuthorizedMenusByUser();
        
        // 转化为树形结构
        $menus = DataHandler::arr2tree($collection->toArray());
        
        return view('admin::admin.console', compact('menus'));
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
