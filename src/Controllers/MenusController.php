<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Menu;
use Spatie\Permission\Models\Permission;
use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Shengfai\LaravelAdmin\Services\MenuService;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;

/**
 * 菜单控制器
 * Class MenusController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class MenusController extends Controller
{
    
    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '菜单管理';

    /**
     * Display a listing of the resource.
     *
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Menu $menu)
    {
        return $this->list(Menu::class, false);
    }

    /**
     * 列表数据处理
     *
     * @param array $data
     * @return array
     */
    protected function index_data_filter(&$data)
    {
        // 转化数组
        $data = $data->toArray();
        foreach ($data as &$vo) {
            $vo['ids'] = join(',', DataHandler::getArrSubIds($data, $vo['id']));
        }
        $data = DataHandler::arr2table($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param MenuService $menuService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(Request $request, MenuService $menuService)
    {
        // 获取父节点
        $menus = $menuService->getUsedAsParentList();
        
        return $this->view([
            'menus' => $menus,
            'parent_id' => $request->parent_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request, Menu $menu)
    {
        try {
            // 创建菜单
            $menu->fill($request->all());
            $menu->save();
            
            // 创建权限
            $this->autoCreatePermission($menu);
            
            return $this->success('数据保存成功', '');
        
        } catch (PermissionAlreadyExists $e) {
            return $this->error('权限已存在，创建失败！');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Menu $menu
     * @param MenuService $menuService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Menu $menu, MenuService $menuService)
    {
        // 获取父节点
        $menus = $menuService->getUsedAsParentList();
        
        return $this->view([
            'menu' => $menu,
            'menus' => $menus,
            'parent_id' => $menu->parent_id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws PermissionAlreadyExists
     */
    public function update(Request $request, Menu $menu)
    {
        try {
            // 获取对象
            $menu = $menu->fill($request->all());
            
            // 更新菜单
            $menu->save();
            
            // 更新权限
            $this->autoUpdatePermission($menu);
            
            return $this->success('数据保存成功', '');
        
        } catch (PermissionAlreadyExists $e) {
            return $this->error('权限已存在，创建失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Menu $menu)
    {
        if ($menu->delete()) {
            return $this->success('菜单删除成功!', '');
        }
        return $this->error('菜单删除失败, 请稍候再试!');
    }

    /**
     * 创建权限
     *
     * @param Menu $menu
     * @return void
     */
    protected function autoCreatePermission(Menu $menu)
    {
        // 标识不为空
        if (empty($menu->code)) {
            return;
        }
        
        // 创建权限
        $permission = Permission::create([
            'name' => $menu->code,
            'title' => $menu->name
        ]);
        
        // 更新菜单
        $menu->update([
            'permission_id' => $permission->id
        ]);
    }

    /**
     * 更新权限
     *
     * @param Menu $menu
     * @return void
     */
    protected function autoUpdatePermission(Menu $menu)
    {
        // 标识不为空
        if (empty($menu->code)) {
            return;
        }
        
        // 未关联权限
        if (empty($menu->permission)) {
            return $this->autoCreatePermission($menu);
        }
        
        // 更新权限
        $menu->permission->update([
            'name' => $menu->code,
            'title' => $menu->name
        ]);
    }
}
