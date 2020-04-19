<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 权限校验
 * Class CheckRole.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月9日
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
