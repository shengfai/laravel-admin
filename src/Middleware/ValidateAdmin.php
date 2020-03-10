<?php

namespace Shengfai\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 后台权限校验中间件
 * Class ValidateAdmin
 *
 * @package \Shengfai\LaravelAdmin\Middleware
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class ValidateAdmin
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // get the admin check closure that should be supplied in the config
        $permission = function () {
            return Auth::check() && Auth::user()->can(config('administrator.permission'));
        };
        
        // if this is a simple false value, send the user to the login redirect
        if (!$response = $permission()) {
            $loginUrl = route(config('administrator.login_path'));
            $redirectKey = config('administrator.login_redirect_key');
            $redirectUri = $request->url();
            $responseData = [
                'code' => 0,
                'msg' => '抱歉，您还没有登录获取访问权限！',
                'url' => $loginUrl
            ];
            return $request->isXmlHttpRequest() ? response()->json($responseData) : redirect()->guest($loginUrl)->with($redirectKey, $redirectUri);
        }

        // otherwise if this is a response, return that
        elseif (is_a($response, 'Illuminate\Http\JsonResponse') || is_a($response, 'Illuminate\Http\Response')) {
            return $response;
        }

        // if it's a redirect, send it back with the redirect uri
        elseif (is_a($response, 'Illuminate\\Http\\RedirectResponse')) {
            $redirectKey = config('administrator.login_redirect_key');
            $redirectUri = $request->url();
            
            return $response->with($redirectKey, $redirectUri);
        }
        
        return $next($request);
    }
}