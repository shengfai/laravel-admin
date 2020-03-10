<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * 角色控制器
 *
 * Class RolesController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class RolesController extends Controller
{
    /**
     * 页面标题
     *
     * @var string $title
     */
    protected $title = '系统角色管理';
    
    /**
     * 守卫
     *
     * @var web
     */
    protected $defaultGuardName = 'web';

    /**
     * Display a listing of the resource.
     *
     * @param Role $role
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Role $role)
    {
        // 获取记录
        $queryBuilder = $role->where('guard_name', $this->defaultGuardName);
        
        return $this->list($queryBuilder);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return $this->view();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request, Role $role)
    {
        $role = Role::create([
            'name' => $request->name,
            'remark' => $request->remark
        ]);
        
        return $this->success('数据保存成功', '');
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Role $role)
    {
        $this->title = '角色授权';
        return $this->view(compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Role $role)
    {
        return $this->view(compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, Role $role)
    {
        $role->fillable([
            'name',
            'remark',
            'status'
        ]);
        if ($role->update($request->all())) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        return $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return $this->success('角色删除成功!', '');
        }
        return $this->error('角色删除失败, 请稍候再试!');
    }
}
