<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shengfai\LaravelAdmin\Models\Menu;
use Spatie\Permission\Models\Permission;
use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Shengfai\LaravelAdmin\Services\MenuService;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;

/**
 * 菜单控制器
 * Class MenuController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class MenuController extends Controller
{

    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '菜单管理';

    /**
     * 守卫
     *
     * @var web
     */
    protected $defaultGuardName = 'web';

    /**
     * Display a listing of the resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request, Menu $menu)
    {
        if ($request->has('action')) {
            return $this->list(Menu::class, false, false);
        }
        
        $menus = $this->list(Menu::class, false, false);
        $this->index_data_filter($menus['list']);
        
        if ($request->wantsJson()) {
            return $this->response($menus['list']);
        }
        
        return $this->view($menus);
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
     * Show the form for creating a new resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Services\MenuService $menuService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * Store a newly created resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request, Menu $menu)
    {
        try {
            
            DB::transaction(function () use ($request, $menu) {
                
                // 创建菜单
                $menu->fill($request->all());
                $menu->save();
                
                // 创建权限
                $this->autoCreatePermission($menu);
            });
            return $this->success('数据保存成功', '');
        } catch (PermissionAlreadyExists $e) {
            return $this->error('权限已存在，创建失败！');
        }
    }

    /**
     * Display the specified resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, Menu $menu)
    {
        $menu->load('permission');
        
        if ($request->wantsJson()) {
            return $this->response($menu->toArray());
        }
        
        return $this->view([
            'menu' => $menu
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
     * @param \Shengfai\LaravelAdmin\Services\MenuService $menuService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * Update the specified resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
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
     * Remove the specified resource from storage
     *
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
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
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
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
            'title' => $menu->name,
            'guard_name' => $this->defaultGuardName
        ]);
        
        // 更新菜单
        $menu->update([
            'permission_id' => $permission->id
        ]);
    }

    /**
     * 更新权限
     *
     * @param \Shengfai\LaravelAdmin\Models\Menu $menu
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
