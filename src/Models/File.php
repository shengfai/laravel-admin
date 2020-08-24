<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 资源模型
 * Class File.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年5月19日
 */
class File extends Model
{
    use SoftDeletes;
    
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'mime',
        'hash',
        'size',
        'original_name',
        'relative_url',
        'url',
        'remark'
    ];

    /**
     * 通过 url 获取文件模型
     *
     * @param string $url
     * @return Shengfai\LaravelAdmin\Models\File|string
     */
    public static function get(string $url = null)
    {
        if (filled($url) && self::whereUrl($url)->exists()) {
            return self::whereUrl($url)->first();
        }
        
        return $url;
    }

    /**
     * 查询指定 Hash 值记录.
     *
     * @param string $hash
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfHash(Builder $query, string $hash = null)
    {
        if (is_null($hash)) {
            return $query;
        }
        
        return $query->where('hash', $hash);
    }
}
