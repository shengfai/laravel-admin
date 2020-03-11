<?php

namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shengfai\LaravelAdmin\Traits\Scope;
use Spatie\Activitylog\Traits\LogsActivity;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Traits\CustomActivityProperties;

/**
 * 系统菜单模型
 * Class Menu
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class Menu extends Model
{
    use SoftDeletes, Scope, LogsActivity, CustomActivityProperties;
    
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'permission_id',
        'icon',
        'url',
        'params',
        'target',
        'sort',
        'status'
    ];
    
    /**
     * 追加属性
     *
     * @var array
     */
    protected $appends = [
        'full_url'
    ];
    
    /**
     * The attributes that need to be logged
     *
     * @var array
     */
    protected static $logAttributes = [
        'parent_id',
        'name',
        'code',
        'permission_id',
        'url',
        'params',
        'status'
    ];
    
    /**
     * customizing the log name
     *
     * @var string
     */
    protected static $logName = Conventions::LOG_TYPE_CONSOLE;

    /**
     * 是否拥有父菜单
     *
     * @return boolean
     */
    public function hasParent()
    {
        return $this->parent_id != 0;
    }

    /**
     * customizing the description
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return '创建菜单';
        } elseif ($eventName == 'deleted') {
            return '删除菜单';
        } else {
            return '更新菜单';
        }
    }

    /**
     * 关联权限
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * 关联父节点
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * 路径获取器
     *
     * @param string $value
     */
    public function getUrlAttribute($value)
    {
        if (Str::contains($value, '/')) {
            $value = config('administrator.prefix') . $value;
        }
        return $value;
    }

    /**
     * 路径设置器
     *
     * @param string $value
     */
    public function setUrlAttribute($value)
    {
        if (Str::startsWith($value, config('administrator.prefix'))) {
            return $this->attributes['url'] = str_replace(config('administrator.prefix'), '', $value);
        }
        $this->attributes['url'] = $value;
    }

    /**
     * 获取菜单链接
     *
     * @return string
     */
    public function getFullUrlAttribute()
    {
        $value = '';
        if (!empty($this->attributes['url']) && $this->attributes['url'] !== '#') {
            $value = $this->attributes['url'] .
                     (empty($this->attributes['params']) ? '' : "?{$this->attributes['params']}");
        }
        
        return $value;
    }

    /**
     * 标识设置器
     *
     * @param string $value
     */
    public function setCodeAttribute(string $value = null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        $this->attributes['code'] = $value;
    }
}
