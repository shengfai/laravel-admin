<?php

use Shengfai\LaravelAdmin\Models\Setting;

if (!function_exists('new_json_encode')) {

    /**
     * 对变量进行 JSON 编码
     *
     * @param mixed $value
     * @return string
     */
    function new_json_encode($value)
    {
        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

/**
 * 获取或设置系统参数
 *
 * @param string $name 配置名
 * @param string $value 默认是null为获取值，否则为更新
 * @return string|bool
 */
function settings($name, $value = null)
{
    static $settings = [];
    
    // 设置参数
    if (!is_null($value)) {
        return Setting::updateOrCreate([
            'name' => $name
        ], [
            'value' => $value
        ]);
    }
    
    // 获取参数
    if (empty($settings)) {
        $settings = Setting::pluck('value', 'name');
    }
    return $settings[$name] ?? false;
}

/**
 * 获取文件实际存储相对路径
 *
 * @param string $local_url 文件标识
 * @param string $ext 文件后缀
 * @param string $pre 文件前缀（若有值需要以/结尾）
 * @return string
 */
function file_storage_path($file, $ext = null, $pre = '')
{
    // 文件后缀
    $ext = $ext ?? strtolower(pathinfo($file, PATHINFO_EXTENSION));
    
    // 存储路径
    $hashFile = is_string($file) ? md5($file) : md5_file($file);
    return $pre . join('/', str_split($hashFile, 12)) . '.' . ($ext ? $ext : 'tmp');
}
