<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;
use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;

/**
 * 角色授权
 * Class AuthorizationsController.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class AuthorizationsController extends Controller
{
    use HasPermissions;

    /**
     * 守卫.
     *
     * @var web
     */
    protected $defaultGuardName = 'web';

    /**
     * 读取授权节点.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function show(Role $role, Permission $permission)
    {
        // 获取权限列表
        $permissions = $permission->where('guard_name', $this->defaultGuardName)->get();

        // 格式化分组
        $nodes = $permissions->map(function ($item) {
            $item->pnode = Str::contains($item->name, '.') ? Str::before($item->name, '.') : '';

            return $item;
        })->toArray();

        // 获取授权节点
        $checked = array_column($role->permissions()->get()->toArray(), 'id');
        foreach ($nodes as &$node) {
            $node['checked'] = in_array($node['id'], $checked);
        }
        $all = $this->apply_filter(DataHandler::arr2tree($nodes, 'name', 'pnode', '_sub_'));

        return $this->success('获取节点成功！', '', $all);
    }

    /**
     * 保存授权节点.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Role $role, Request $request)
    {
        // 删除权限
        $role->revokePermissionTo(Permission::all());

        // 增加权限
        $role->givePermissionTo($request->nodes);

        ActivityHandler::console()->performedOn($role)->log('角色授权');

        return $this->success('节点授权更新成功！', '');
    }

    /**
     * 节点数据拼装.
     *
     * @param array $nodes
     * @param int   $level
     *
     * @return array
     */
    protected function apply_filter($nodes, $level = 1)
    {
        foreach ($nodes as $key => $node) {
            if (!empty($node['_sub_']) && is_array($node['_sub_'])) {
                $node[$key]['_sub_'] = $this->apply_filter($node['_sub_'], $level + 1);
            }
        }

        return $nodes;
    }
}
