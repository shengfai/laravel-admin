<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Models\Traits;

use Shengfai\LaravelAdmin\Handlers\FileHandler;

/**
 * 模型属性
 * trait Attributes.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月12日
 */
trait Attributes
{

    /**
     * 关键词获取器.
     *
     * @param string $value
     * @return array
     */
    public function getKeywordsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        return json_decode($value, true);
    }

    /**
     * 关键词设置器.
     *
     * @param mixed $value
     * @return string
     */
    public function setKeywordsAttribute($value)
    {
        if (empty($value)) {
            $value = [];
        } elseif (is_string($value)) {
            $value = str_replace([
                ' ',
                ',',
                '，'
            ], ',', $value);
            $value = explode(',', (string)$value);
        }
        
        $this->attributes['keywords'] = new_json_encode($value);
    }

    /**
     * 封面获取器.
     *
     * @param string $path
     * @return string
     */
    public function getCoverPicAttribute($value)
    {
        // 处理图片域名
        $domain = config('uploader.base_uri') ?? env('APP_URL') . '/storage';
        $url = FileHandler::startsWithUrlForResource($domain, $value);
        
        return $url;
    }

    /**
     * 封面设置器.
     *
     * @param string $url
     * @return string
     */
    public function setCoverPicAttribute($value)
    {
        // 处理图片域名
        $domain = config('uploader.base_uri') ?? env('APP_URL') . '/storage';
        $url = FileHandler::exceptUrlForResource($domain, $value);
        
        $this->attributes['cover_pic'] = $url;
    }
}
