<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Shengfai\LaravelAdmin\Traits\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 推荐位模型
 * Class Position
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class Position extends Model
{
    use Scope;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cover_pic',
        'description'
    ];

    /**
     * 关联推荐对象
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function datas(): HasMany
    {
        return $this->hasMany(Positionable::class)->sorted()->recent();
    }
}
