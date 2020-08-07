<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 模型
 * Class Module
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年8月6日
 */
class Module extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'namespace',
        'sort'
    ];
    
    /**
     * 关联维度
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dimensions()
    {
        return $this->belongsToMany(Dimension::class, 'module_dimension', 'module_id');
    }
}
