<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasPermissions;
use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

/**
 * 角色控制器
 * Class RoleController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class RoleController extends AdminController
{
    use HasPermissions;

    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '系统角色管理';

    /**
     * 守卫
     *
     * @var web
     */
    protected $defaultGuardName = 'web';

    /**
     * Display the specified resource
     *
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Role $role)
    {
        $this->title = '角色授权';
        
        return $this->view(compact('role'));
    }

    /**
     * Show the form for creating a new resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {
        try {
            $role = Role::create($request->only([
                'name',
                'guard_name',
                'remark',
                'status'
            ]));
            
            return $this->success();
        } catch (RoleAlreadyExists $exception) {
            return $this->error('角色名已存在');
        }
    }

    /**
     * 读取授权节点
     *
     * @param \Spatie\Permission\Models\Role $role
     * @param \Spatie\Permission\Models\Permission $permission
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function permissions(Role $role, Permission $permission)
    {
        // 获取权限列表
        $permissions = $permission->where('guard_name', $this->defaultGuardName)->get();
        
        // 格式化分组
        $nodes = $permissions->map(function ($item) {
            $item->pnode = Str::contains($item->name, '.') ? Str::before($item->name, '.') : '';
            return $item;
        })->toArray();
        
        // 获取授权节点
        $checked = array_column($role->permissions()
            ->get()
            ->toArray(), 'id');
        
        foreach ($nodes as &$node) {
            $node['checked'] = in_array($node['id'], $checked);
        }
        $all = $this->apply_filter(DataHandler::arr2tree($nodes, 'name', 'pnode', '_sub_'));
        
        return $this->success('获取节点成功！', '', $all);
    }

    /**
     * 保存授权节点
     *
     * @param \Illuminate\Http\Request $request
     * @param \Spatie\Permission\Models\Role $role
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function authorize(Request $request, Role $role)
    {
        // 删除权限
        $role->revokePermissionTo(Permission::all());
        
        // 增加权限
        $role->givePermissionTo($request->nodes);
        
        ActivityHandler::console()->performedOn($role)->log('角色授权');
        
        return $this->success('节点授权更新成功！', '');
    }

    /**
     * 节点数据拼装
     *
     * @param array $nodes
     * @param int $level
     * @return array
     */
    protected function apply_filter($nodes, $level = 1)
    {
        foreach ($nodes as $key => $node) {
            if (! empty($node['_sub_']) && is_array($node['_sub_'])) {
                $node[$key]['_sub_'] = $this->apply_filter($node['_sub_'], $level + 1);
            }
        }
        
        return $nodes;
    }
}
