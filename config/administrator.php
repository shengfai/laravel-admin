<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

use App\Models\User;

return [

    /*
     * Domain for routing.
     * @type string
     */
    'domain' => env('CONSOLE_DOMAIN', ''),

    /*
     * Prefix for routing.
     *
     * @type string
     */
    'prefix' => env('CONSOLE_PREFIX', 'console'),

    /*
     * Namespace for controllers.
     *
     * @type string
     */
    'namespace' => '\App\Http\Controllers\Admin',

    /*
     * The path to your model config directory
     *
     * @type string
     */
    'model_config_path' => config_path('administrator'),

    /*
     * The path to your settings config directory
     *
     * @type string
     */
    'settings_config_path' => config_path('administrator/settings'),

    /*
     * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
     * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
     *
     * @type closure
     */
    'permission' => function () {
        return Auth::check();
    },

    /*
     * This determines if you will have a dashboard (whose view you provide in the dashboard_view option) or a non-dashboard home
     * page (whose menu item you provide in the home_page option)
     *
     * @type bool
     */
    'use_dashboard' => true,

    /*
     * If you want to create a dashboard view, provide the view string here.
     *
     * @type string
     */
    'dashboard_view' => 'admin::console',

    /*
     * The menu item that should be used as the default landing page of the administrative section
     *
     * @type string
     */
    'home_page' => 'page.dashboards',

    /*
     * The login path is the path where Administrator will send the user if they fail a permission check
     *
     * @type string
     */
    'login_path' => 'admin.login',

    /*
     * This is the key of the return path that is sent with the redirection to your login_action. Session::get('redirect') will hold the return URL.
     *
     * @type string
     */
    'login_redirect_key' => 'redirect',

    /*
     * Global default rows per page
     *
     * @type int
     */
    'global_rows_per_page' => 20,

    /*
     * Custom routes file
     *
     * @type string
     */
    'custom_routes_file' => base_path('routes/administrator.php'),

    /*
     * The models
     *
     * @type array
     */
    'models' => [
        'settings.system',
        'roles',
        'activities',
        'positions',
        'modules',
        'dimensions',
        'tags'
    ],

    /*
     * The types title
     *
     * @type array
     */
    'types_title' => [],

    /*
     * Available positioned models
     *
     * @type array
     */
    'available_positioned_models' => [
        'user' => [
            'name' => '用户模型',
            'model' => User::class
        ]
    ],

    /*
     * Available notified topics
     *
     * @type array
     */
    'available_notified_topics' => [
        'remind' => '提醒'
    ]
];