<?php
namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shengfai\LaravelAdmin\Traits\Scope;
use Shengfai\LaravelAdmin\Models\Traits\Attributes;


/**
 * 推荐数据模型
 * Class Positionable
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 */
class Positionable extends Model
{
    use Scope;
    use Attributes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'positionable';

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
        'created_at',
        'updated_at'
    ];

    /**
     * 获取推荐模型
     *
     * @param string $key
     * @return string|array
     */
    public function getPositionableModel(string $key = null)
    {
        $availablePositionedModels = config('administrator.available_positioned_models');
        $modelName = Str::of(class_basename($this->positionable_type))->snake()
            ->lower()
            ->__toString();
        return $key ? $availablePositionedModels[$modelName][$key] : $availablePositionedModels[$modelName];
    }

    /**
     * 获得拥有此推荐的模型
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
     * 查询指定推荐位记录
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $position_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfPosid(Builder $query, int $position_id = null)
    {
        if (is_null($position_id)) {
            return $query;
        }
        
        return $query->where('position_id', $position_id);
    }
}