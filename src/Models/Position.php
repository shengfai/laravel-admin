<?php
namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shengfai\LaravelAdmin\Models\Traits\Attributes;
use Shengfai\LaravelAdmin\Traits\Scope;

/**
 * 推荐位模型
 * Class Position
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 */
class Position extends Model
{
    use Scope;
    use Attributes;

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
        return $this->hasMany(Positionable::class)
            ->sorted()
            ->latest('updated_at');
    }
}
