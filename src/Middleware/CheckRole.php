<?php

namespace Shengfai\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 权限校验
 * Class CheckRole
 *
 * @package \Shengfai\LaravelAdmin\Middleware
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月9日
 */
class CheckRole
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

}