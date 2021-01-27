<?php

namespace App\Models;

use Shengfai\LaravelAdmin\Contracts\Conventions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Shengfai\LaravelAdmin\Traits\CustomActivityProperties;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Builder;

/**
 * 用户模型
 * Class User
 *
 * @package \App\Models
 * @author ShengFai <shengfai@qq.com>
 */
class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes, LogsActivity, CustomActivityProperties;
    use Notifiable, HasRoles;

    /**
     * 用户类型
     */
    const TYPE_USER          = 1;   // 用户
    const TYPE_ORGANIZER     = 2;   // 组织
    const TYPE_ADMINISTRATOR = 11;  // 后台用户

    // 用户类型
    public static $typeMap = [
        self::TYPE_USER => '普通用户',
        self::TYPE_ORGANIZER => '机构用户',
        self::TYPE_ADMINISTRATOR => '管理用户',
    ];
    
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'notification_count',
        'signature',
        'gender',
        'birthdate',
        'country',
        'province',
        'city',
        'district',
        'registered_channel',
        'spread_userid',
        'remark',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'gender' => 'integer',
        'status' => 'integer',
        'email_verified_at' => 'datetime',
        'notification_count' => 'integer'
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
     * 获取用户类型
     *
     * @return string
     */
    public function getTypeName()
    {
        return self::$typeMap[$this->type];
    }
    
    /**
     * customizing the description
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ('created' == $eventName) {
            return '创建用户';
        } elseif ('deleted' == $eventName) {
            return '删除用户';
        }

        return '更新用户';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 查询指定类型记录
     *
     * @param Builder $query
     * @param int $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInNormalType(Builder $query, int $type = null)
    {
        if (is_null($type)) {
            return $query->where('type', '<>', self::TYPE_ADMINISTRATOR);
        }
    
        return $query->where('type', $type);
    }
    
    /**
     * 设置密码
     *
     * @param string $value
     */
    protected function setPasswordAttribute(string $value = null)
    {
        if (is_null($value)) {
            return;
        }
        $this->attributes['password'] = bcrypt($value);
    }
}
