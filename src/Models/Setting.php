<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Traits\CustomActivityProperties;

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
    use LogsActivity, CustomActivityProperties;
    
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
     * all $fillable attributes changes will be logged
     *
     * @var string
     */
    protected static $logFillable = true;
    
    /**
     * customizing the log name
     *
     * @var string
     */
    protected static $logName = Conventions::LOG_TYPE_CONSOLE;

    /**
     * customizing the description
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return '创建配置';
        } elseif ($eventName == 'deleted') {
            return '删除配置';
        } else {
            return '更新配置';
        }
    }

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
