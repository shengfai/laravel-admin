<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

use Illuminate\Database\Migrations\Migration;
use Shengfai\LaravelAdmin\Contracts\Conventions;

class SeedAdminData extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 顶级菜单
        $parent_id = DB::table('menus')->insertGetId([
            'code' => 'dashboards',
            'parent_id' => 0,
            'name' => '控制台',
            'url' => '#',
            'sort' => 100
        ]);
        
        $parent_id = DB::table('menus')->insertGetId([
            'code' => 'dashboards.manages',
            'parent_id' => $parent_id,
            'name' => '管理控制台',
            'url' => '#'
        ]);
        
        DB::table('menus')->insert([
            [
                'code' => '',
                'parent_id' => $parent_id,
                'name' => '我的资源',
                'url' => '/dashboard'
            ]
        ]);
        
        // 顶级菜单
        $parent_id = DB::table('menus')->insertGetId([
            'code' => 'systems',
            'parent_id' => 0,
            'name' => '系统设置',
            'url' => '#',
            'sort' => 10
        ]);
        
        // 二级菜单
        DB::table('menus')->insert([
            [
                'code' => 'systems.settings',
                'parent_id' => $parent_id,
                'name' => '系统管理',
                'url' => '#',
                'sort' => 40
            ],
            [
                'code' => 'systems.permissions',
                'parent_id' => $parent_id,
                'name' => '访问权限',
                'url' => '#',
                'sort' => 30
            ],
            [
                'code' => 'systems.contents',
                'parent_id' => $parent_id,
                'name' => '内容体系',
                'url' => '#',
                'sort' => 20
            ],
            [
                'code' => 'systems.logs',
                'parent_id' => $parent_id,
                'name' => '日志管理',
                'url' => '#',
                'sort' => 10
            ]
        ]);
        
        // 系统管理
        $parent_id = DB::table('menus')->where('name', '系统管理')->value('id');
        DB::table('menus')->insert([
            [
                'parent_id' => $parent_id,
                'name' => '菜单管理',
                'url' => '/menus',
                'icon' => 'fa fa-leaf',
                'sort' => 80
            ],
            [
                'parent_id' => $parent_id,
                'name' => '系统参数',
                'url' => '/settings/system',
                'icon' => 'fa fa-gear',
                'sort' => 90
            ]
        ]);
        
        // 访问权限
        $parent_id = DB::table('menus')->where('name', '访问权限')->value('id');
        DB::table('menus')->insert([
            [
                'parent_id' => $parent_id,
                'name' => '角色管理',
                'url' => '/roles',
                'icon' => 'fa fa-user-secret',
                'sort' => 20
            ],
            [
                'parent_id' => $parent_id,
                'name' => '用户管理',
                'url' => '/managers',
                'icon' => 'fa fa-user',
                'sort' => 10
            ]
        ]);
        
        // 内容体系
        $parent_id = DB::table('menus')->where('name', '内容体系')->value('id');
        DB::table('menus')->insert([
            [
                'parent_id' => $parent_id,
                'name' => '模型管理',
                'url' => '/modules',
                'icon' => 'fa fa-database',
                'sort' => 30
            ],
            [
                'parent_id' => $parent_id,
                'name' => '维度管理',
                'url' => '/dimensions',
                'icon' => 'fa fa-asterisk',
                'sort' => 20
            ],
            [
                'parent_id' => $parent_id,
                'name' => '标签管理',
                'url' => '/tags',
                'icon' => 'fa fa-hashtag',
                'sort' => 10
            ]
        ]);
        
        // 日志管理
        $parent_id = DB::table('menus')->where('name', '日志管理')->value('id');
        DB::table('menus')->insert([
            'parent_id' => $parent_id,
            'name' => '系统日志',
            'url' => '/activities',
            'icon' => 'fa fa-code'
        ]);
        
        // 顶级菜单
        $parent_id = DB::table('menus')->insertGetId([
            'code' => 'contents',
            'parent_id' => 0,
            'name' => '内容管理',
            'url' => '#',
            'sort' => 90
        ]);
        
        $parent_id = DB::table('menus')->insertGetId([
            'code' => '',
            'parent_id' => $parent_id,
            'name' => '推荐管理',
            'url' => '#'
        ]);
        
        DB::table('menus')->insert([
            [
                'parent_id' => $parent_id,
                'name' => '推荐位管理',
                'url' => '/positions',
                'icon' => 'fa fa-paw',
                'sort' => 20
            ]
        ]);
        
        // 添加配置
        \Option::set([
            'app_name' => 'LaraAdmin',
            'app_version' => Conventions::VERSION,
            'site_name' => config('app.name'),
            'site_keywords' => 'administrative',
            'site_description' => 'An administrative interface package for Laravel.',
            'site_copy' => '@ LaravelAdmin',
            'site_icon' => '/admin/favicon.ico'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menus')->truncate();
        DB::table('options')->truncate();
    }
}
