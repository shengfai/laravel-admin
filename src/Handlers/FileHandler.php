<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Handlers;

use Illuminate\Support\Str;

/**
 * 文件处理器
 * Class FileHandler.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class FileHandler
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * 根据文件后缀获取文件MINE.
     *
     * @param array $ext 文件后缀
     * @param array $mine 文件后缀MINE信息
     *
     * @return string
     */
    public function getFileMine($ext, $mine = [])
    {
        $mines = config('mines');
        foreach (is_string($ext) ? explode(',', $ext) : $ext as $e) {
            if (isset($mines[strtolower($e)])) {
                $_exinfo = $mines[strtolower($e)];
                $mine[] = is_array($_exinfo) ? join(',', $_exinfo) : $_exinfo;
            }
        }
        
        return join(',', array_unique($mine));
    }

    /**
     * 填充资源域名.
     *
     * @param string $url
     * @param string $path
     * @return string
     */
    public static function startsWithUrlForResource(string $url, string $path = null)
    {
        // 未设置
        if (empty($path) || Str::is('http*', $path)) {
            return $path;
        }
        
        // 填充域名
        return $url . $path;
    }

    /**
     * 排除资源域名.
     *
     * @param string $url
     * @param string $path
     * @return string
     */
    public static function exceptUrlForResource(string $url, string $path = null)
    {
        // 处理图片域名
        if (Str::startsWith($path, $url)) {
            $path = Str::after($path, $url);
        }
        
        return $path;
    }
}
