<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

if (!function_exists('new_json_encode')) {

    /**
     * 对变量进行 JSON 编码
     *
     * @param mixed $value
     *
     * @return string
     */
    function new_json_encode($value)
    {
        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists('settings')) {

    /**
     * 获取或设置系统参数.
     *
     * @param string $name 配置名
     * @param string $value 默认是null为获取值，否则为更新
     *
     * @return string|bool
     */
    function settings($name, $value = null)
    {
        static $settings = [];
        
        // 设置参数
        if (!is_null($value)) {
            return \Option::set($name, $value);
        }
        
        // 获取参数
        if (empty($settings)) {
            $settings = \Option::all();
        }
        
        return $settings[$name] ?? false;
    }
}

if (!function_exists('admin_base_path')) {

    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/' . trim(config('administrator.route.prefix'), '/');
        
        $prefix = ($prefix == '/') ? '' : $prefix;
        
        $path = trim($path, '/');
        
        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?  : '/';
        }
        
        return $prefix . '/' . $path;
    }
}
