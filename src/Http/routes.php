<?php

use Illuminate\Support\Facades\Route;
use Shengfai\LaravelAdmin\Http\Middleware\Authenticate;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::name('admin.api.')->prefix('api')->middleware(['api'])->group(function () {

        // 授权
        Route::post('authorizations/accounts', 'Shengfai\LaravelAdmin\Http\Controllers\AuthorizationController@authorizeWithAccount')->name('accounts.authorize');
    
        // 授权后可访问接口
        Route::group(['middleware' => 'auth:api'], function () {
            
            Route::put('authorizations/current', 'AuthorizationController@update')->name('authorizations.update');          // 刷新token
            Route::delete('authorizations/current', 'AuthorizationController@destroy')->name('authorizations.destroy');     // 删除token
            
            // 自定义路由
            Route::namespace(config('administrator.namespace'))->group(function () {
                if (file_exists(config('administrator.custom_routes_file'))) {
                    include config('administrator.custom_routes_file');
                }
            });
            
            Route::namespace('Shengfai\LaravelAdmin\Http\Controllers')->group(function () {
                
                Route::get('user', 'ManagerController@show')->name('user.me');
                
                include __DIR__ . '/routes/system.php';     // 系统
                include __DIR__ . '/routes/settings.php';   // 设置
                include __DIR__ . '/routes/models.php';     // 模型
            });
        });
    });

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::name('admin.')->middleware(['web', Authenticate::class])->group(function () {

    // 自定义路由
    Route::namespace(config('administrator.namespace'))->group(function () {
        if (file_exists(config('administrator.custom_routes_file'))) {
            include config('administrator.custom_routes_file');
        }
    });

    Route::namespace('Shengfai\LaravelAdmin\Http\Controllers')->group(function () {

        // 登录页
        Route::get('login', 'AccountController@showLoginForm')->name('login');
        Route::post('login', 'AccountController@login');
        Route::post('logout', 'AccountController@logout')->name('logout');

        // Admin Dashboard
        Route::get('/', 'AdminController@main')->name('main');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        include __DIR__ . '/routes/system.php';     // 系统
        include __DIR__ . '/routes/settings.php';   // 设置
        include __DIR__ . '/routes/models.php';     // 模型
    });
});
