<?php

/*
 * This file is part of the shengfai/laravel-admin.
 * (c) shengfai <shengfai@qq.com>
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Models\Traits;

use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\Positionable;

/**
 * 可推荐
 * trait CanBePositioned.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月17日
 */
trait CanBePositioned
{

    /**
     * 是否被推荐指定推荐位.
     *
     * @return boolean
     */
    public function isPositionedBy(Position $position)
    {
        return $this->positionables()->ofPosition($position->id)->exists();
    }

    /**
     * 推荐至指定推荐位.
     */
    public function position(Position $position)
    {
    
    }

    /**
     * 取消推荐至指定推荐位.
     */
    public function unPosition(Position $position)
    {
    }

    /**
     * 推荐的数据.
     *
     * @return
     *
     */
    public function positionables()
    {
        return $this->morphMany(Positionable::class, 'positionable');
    }
}
