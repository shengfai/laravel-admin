<?php
use Overtrue\Pinyin\Pinyin;

if (! function_exists('new_json_encode')) {

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

if (! function_exists('slugger')) {

    /**
     * 处理 Slug
     *
     * @param string $str
     * @return string
     */
    function slugger($str)
    {
        return (new Pinyin())->permalink($str);
    }
}

if (! function_exists('settings')) {

    /**
     * 获取或设置系统参数.
     *
     * @param string $name
     * @param string $value
     *
     * @return string|bool
     */
    function settings($name, $value = null)
    {
        static $settings = [];
        
        // 设置参数
        if (! is_null($value)) {
            return \Option::set($name, $value);
        }
        
        // 获取参数
        if (empty($settings)) {
            $settings = \Option::all();
        }
        
        return $settings[$name] ?? false;
    }
}

if (! function_exists('admin_base_path')) {

    /**
     * Get admin url.
     *
     * @param string $path
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/' . trim(config('administrator.route.prefix'), '/');
        $prefix = ($prefix == '/') ? '' : $prefix;
        $path = trim($path, '/');
        
        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }
        
        return $prefix . '/' . $path;
    }
}
