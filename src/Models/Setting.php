<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 系统配置
 * Class Setting
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月9日
 */
class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * Value设置器
     *
     * @param string $value
     */
    public function setValueAttribute(string $value = null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        $this->attributes['value'] = $value;
    }
}
