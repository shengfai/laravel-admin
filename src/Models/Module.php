<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Support\Str;
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
     * 获取模型
     *
     * @param string $class_name
     * @return \Shengfai\LaravelAdmin\Models\Module
     */
    public static function getModelByClassName(string $class_name)
    {
        $class = 'App\Models\\' . Str::of($class_name)->singular()->ucfirst();
        return self::where('namespace', $class)->first();
    }

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
