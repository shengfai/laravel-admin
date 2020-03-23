<?php

namespace Shengfai\LaravelAdmin\Models;

use Shengfai\LaravelAdmin\Traits\Scope;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Shengfai\LaravelAdmin\Handlers\FileHandler;

/**
 * 推荐数据模型
 * Class Positionable
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class Positionable extends Pivot
{
    use Scope;
    
    /**
     * 批量填充字段
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
        'sort'
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
     * 关联的推荐位
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    
    /**
     * 封面获取器
     *
     * @param string $path
     * @return string
     */
    public function getCoverPicAttribute($value)
    {
        // 处理图片域名
        $url = FileHandler::startsWithUrlForResource($value, config('business.domains.images'));
    
        return $url;
    }
    
    /**
     * 封面设置器
     *
     * @param string $url
     * @return string
     */
    public function setCoverPicAttribute($value)
    {
        // 处理图片域名
        $url = FileHandler::exceptUrlForResource($value, config('business.domains.images'));
    
        $this->attributes['cover_pic'] = $url;
    }
}
