<?php

namespace Shengfai\LaravelAdmin\Models\Traits;

use Shengfai\LaravelAdmin\Models\Position;

/**
 * 可推荐
 * trait CanBePositioned
 *
 * @package \Shengfai\LaravelAdmin\Models\Traits
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月17日
 */
trait CanBePositioned
{

    /**
     * 是否被推荐指定推荐位
     *
     * @param Position $position
     * @return boolean
     */
    public function isPositionedBy(Position $position)
    {
    
    }

    /**
     * 推荐至指定推荐位
     *
     * @param Position $position
     */
    public function position(Position $position)
    {
    
    }

    /**
     * 取消推荐至指定推荐位
     *
     * @param Position $position
     */
    public function unPosition(Position $position)
    {
    
    }

    /**
     * 推荐的数据
     */
    public function positionables()
    {
    }
}