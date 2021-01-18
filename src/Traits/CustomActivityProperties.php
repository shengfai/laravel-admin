<?php
namespace Shengfai\LaravelAdmin\Traits;

use Spatie\Activitylog\Contracts\Activity;

/**
 * 自定义日志属性
 * trait CustomActivityProperties
 *
 * @package \Shengfai\LaravelAdmin\Traits
 * @author ShengFai <shengfai@qq.com>
 */
trait CustomActivityProperties
{

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->ip = request()->ip();
        $activity->user_agent = request()->userAgent();
        $activity->method = request()->method();
        $activity->host = request()->getHost();
        $activity->node = request()->path();
    }
}
