<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shengfai\LaravelAdmin\Models\Traits\Attributes;
use Shengfai\LaravelAdmin\Traits\Scope;

/**
 * 推荐位模型
 * Class Position.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class Position extends Model
{
    use Scope;
    use Attributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cover_pic',
        'description',
    ];

    /**
     * 关联推荐对象
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function datas(): HasMany
    {
        return $this->hasMany(Positionable::class)->sorted()->recent();
    }
}
