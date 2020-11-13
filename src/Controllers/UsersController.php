<?php

namespace Shengfai\LaravelAdmin\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasPermissions;

/**
 * 用户控制台
 * Class UsersController.
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class UsersController extends Controller
{
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request)
    {
        $this->title = '用户管理';
        
        $queryBuilder = User::query();
        
        if ($request->has('type')) {
            $queryBuilder->where('type', $request->type);
        }
        
        return $this->list($queryBuilder);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(User $user)
    {
        return $this->view(compact('user'));
    }

    /**
     * 密码重置页
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function password(User $user)
    {
        return $this->view(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, User $user)
    {
        // 更新用户
        if ($user->update($request->all())) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        
        return $this->error('数据保存失败, 请稍候再试!');
    }
}
