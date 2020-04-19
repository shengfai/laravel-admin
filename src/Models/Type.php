<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shengfai\LaravelAdmin\Traits\Scope;

/**
 * 分类模型.
 *
 * Class Type
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class Type extends Model
{
    use Scope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'model_type',
        'name',
        'code',
        'cover_pic',
        'user_id',
        'is_recommend',
        'description',
        'sort',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * 判断该分类是否被使用.
     *
     * @return boolean
     */
    public function isUsed()
    {
        $class = 'App\Models\\'.$this->model_type;

        return $class::ofType($this->id)->exists();
    }

    /**
     * 父类型.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Type::class, 'parent_id');
    }

    /**
     * 子类型.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Type::class, 'parent_id');
    }

    /**
     * 关联所属用户.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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

    /**
     * 查询指定模型记录.
     *
     * @param string $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfModel(Builder $query, string $model = null)
    {
        if (is_null($model)) {
            return $query;
        }

        $class = ucfirst(class_basename($model));

        return $query->where('model_type', $class);
    }

    /**
     * 模型设置器.
     *
     * @param string $url
     *
     * @return string
     */
    public function setModelTypeAttribute($value)
    {
        $class = ucfirst(class_basename($value));
        $this->attributes['model_type'] = $class;
    }
}
