<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Traits\CustomActivityProperties;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * 用户模型
 * Class User.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use LogsActivity;
    use CustomActivityProperties;

    /**
     * 用户类型.
     */
    const TYPE_USER = 1;        // 用户
    const TYPE_ORGANIZER = 2;        // 组织
    const TYPE_ADMINISTRATOR = 11;       // 后台用户

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
        'remember_token',
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
        'notification_count' => 'integer',
    ];

    /**
     * all $fillable attributes changes will be logged.
     *
     * @var string
     */
    protected static $logFillable = true;

    /**
     * customizing the log name.
     *
     * @var string
     */
    protected static $logName = Conventions::LOG_TYPE_CONSOLE;

    /**
     * customizing the description.
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
