<?php

namespace Shengfai\LaravelAdmin\Http\Middleware;

use Auth;
use Closure;

class Authenticate
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
        $configFactory = app('admin.config_factory');
        
        // get the admin check closure that should be supplied in the config
        $permission = function () use($request) {
            return $this->shouldPassThrough($request) || Auth::check();
        };
        
        // if this is a simple false value, send the user to the login redirect
        if (!$response = $permission()) {
            $loginUrl = route(config('administrator.login_path'));
            $redirectKey = config('administrator.login_redirect_key', 'redirect');
            $responseData = [
                'code' => 0,
                'msg' => '抱歉，您还没有登录获取访问权限！',
                'url' => $loginUrl
            ];
            return $request->isXmlHttpRequest() ? response()->json($responseData) : redirect()->guest($loginUrl)->with($redirectKey, $request->url());
        }
        
        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $excepts = config('administrator.auth.excepts', [
            'console/login',
            'console/logout'
        ]);
        
        return collect($excepts)->map('admin_base_path')->contains(function ($except) use($request) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            
            return $request->is($except);
        });
    }
}
