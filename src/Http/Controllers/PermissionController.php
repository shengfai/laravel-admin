<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;

/**
 * 权限控制器
 * Class PermissionController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class PermissionController extends Controller
{

    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '系统权限管理';

    /**
     * 守卫
     *
     * @var web
     */
    protected $defaultGuardName = 'web';

    /**
     * Display a listing of the resource
     *
     * @param \Spatie\Permission\Models\Permission $permission
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Permission $permission)
    {
        // 获取权限列表
        $permissions = $permission->where('guard_name', $this->defaultGuardName)->get();
        
        // 格式化分组
        $groupedPermissions = $permissions->groupBy(function ($item) {
            return Str::contains($item->name, '.') ? Str::before($item->name, '.') : 'pnode';
        });
        
        return $this->view([
            'groups' => $groupedPermissions->all()
        ]);
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return $this->form();
    }

    /**
     * Store a newly created resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \Spatie\Permission\Models\Permission $permission
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request, Permission $permission)
    {
        // 添加记录
        $permission->fill($request->all());
        $permission = Permission::create($permission->toArray());
        
        // 添加日志
        if ($permission->id) {
            ActivityHandler::console()->performedOn($permission)->log('添加权限');
        }
        
        return $this->success('数据保存成功', '');
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param \Spatie\Permission\Models\Permission $permission
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Permission $permission)
    {
        return $this->view(compact('permission'));
    }

    /**
     * Update the specified resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \Spatie\Permission\Models\Permission $permission
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, Permission $permission)
    {
        if ($permission->update($request->all())) {
            ActivityHandler::console()->performedOn($permission)->log('更新权限');
            
            return $this->success('恭喜, 数据保存成功!', '');
        }
        
        return $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Spatie\Permission\Models\Permission $permission
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Permission $permission)
    {
        if ($permission->delete()) {
            ActivityHandler::console()->performedOn($permission)->log('删除权限');
            
            return $this->success('权限删除成功!', '');
        }
        
        return $this->error('权限删除失败, 请稍候再试!');
    }
}
