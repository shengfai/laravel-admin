<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Shengfai\LaravelAdmin\Traits\Scope;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 推荐数据模型
 * Class PositionData
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class PositionData extends Model
{
    use Scope;
    
    /**
     * 批量填充字段
     *
     * @var array
     */
    protected $fillable = [
        'position_id',
        'target_type',
        'target_id',
        'title',
        'cover_pic',
        'description'
    ];
    
    /**
     * 追加到模型数组表单的访问器.
     *
     * @var array
     */
    protected $appends = [
        'link'
    ];

    /**
     * 获得拥有此推荐的模型。
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 关联的推荐位
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
