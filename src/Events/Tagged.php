<?php

namespace Shengfai\LaravelAdmin\Events;

use Illuminate\Database\Eloquent\Model;

/**
 * 标签事件
 * Class Tagged
 *
 * @package App\Events
 * @author ShengFai <shengfai@qq.com>
 */
class Tagged
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $taggable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $taggable)
    {
        $this->taggable = $taggable;
    }

    /**
     * 获取可用标签对象
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getTaggable()
    {
        return $this->taggable;
    }
}
