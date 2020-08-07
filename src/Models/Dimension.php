<?php

namespace Shengfai\LaravelAdmin\Models;

use Overtrue\Pinyin\Pinyin;
use Illuminate\Database\Eloquent\Model;

/**
 * 维度模型
 * Class Dimension
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年8月5日
 */
class Dimension extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'limits',
        'sort'
    ];

    /**
     * 模型事件
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->slug = null;
        });
    }

    /**
     * 关联模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_dimension', 'dimension_id', 'module_id');
    }

    /**
     * 拥有的标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * 固定链接
     *
     * @param mixed $value
     */
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $value = implode('-', (new Pinyin())->name($this->name, PINYIN_UMLAUT_V));
        }
        
        $this->attributes['slug'] = $value;
    }
}
