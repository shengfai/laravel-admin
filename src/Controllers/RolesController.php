<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Spatie\Permission\Models\Role;

/**
 * 角色控制器.
 * Class RolesController
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class RolesController extends AdminController
{
    /**
     * 页面标题.
     *
     * @var string
     */
    protected $title = '系统角色管理';
    
    /**
     * 守卫.
     *
     * @var web
     */
    protected $defaultGuardName = 'web';

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Role $role)
    {
        $this->title = '角色授权';
        
        return $this->view(compact('role'));
    }
}
