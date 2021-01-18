<?php

namespace Shengfai\LaravelAdmin\Http\Middleware;

use Closure;

class ValidateModel
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $modelName = $request->route()->parameter('model');

        app()->singleton('admin.item_config', function ($app) use ($modelName) {
            $configFactory = app('admin.config_factory');

            return $configFactory->make($modelName, true);
        });

        return $next($request);
    }
}
