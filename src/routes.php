<?php

use Illuminate\Support\Facades\Route;

/*
 * Routes
 */
Route::middleware('web')->group(function () {
    Route::group([
        'domain' => config('administrator.domain'),
        'prefix' => config('administrator.prefix'),
        'middleware' => [
            Shengfai\LaravelAdmin\Middleware\CheckRole::class,
            Shengfai\LaravelAdmin\Middleware\LogActivity::class
        ]
    ], function () {
        
        Route::name(config('administrator.name'))->group(function () {
            
            // 登录页
            Route::get('login', 'Shengfai\LaravelAdmin\Controllers\AccountsController@showLoginForm')->name('login');
            Route::post('login', 'Shengfai\LaravelAdmin\Controllers\AccountsController@login');
            
            // 认证
            Route::group([
                'middleware' => Shengfai\LaravelAdmin\Middleware\ValidateAdmin::class
            ], function ($router) {
                
                // 自定义路由
                Route::group([
                    'namespace' => config('administrator.namespace')
                ], function () {
                    with(config('administrator.custom_routes_file'), function ($routes_file) {
                       if( file_exists($routes_file)) {
                            include config('administrator.custom_routes_file');
                        }
                    });
                });
                
                // 登录/登出
                Route::post('logout', 'Shengfai\LaravelAdmin\Controllers\AccountsController@logout')->name('logout'); // 登出
                                                                                                                      
                // 控制台首页
                Route::get('/', 'Shengfai\LaravelAdmin\Controllers\DashboardsController@console')->name('dashboards.console'); // 控制台
                Route::get('dashboards', 'Shengfai\LaravelAdmin\Controllers\DashboardsController@index')->name('dashboards.index'); // 仪表盘
                                                                                                                                    
                // 分类管理
                Route::get('{model}/types', 'Shengfai\LaravelAdmin\Controllers\TypesController@index')->name('types.index');              // 列表
                Route::get('{model}/types/create', 'Shengfai\LaravelAdmin\Controllers\TypesController@create')->name('types.create');     // 修改
                Route::get('{model}/types/{type}', 'Shengfai\LaravelAdmin\Controllers\TypesController@edit')->name('types.edit');         // 详情
                Route::post('{model}/types', 'Shengfai\LaravelAdmin\Controllers\TypesController@store')->name('types.store');             // 新增
                Route::put('types/{type}', 'Shengfai\LaravelAdmin\Controllers\TypesController@update')->name('types.update');             // 更新
                Route::delete('types/{type}', 'Shengfai\LaravelAdmin\Controllers\TypesController@destroy')->name('types.destroy');        // 删除
                
                // 系统设置
                Route::resource('logs', 'Shengfai\LaravelAdmin\Controllers\LogsController'); // 日志
                Route::resource('menus', 'Shengfai\LaravelAdmin\Controllers\MenusController'); // 菜单
                Route::resource('settings', 'Shengfai\LaravelAdmin\Controllers\SettingsController'); // 系统设置
                                                                                                     
                // 角色权限
                Route::resource('users', 'Shengfai\LaravelAdmin\Controllers\UsersController'); // 用户
                Route::get('managers', 'Shengfai\LaravelAdmin\Controllers\ManagersController@index')->name('managers.index'); // 管理员列表
                Route::get('users/{user}/authorizations', 'Shengfai\LaravelAdmin\Controllers\UsersController@authorizations')->name('users.authorizations'); // 用户授权
                Route::get('users/{user}/password', 'Shengfai\LaravelAdmin\Controllers\UsersController@password')->name('users.reset_password'); // 重置密码
                Route::resource('roles', 'Shengfai\LaravelAdmin\Controllers\RolesController'); // 角色
                Route::resource('permissions', 'Shengfai\LaravelAdmin\Controllers\PermissionsController'); // 权限
                Route::get('authorizations/{role}', 'Shengfai\LaravelAdmin\Controllers\AuthorizationsController@show')->name('authorizations.show'); // 查看授权
                Route::post('authorizations/{role}', 'Shengfai\LaravelAdmin\Controllers\AuthorizationsController@store')->name('authorizations.store'); // 角色授权
                                                                                                                                                        
                // 推荐位管理
                Route::resource('positions', 'Shengfai\LaravelAdmin\Controllers\PositionsController'); // 推荐位
                Route::get('positions/{position}/datas', 'Shengfai\LaravelAdmin\Controllers\PositionDatasController@index')->name('positions.datas.index'); // 获取推荐内容
                Route::get('positions/{position}/create', 'Shengfai\LaravelAdmin\Controllers\PositionDatasController@create')->name('positions.datas.create'); // 添加推送内容
                Route::get('positions/datas/push', 'Shengfai\LaravelAdmin\Controllers\PositionDatasController@push')->name('positions.datas.push'); // 推送内容
                Route::resource('positions/datas', 'Shengfai\LaravelAdmin\Controllers\PositionDatasController'); // 推荐位内容
                Route::get('positions/models/types', 'Shengfai\LaravelAdmin\Controllers\PositionsController@model')->name('positions.models'); // 推荐内容的种类
                                                                                                                                               
                // 插件其他
                Route::get('plugs/icon.html', 'Shengfai\LaravelAdmin\Controllers\PlugsController@icon')->name('plugs.icon'); // 选择图标
                Route::get('plugs/upfile.html', 'Shengfai\LaravelAdmin\Controllers\PlugsController@upfile')->name('plugs.upfile'); // 上传文件
                Route::get('plugs/map/poipicker.html', 'Shengfai\LaravelAdmin\Controllers\PlugsController@poipicker')->name('plugs.upfile'); // 选择POI点
                Route::post('plugs/upload', 'Shengfai\LaravelAdmin\Controllers\PlugsController@upload')->name('plugs.upload'); // 文件上传
                Route::post('plugs/upstate', 'Shengfai\LaravelAdmin\Controllers\PlugsController@upstate')->name('plugs.upstate'); // 文件状态检查
                                                                                                                                  
                // 用户管理
                Route::resource('consumers', 'Shengfai\LaravelAdmin\Controllers\ConsumersController');
            });
        });
    });
});