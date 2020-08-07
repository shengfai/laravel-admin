<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 清除缓存
        Cache::flush();

        // 先创建权限
        Permission::create([
            'name' => 'systems',
            'title' => '系统设置',
        ]);
        Permission::create([
            'name' => 'systems.settings',
            'title' => '系统管理',
        ]);
        Permission::create([
            'name' => 'systems.permissions',
            'title' => '访问权限',
        ]);
        Permission::create([
            'name' => 'systems.contents',
            'title' => '内容体系设置',
        ]);
        Permission::create([
            'name' => 'systems.logs',
            'title' => '日志管理',
        ]);
        Permission::create([
            'name' => 'dashboards',
            'title' => '控制台',
        ]);
        Permission::create([
            'name' => 'dashboards.manages',
            'title' => '管理控制台',
        ]);
        Permission::create([
            'name' => 'contents',
            'title' => '内容管理',
        ]);

        // 创建站长角色，并赋予权限
        $founder = Role::create([
            'name' => 'Founder',
            'remark' => '站长',
        ]);
        $founder->givePermissionTo([
            'systems',
            'systems.settings',
            'systems.permissions',
            'systems.contents',
            'systems.logs',
            'dashboards',
            'dashboards.manages',
            'contents'
        ]);

        // 授权
        $user = new User();
        $user->name = 'founder';
        $user->type = User::TYPE_ADMINISTRATOR;
        $user->phone = '13123456789';
        $user->email = 'shengfai@qq.com';
        $user->password = 111111;
        $user->email_verified_at = now();
        $user->save();

        // 初始化用户角色
        config([
            'auth.providers.users.model' => User::class,
        ]);

        $user->assignRole('Founder');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create([
            'name' => 'Maintainer',
            'remark' => '运营人员',
        ]);
        $maintainer->givePermissionTo('dashboards');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
