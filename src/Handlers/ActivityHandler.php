<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Handlers;

use Shengfai\LaravelAdmin\Contracts\Conventions;
use Spatie\Activitylog\Contracts\Activity;

/**
 * 行为处理器
 * Class ActivityHandler.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月12日
 */
class ActivityHandler
{
    /**
     * 记录用户操作日志.
     *
     * @param bool $withDefaultProperties
     *
     * @return \Spatie\Activitylog\ActivityLogger
     */
    public static function console(bool $withDefaultCaused = true)
    {
        // 行为日志
        $activity = activity(Conventions::LOG_TYPE_CONSOLE)->tap(function (Activity $activity) {
            $activity->ip = request()->ip();
            $activity->user_agent = request()->userAgent();
            $activity->method = request()->method();
            $activity->host = request()->getHost();
            $activity->node = request()->path();
        });

        // 执行用户
        if (!$withDefaultCaused) {
            return $activity->byAnonymous();
        }

        $causedBy = request()->user();

        return $activity->by($causedBy);
    }
}
