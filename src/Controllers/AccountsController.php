<?php

namespace Shengfai\LaravelAdmin\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * 登录界面
 * Class AccountsController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class AccountsController extends Controller
{
    use AuthenticatesUsers;

    /**
     * 登录表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return $this->view([], 'login');
    }

    /**
     * 登录请求失败请求
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function sendFailedLoginResponse()
    {
        return $this->error('输入的账号或者密码有误');
    }

    /**
     * 用于登录的字段
     *
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        // 状态及类型
        return array_merge($request->only($this->username(), 'password'), [
            'type' => User::TYPE_ADMINISTRATOR,
            'status' => Conventions::STATUS_USABLE
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function authenticated(Request $request, User $user)
    {
        // @todo 记录日志
        
        return $this->success('登录成功，正在进入系统...', route('admin.dashboards.console'));
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        
        $request->session()->invalidate();
        
        return redirect(route('admin.login'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

}
