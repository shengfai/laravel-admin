<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Tags\Tag;

/**
 * 标签关联模型
 * Class Taggable.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年7月31日
 */
class Taggable extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taggables';
    
    /**
     * 关联的标签.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
