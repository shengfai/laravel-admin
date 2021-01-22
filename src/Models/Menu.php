<?php
namespace Shengfai\LaravelAdmin\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Traits\CustomActivityProperties;
use Shengfai\LaravelAdmin\Traits\Scope;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;

/**
 * 系统菜单模型
 * Class Menu
 *
 * @package \Shengfai\LaravelAdmin\Models
 * @author ShengFai <shengfai@qq.com>
 */
class Menu extends Model
{
    use SoftDeletes;
    use Scope;
    use LogsActivity;
    use CustomActivityProperties;

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
     * 追加属性.
     *
     * @var array
     */
    protected $appends = [
        'full_url'
    ];

    /**
     * The attributes that need to be logged.
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
     * customizing the log name.
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
        return 0 != $this->parent_id;
    }

    /**
     * customizing the description
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ('created' == $eventName) {
            return '创建菜单';
        } elseif ('deleted' == $eventName) {
            return '删除菜单';
        }
        
        return '更新菜单';
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
        if (Str::contains($value, 'http')) {
            return $value;
        }
        
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
        if (! empty($this->attributes['url']) && '#' !== $this->attributes['url']) {
            $value = $this->attributes['url'] . (empty($this->attributes['params']) ? '' : "?{$this->attributes['params']}");
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
