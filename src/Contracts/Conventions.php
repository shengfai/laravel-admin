<?php

namespace Shengfai\LaravelAdmin\Contracts;

/**
 * 约定
 * Interface Conventions.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
interface Conventions
{
    const VERSION = '1.1.8';                            // 版本

    /**
     * 注册通道
     */
    const REGISTERED_CHANNEL_WXAMP = 1;                 // 小程序
    const REGISTERED_CHANNEL_CONSOLE = 10;              // 控制台
    
    /**
     * 通用状态
     */
    const STATUS_UNUSABLE       = 0;                    // 状态不可用
    const STATUS_USABLE         = 1;                    // 状态可用/进行中
    const STATUS_PENDING        = 2;                    // 状态不可用/待审核
    const STATUS_FORBIDDEN      = 3;                    // 状态不可用/已拒绝
    const STATUS_PREPARING      = 4;                    // 状态可用/准备中
    const STATUS_DONE           = 5;                    // 状态不可用/已结束
    const STATUS_INSUFFICIENT   = 6;                    // 状态不可用/数量不足
    const STATUS_EXPIRED        = 7;                    // 状态不可以/已过期

    const STATUS_SUCCESSFUL     = 'successful';         // 成功
    const STATUS_FAILED         = 'failed';             // 失败

    /**
     * 模板提示信息.
     */
    const VIEW_ALERT_INFO       = 'info';
    const VIEW_ALERT_DANGER     = 'danger';
    const VIEW_ALERT_WARNING    = 'warning';
    const VIEW_ALERT_SUCCESS    = 'success';

    /**
     * 日志类型.
     */
    const LOG_TYPE_RUN          = 'run';                //系统日志
    const LOG_TYPE_CONSOLE      = 'console';            //操作日志
    const LOG_TYPE_BEHAVIOR     = 'behavior';           //行为日志

    /**
     * 支付方式.
     */
    const PAY_TYPE_CREDIT               = 1;            // 积分换购
    const PAY_TYPE_BALANCE              = 2;            // 钱包支付
    const PAY_TYPE_TRANSFER             = 9;            // 线下转账
    const PAY_TYPE_ALIPAY_WEB           = 10;           // 支付宝电脑支付
    const PAY_TYPE_ALIPAY_WAP           = 11;           // 支付宝手机网站支付
    const PAY_TYPE_ALIPAY_APP           = 12;           // 支付宝 APP 支付
    const PAY_TYPE_ALIPAY_POS           = 13;           // 支付宝刷卡支付
    const PAY_TYPE_ALIPAY_SCAN          = 14;           // 支付宝扫码支付
    const PAY_TYPE_ALIPAY_TRANSFER      = 15;           // 支付宝账户转账
    
    const PAY_TYPE_WXPAY_MP             = 20;           // 微信公众号支付
    const PAY_TYPE_WXPAY_MINIAPP        = 21;           // 微信小程序支付
    const PAY_TYPE_WXPAY_WAP            = 22;           // 微信H5支付
    const PAY_TYPE_WXPAY_SCAN           = 23;           // 微信扫码支付
    const PAY_TYPE_WXPAY_POS            = 24;           // 微信刷卡支付
    const PAY_TYPE_WXPAY_APP            = 25;           // 微信APP支付
    const PAY_TYPE_WXPAY_TRANSFER       = 26;           // 微信企业付款
    const PAY_TYPE_WXPAY_REDPACK        = 27;           // 微信支付普通红包
    const PAY_TYPE_WXPAY_GROUPREDPACK   = 28;           // 微信支付分裂红包

    /**
     * 订单状态
     * 参考：http://help.jd.com/user/issue/56-94.html.
     */
    const TRADE_STATUS_PROCESSING       = 1;            // 订单状态：等待处理
    const TRADE_STATUS_PAYING           = 2;            // 订单状态：等待付款
    const TRADE_STATUS_CONFIRM_PAY      = 3;            // 订单状态：等待确认付款
    const TRADE_STATUS_PAID             = 4;            // 订单状态：已付款
    const TRADE_STATUS_SEND             = 5;            // 订单状态：已发货
    const TRADE_STATUS_FINISH           = 6;            // 订单状态：已完成
    const TRADE_STATUS_CANCEL           = 7;            // 订单状态：已取消
    const TRADE_STATUS_CLODED           = 8;            // 订单状态：已关闭
    const TRADE_STATUS_LOCKING          = 9;            // 订单状态：锁定
    const TRADE_STATUS_UNDERSTOCK       = 10;           // 订单状态：订单中有商品无法到货(发布者)
    const TRADE_STATUS_BOOKING          = 11;           // 订单状态：商品预订
    const TRADE_STATUS_DISPATCHING      = 12;           // 订单状态：正在配送
    const TRADE_STATUS_PICKUP           = 13;           // 订单状态：上门自提
    const TRADE_STATUS_REFUND           = 20;           // 订单状态：申请退款
    const TRADE_STATUS_REFUNDING        = 21;           // 订单状态：退款中
    const TRADE_STATUS_REFUNDED         = 22;           // 订单状态：已退款
    const TRADE_STATUS_REFUND_REJECTED  = 23;           // 订单状态：退款已拒绝
    const TRADE_STATUS_REFUND_FAILED    = 24;           // 订单状态：退款失败
}
