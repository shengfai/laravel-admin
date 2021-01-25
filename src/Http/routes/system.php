<?php

use Illuminate\Support\Facades\Route;

// 分类管理
Route::get('{model}/types', 'TypeController@index')->name('types.index');                       // 列表
Route::get('{model}/types/create', 'TypeController@create')->name('types.create');              // 创建
Route::get('{model}/types/{type}', 'TypeController@edit')->name('types.edit');                  // 详情
Route::post('{model}/types', 'TypeController@store')->name('types.store');                      // 新增
Route::match(['put', 'patch'], 'types/{type}', 'TypeController@update')->name('types.update');  // 更新
Route::delete('types/{type}', 'TypeController@destroy')->name('types.destroy');                 // 删除

// 角色权限
Route::post('roles', 'RoleController@store')->name('roles.store');                                                         // 创建角色
Route::get('managers/{manager}/authorizations', 'ManagerController@authorizations')->name('managers.authorizations');      // 用户授权
Route::get('users/{user}/password', 'UserController@password')->name('users.reset_password');                              // 重置密码
Route::get('roles/{role}/authorizations', 'RoleController@show')->where('role', '[0-9]+')->name('roles.authorizations');   // 查看角色
Route::get('roles/{role}/permissions', 'RoleController@permissions')->where('role', '[0-9]+')->name('roles.permissions');  // 查看权限
Route::post('roles/{role}/permissions', 'RoleController@authorize')->where('role', '[0-9]+')->name('roles.authorize');     // 角色授权

// 内容体系
Route::match(['get', 'post'], 'dimensions/{dimension}/modules', 'DimensionController@modules')->name('dimensions.modules');
Route::match(['get', 'post'], 'taggables/{model}/{id}', 'TagController@attach')->name('tags.attach');

// 推荐位
Route::get('positions/{position}/datas', 'PositionController@show')->name('positions.datas');                // 推荐位详情
Route::get('positions/{position}/create', 'PositionableController@create')->name('positions.datas.create');  // 添加推送内容
Route::get('positions/datas/push', 'PositionableController@push')->name('positions.datas.push');             // 推送内容
Route::get('positions/models/types', 'PositionController@model')->name('positions.models');                  // 推荐内容的种类

// 资源相关
Route::resources(
    [
        'menus'           => 'MenuController',           // 菜单资源
        'users'           => 'UserController',           // 用户资源
        'notices'         => 'NoticeController',         // 消息资源
        'managers'        => 'ManagerController',        // 管理员资源
        'consumers'       => 'ConsumerController',       // 用户资源
        'permissions'     => 'PermissionController',     // 权限资源
        'positions/datas' => 'PositionableController',   // 推荐内容资源
    ]
);

// 插件其他
Route::get('plugs/icon.html', 'PlugController@icon')->name('plugs.icon');                         // 选择图标
Route::get('plugs/map/poipicker.html', 'PlugController@poipicker')->name('plugs.map.poipicker');  // 选择POI点
Route::get('plugs/upfile.html', 'PlugController@upfile')->name('plugs.upfile');                   // 上传文件
Route::post('plugs/upload', 'PlugController@upload')->name('plugs.upload');                       // 文件上传
Route::post('plugs/upstate', 'PlugController@upstate')->name('plugs.upstate');                    // 文件状态检查
