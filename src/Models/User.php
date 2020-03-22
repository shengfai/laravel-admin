<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Traits\CustomActivityProperties;

/**
 * 用户模型
 * Class User
 *
 * @package \App\Models
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasRoles;
    use LogsActivity, CustomActivityProperties;
    
    /**
     * 用户类型
     */
    const TYPE_USER             = 1;        // 用户
    const TYPE_ORGANIZER        = 2;        // 组织
    const TYPE_ADMINISTRATOR    = 11;       // 后台用户
    
    /**
     * The attributes that are mass assignable.
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
        'status',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    /**
     * The attributes that should be cast to native types.
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
     * customizing the description
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return '创建用户';
        } elseif ($eventName == 'deleted') {
            return '删除用户';
        } else {
            return '更新用户';
        }
    }
    
    /**
     * 设置密码
     *
     * @param string $value
     */
    protected function setPasswordAttribute(string $value = null)
    {
        if (is_null($value)) return;
        
        $this->attributes['password'] = bcrypt($value);
    }
}
