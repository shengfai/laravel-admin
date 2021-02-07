<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Permission\Traits\HasPermissions;
use Shengfai\LaravelAdmin\Http\Requests\ResetPasswordRequest;

/**
 * 用户控制台
 * Class UserController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class UserController extends Controller
{
    use HasPermissions;

    /**
     * Display a listing of the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request)
    {
        $this->title = '用户管理';
        
        $queryBuilder = QueryBuilder::for(User::query()->latest()->inNormalType($request->type))->allowedFilters([
            'name',
            'phone',
            'status'
        ]);
        
        if ($request->wantsJson()) {
            $data = $queryBuilder->paginate($this->perPage);
            return response()->json($data);
        }
        
        return $this->list($queryBuilder);
    }

    /**
     * Show the form for editing the specified resource
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
     * Update the specified resource in storage
     *
     * @param \Shengfai\LaravelAdmin\Http\Requests\ResetPasswordRequest $request
     * @param \App\Models\User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(ResetPasswordRequest $request, User $user)
    {
        // 更新用户
        if ($user->update($request->all())) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        
        return $this->error('数据保存失败, 请稍候再试!');
    }
}
