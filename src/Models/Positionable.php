<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Shengfai\LaravelAdmin\Models\Traits\Attributes;
use Shengfai\LaravelAdmin\Traits\Scope;

/**
 * 推荐数据模型
 * Class Positionable.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class Positionable extends Pivot
{
    use Scope;
    use Attributes;

    /**
     * 批量填充字段.
     *
     * @var array
     */
    protected $fillable = [
        'position_id',
        'positionable_type',
        'positionable_id',
        'title',
        'cover_pic',
        'description',
        'sort',
    ];

    /**
     * 获得拥有此推荐的模型。
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function positionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 关联的推荐位.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
