<?php

namespace Shengfai\LaravelAdmin\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;

/**
 * 用户控制台
 * Class UsersController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class UsersController extends Controller
{
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request, User $user)
    {
        $this->title = '用户管理';
        
        if ($request->has('type')) {
            $user = User::where('type', $request->type);
        }
        
        return $this->list($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(User $user, Role $role)
    {
        // 获取站点所有角色
        $roles = $role::where('guard_name', Auth::getDefaultDriver())->get();
        
        return $this->view(compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request, User $user)
    {
        // 查找
        $user = User::where('phone', $request->phone)->first();
        
        // 未找到
        if (is_null($user)) {
            return $this->error('未找到用户！');
        }
        
        // 更新用户
        $user->fill($request->all());
        $user->type = Conventions::USER_TYPE_ADMIN;
        $user->save();
        
        // 更新绑定角色
        if ($request->roles) {
            $user->assignRole($request->roles);
        }
        
        ActivityHandler::console()->performedOn($user)->log('创建用户');
        
        return $this->success('数据保存成功', '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(User $user, Role $role)
    {
        // 获取站点所有角色
        $roles = $role::where('guard_name', Auth::getDefaultDriver())->get();
        
        // 当前用户绑定的角色
        $bindRoles = $user->getRoleNames()->toArray();
        
        foreach ($roles as &$val) {
            $val['checked'] = in_array($val['name'], $bindRoles);
        }
        return $this->view(compact('user', 'roles'));
    }

    /**
     * 绑定角色
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function authorizations(User $user, Role $role)
    {
        // 获取站点所有角色
        $roles = $role::where('guard_name', Auth::getDefaultDriver())->get();
        
        // 当前用户绑定的角色
        $bindRoles = $user->getRoleNames()->toArray();
        foreach ($roles as &$val) {
            $val['checked'] = in_array($val['name'], $bindRoles);
        }
        
        return $this->view([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * 密码重置页
     *
     * @param User $user
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function password(User $user)
    {
        return $this->view(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, User $user)
    {
        // 更新角色
        if ($request->roles) {
            
            // 删除绑定角色
            $bindRoles = $user->getRoleNames();
            
            foreach ($bindRoles as $role) {
                $user->removeRole($role);
            }
            
            // 绑定角色
            $user->assignRole($request->roles);
            
            ActivityHandler::console()->performedOn($user)->log('用户授权');
        }
        
        // 更新用户
        if ($user->update($request->all())) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        
        return $this->error('数据保存失败, 请稍候再试!');
    }
}
