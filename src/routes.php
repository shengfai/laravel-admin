<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Illuminate\Support\Facades\Route;

/*
 * Routes
 */

Route::group([
    'domain' => config('administrator.domain'),
    'prefix' => config('administrator.prefix'),
    'middleware' => [
        Shengfai\LaravelAdmin\Http\Middleware\LogActivity::class,
        Shengfai\LaravelAdmin\Http\Middleware\Authenticate::class
    ],
], function () {
    Route::name('admin.')->group(function () {

        // 登录页
        Route::get('login', 'Shengfai\LaravelAdmin\Controllers\AccountsController@showLoginForm')->name('login');
        Route::post('login', 'Shengfai\LaravelAdmin\Controllers\AccountsController@login');

        //Admin Dashboard
        Route::get('/', ['as' => 'main', 'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@main']);
        Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'Shengfai\LaravelAdmin\Controllers\DashboardsController@index']);

        // 自定义路由
        Route::group([
            'namespace' => config('administrator.namespace'),
        ], function () {
            with(config('administrator.custom_routes_file'), function ($routes_file) {
                if (file_exists($routes_file)) {
                    include config('administrator.custom_routes_file');
                }
            });
        });

        // 登录/登出
        Route::post('logout', 'Shengfai\LaravelAdmin\Controllers\AccountsController@logout')->name('logout');                               // 登出

        // 分类管理
        Route::get('{model}/types', 'Shengfai\LaravelAdmin\Controllers\TypesController@index')->name('types.index');                        // 列表
        Route::get('{model}/types/create', 'Shengfai\LaravelAdmin\Controllers\TypesController@create')->name('types.create');               // 创建
        Route::get('{model}/types/{type}', 'Shengfai\LaravelAdmin\Controllers\TypesController@edit')->name('types.edit');                   // 详情
        Route::post('{model}/types', 'Shengfai\LaravelAdmin\Controllers\TypesController@store')->name('types.store');                       // 新增
        Route::match(['put', 'patch'], 'types/{type}', 'Shengfai\LaravelAdmin\Controllers\TypesController@update')->name('types.update');    // 更新
        Route::delete('types/{type}', 'Shengfai\LaravelAdmin\Controllers\TypesController@destroy')->name('types.destroy');                  // 删除

        // 系统设置
        Route::resource('menus', 'Shengfai\LaravelAdmin\Controllers\MenusController');                                                      // 菜单
        Route::resource('notices', 'Shengfai\LaravelAdmin\Controllers\NoticesController');                                                  // 消息

        // 角色权限
        Route::resource('users', 'Shengfai\LaravelAdmin\Controllers\UsersController');                                                                  // 用户
        Route::get('managers', 'Shengfai\LaravelAdmin\Controllers\ManagersController@index')->name('managers.index');                                   // 管理员列表
        Route::get('users/{user}/authorizations', 'Shengfai\LaravelAdmin\Controllers\UsersController@authorizations')->name('users.authorizations');    // 用户授权
        Route::get('users/{user}/password', 'Shengfai\LaravelAdmin\Controllers\UsersController@password')->name('users.reset_password');                // 重置密码
        Route::resource('permissions', 'Shengfai\LaravelAdmin\Controllers\PermissionsController');                                                      // 权限
        Route::get('role/{role}', 'Shengfai\LaravelAdmin\Controllers\RolesController@show')->name('roles.show')->where(['role' => '[0-9]+']);          // 查看角色
        Route::get('authorizations/{role}', 'Shengfai\LaravelAdmin\Controllers\AuthorizationsController@show')->name('authorizations.show');            // 查看授权
        Route::post('authorizations/{role}', 'Shengfai\LaravelAdmin\Controllers\AuthorizationsController@store')->name('authorizations.store');         // 角色授权

        // 内容体系
        Route::match(['get', 'post'], 'dimensions/{dimension}/modules', 'Shengfai\LaravelAdmin\Controllers\DimensionsController@modules')->name('dimensions.modules');
        Route::match(['get', 'post'], 'taggables/{model}/{id}', 'Shengfai\LaravelAdmin\Controllers\TagsController@attach')->name('tags.attach');    // 关联标签

        // 推荐位管理
        Route::get('positions/{position}/datas', 'Shengfai\LaravelAdmin\Controllers\PositionsController@show')->name('positions.datas');                // 推荐位详情
        Route::get('positions/{position}/create', 'Shengfai\LaravelAdmin\Controllers\PositionablesController@create')->name('positions.datas.create');  // 添加推送内容
        Route::get('positions/datas/push', 'Shengfai\LaravelAdmin\Controllers\PositionablesController@push')->name('positions.datas.push');             // 推送内容
        Route::resource('positions/datas', 'Shengfai\LaravelAdmin\Controllers\PositionablesController');                                                // 推荐位内容
        Route::get('positions/models/types', 'Shengfai\LaravelAdmin\Controllers\PositionsController@model')->name('positions.models');                  // 推荐内容的种类

        // 插件其他
        Route::get('plugs/icon.html', 'Shengfai\LaravelAdmin\Controllers\PlugsController@icon')->name('plugs.icon');                            // 选择图标
        Route::get('plugs/upfile.html', 'Shengfai\LaravelAdmin\Controllers\PlugsController@upfile')->name('plugs.upfile');                      // 上传文件
        Route::get('plugs/map/poipicker.html', 'Shengfai\LaravelAdmin\Controllers\PlugsController@poipicker')->name('plugs.map.poipicker');     // 选择POI点
        Route::post('plugs/upload', 'Shengfai\LaravelAdmin\Controllers\PlugsController@upload')->name('plugs.upload');                          // 文件上传
        Route::post('plugs/upstate', 'Shengfai\LaravelAdmin\Controllers\PlugsController@upstate')->name('plugs.upstate');                       // 文件状态检查

        // 用户管理
        Route::resource('consumers', 'Shengfai\LaravelAdmin\Controllers\ConsumersController');

        Route::group(['middleware' => ['Shengfai\LaravelAdmin\Http\Middleware\ValidateSettings', 'Shengfai\LaravelAdmin\Http\Middleware\PostValidate']], function () {

            //Settings Pages
            Route::get('settings/{settings}',[
                'as'   => 'settings',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@settings',
            ]);

            //Save Item
            Route::post('settings/{settings}/save', [
                'as'   => 'settings.save',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@settings',
            ]);
        });

        //The route group for all other requests needs to validate admin, model, and add assets
        Route::group(['middleware' => ['Shengfai\LaravelAdmin\Http\Middleware\ValidateModel', 'Shengfai\LaravelAdmin\Http\Middleware\PostValidate']], function () {

            //Model Index
            Route::get('{model}', [
                'as'   => 'model.index',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@index',
            ]);

            //Get results
            Route::get('{model}/results', [
                'as'   => 'model.results',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@results',
            ]);

            //New Item
            Route::get('{model}/new', [
                'as'   => 'model.new',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@create',
            ]);

            //Get Item
            Route::get('{model}/{id}', [
                'as'   => 'model.item',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@item',
            ]);

            //Save Item
            Route::post('{model}/{id?}/save', [
                'as'   => 'model.save',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@save',
            ]);

            //Batch Delete Item
            Route::delete('{model}/batch_delete', [
                'as'   => 'model.batch_delete',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@delete',
            ]);

            Route::delete('{model}/{id}/delete', [
                'as'   => 'model.delete',
                'uses' => 'Shengfai\LaravelAdmin\Controllers\AdminController@delete',
            ]);

        });
    });
});