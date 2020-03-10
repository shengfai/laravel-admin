<?php

namespace Shengfai\LaravelAdmin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Shengfai\LaravelAdmin\Traits\Scope;

/**
 * 分类模型
 *
 * Class Type
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
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
        'sort'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer'
    ];

    /**
     * 父类型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Type::class, 'parent_id');
    }

    /**
     * 子类型
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Type::class, 'parent_id');
    }

    /**
     * 关联所属用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
