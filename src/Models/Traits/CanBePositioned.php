<?php
namespace Shengfai\LaravelAdmin\Models\Traits;

use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\Positionable;

/**
 * 可推荐
 * trait CanBePositioned
 *
 * @package \Shengfai\LaravelAdmin\Models\Traits
 * @author ShengFai <shengfai@qq.com>
 */
trait CanBePositioned
{

    /**
     * 是否被推荐指定推荐位.
     *
     * @param int $posid
     * @return boolean
     */
    public function isPositionedBy(int $posid)
    {
        return $this->positionables()
            ->ofPosid($posid)
            ->exists();
    }

    /**
     * 推荐至指定推荐位
     *
     * @param int $posid
     * @param array $attributes
     */
    public function positioning(int $posid, array $attributes)
    {
        // 更新推荐
        if ($this->isPositionedBy($posid)) {
            return $this->positions()->updateExistingPivot($posid, $attributes);
        }
        
        return $this->positions()->attach($posid, $attributes);
    }

    /**
     * 取消推荐至指定推荐位
     *
     * @param int $posid
     */
    public function unPosition(int $posid)
    {
        return $this->positions()->detach($posid);
    }

    /**
     * 推荐的数据
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function positionables()
    {
        return $this->morphMany(Positionable::class, 'positionable');
    }

    /**
     * 关联的推荐位
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function positions()
    {
        return $this->morphToMany(Position::class, 'positionable', Positionable::class)->withTimestamps();
    }
}
