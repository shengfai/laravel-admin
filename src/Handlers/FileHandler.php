<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Handlers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Shengfai\LaravelAdmin\Exceptions\InvalidArgumentException;

/**
 * 文件处理器
 * Class FileHandler.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class FileHandler
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * 根据文件后缀获取文件MINE.
     *
     * @param array $ext  文件后缀
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
     * @param string $path
     *
     * @return string
     */
    public static function startsWithUrlForResource(string $path = null, string $url)
    {
        // 未设置
        if (empty($path) || Str::is('http*', $path)) {
            return $path;
        }

        // 填充域名
        return $url.$path;
    }

    /**
     * 排除资源域名.
     *
     * @param string $path
     *
     * @return string
     */
    public static function exceptUrlForResource(string $path = null, string $url)
    {
        // 处理图片域名
        if (Str::startsWith($path, $url)) {
            $path = Str::after($path, $url);
        }

        return $path;
    }

    /**
     * 存储文件.
     *
     * @param string $fileStoragePath
     * @param File   $file
     */
    public function save($file)
    {
        // 获取文件后缀
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'tmp';

        // 不被允许上传的文件后缀
        if (!in_array($extension, $this->getAllowedExts())) {
            throw new InvalidArgumentException(trans('administrator.invalid_file_exts'), self::HTTP_UNPROCESSABLE_ENTITY);
        }

        // 文件存储路径
        $filename = file_storage_path($file, $extension);

        // 将文件移动到目标存储路径
        $result = Storage::putFileAs(dirname($filename), $file, pathinfo($filename, PATHINFO_BASENAME));

        return [
            'path' => $filename,
            'url' => $this->getFileUrl($filename),
        ];
    }

    /**
     * 获取文件当前URL地址
     *
     * @param string $file 文件存储路径
     *
     * @return bool|string
     */
    public function getFileUrl($filePath)
    {
        // 判断文件是否存在
        if (!Storage::exists($filePath)) {
            return false;
        }

        // 获取当前存储配置
        $storageConfig = config('filesystems.disks')[config('filesystems.default')];

        // 是否限制访问
        if (isset($storageConfig['visibility']) && 'private' === $storageConfig['visibility']) {
            return Storage::privateDownloadUrl($filePath);
        }

        return Storage::url($filePath);
    }

    /**
     * 获取允许上传的文件后缀
     *
     * @return mixed|\Illuminate\Config\Repository
     */
    public function getAllowedExts()
    {
        return config('administrator.allowed_upload_file_exts');
    }
}
