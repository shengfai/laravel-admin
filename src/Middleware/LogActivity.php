<?php

namespace Shengfai\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 用户行为
 * Class LogActivity
 *
 * @package \Shengfai\LaravelAdmin\Middleware
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月9日
 */
class LogActivity
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