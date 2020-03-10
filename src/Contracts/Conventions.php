<?php

namespace Shengfai\LaravelAdmin\Contracts;

/**
 * 约定
 * Class Conventions
 *
 * @package \Shengfai\LaravelAdmin\Contracts
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
interface Conventions
{
    const VERSION = '0.1.0';                    // 版本

    /**
     * 通用状态
     */
    const STATUS_UNUSABLE = 0;                  // 状态不可用
    const STATUS_USABLE = 1;                    // 状态可用/进行中
    const STATUS_PENDING = 2;                   // 状态不可用/待审核
    const STATUS_FORBIDDEN = 3;                 // 状态不可用/已拒绝
    const STATUS_PREPARING = 4;                 // 状态可用/准备中
    const STATUS_DONE = 5;                      // 状态不可用/已结束
    const STATUS_INSUFFICIENT = 6;              // 状态不可用/数量不足
    const STATUS_EXPIRED = 7;                   // 状态不可以/已过期

    const STATUS_ON = 'on';                     // 状态可用/审核通过
    const STATUS_OFF = 'off';                   // 状态不可用
    const STATUS_SUCCESSFUL = 'successful';     // 成功
    const STATUS_FAILED = 'failed';             // 失败

    /**
     * 模板提示信息
     */
    const VIEW_ALERT_INFO = 'info';
    const VIEW_ALERT_DANGER = 'danger';
    const VIEW_ALERT_WARNING = 'warning';
    const VIEW_ALERT_SUCCESS = 'success';
}