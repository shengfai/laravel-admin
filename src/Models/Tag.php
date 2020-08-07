<?php

namespace Shengfai\LaravelAdmin\Models;

use Spatie\Tags\Tag as OriginalTag;
use Illuminate\Database\Eloquent\Builder;

/**
 * 标签模型
 * Class Tag
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年7月31日
 */
class Tag extends OriginalTag
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dimension_id',
        'parent_id',
        'name',
        'slug',
        'type',
        'order_column',
        'remark',
        'sort'
    ];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'parent'
    ];

    /**
     * 模型事件
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->type = $model->dimension->name;
        });
    }

    /**
     * 获取可排序字段
     *
     * @return string
     */
    public function getOrderColumnName()
    {
        return $this->sortable['order_column_name'] ?? 'sort';
    }

    /**
     * 查询指定标签
     *
     * @param string $name
     * @param string $type
     * @param string $locale
     */
    public static function findFromString(string $name, string $type = null, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        return static::query()->where("name->{$locale}", $name)->withType($type)->first();
    }

    /**
     * 所属维度
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    /**
     * 父标签.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Tag::class, 'parent_id')->withDefault([
            'name' => '未设置'
        ]);
    }

    /**
     * 子标签.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }

    /**
     * 关联模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taggables()
    {
        return $this->hasMany(Taggable::class);
    }

    /**
     * 查询指定父节点记录.
     *
     * @param int $parent_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfParent(Builder $query, int $parent_id = null)
    {
        if (is_null($parent_id)) {
            return $query;
        }
        
        return $query->where('parent_id', $parent_id);
    }
}
