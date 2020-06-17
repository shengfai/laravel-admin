<?php

namespace Shengfai\LaravelAdmin\Contracts;

/**
 * Interface ErrorCodes
 *
 * @author  ShengFai <shengfai@qq.com>
 * @version 2020年6月16日
 */
interface ErrorCodes
{
    
    /*
     * HTTP状态码
     */
    const HTTP_OK = 200;                            // 对成功的 GET、PUT、PATCH 或 DELETE 操作进行响应。也可以被用在不创建新资源的 POST 操作上
    const HTTP_CREATED = 201;                       // 对创建新资源的 POST 操作进行响应。应该带着指向新资源地址的 Location 头
    const HTTP_NO_CONTENT = 204;                    // 对不会返回响应体的成功请求进行响应（比如 DELETE 请求）
    const HTTP_BAD_REQUEST = 400;                   // 请求异常，比如请求中的body无法解析
    const HTTP_UNAUTHORIZED = 401;                  // 没有进行认证或者认证非法
    const HTTP_FORBIDDEN = 403;                     // 该状态码可以简单的理解为没有权限访问该请求，服务器收到请求但拒绝提供服务。
    const HTTP_NOT_FOUND = 404;                     // 请求一个不存在的资源
    const HTTP_METHOD_NOT_ALLOWED = 405;            // 请求的 HTTP 方法不允许当前认证用户访问
    const HTTP_GONE = 410;                          // 当前请求的资源不再可用
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;        // 请求中的内容类型是错误的
    const HTTP_UNPROCESSABLE_ENTITY = 422;          // 校验错误
    const HTTP_TOO_MANY_REQUESTS = 429;             // 请求频次达到上限
    const HTTP_SYSTEM_ERROR = 500;                  // 系统异常/未知错误
    const HTTP_SERVICE_UNAVAILABLE = 503;           // 该状态码表示服务器暂时处理不可用状态

    /*
     * 权限认证错误(401)
     */
    const TOKEN_UNAUTHORIZED = 40100;               // Token过期或失效

    /*
     * 无权限(403)
     */
    const NO_PERMISSION = 40300;                    // 当前操作没有权限
    
    /*
     * 未找到(404)
     */
    const RESOURCE_NOT_FOUND = 40401;               // 资源未找到
    
    /*
     * 资源不可用
     */
    const RESOURCE_UNAVAILABLE = 41000;             // 资源不可用
    const RESOURCE_EXPIRED = 41001;                 // 资源已过期
    const RESOURCE_UNACTIVATED = 41002;             // 资源未激活/未开始
    
    /*
     *  验证错误(422)
     */
    const BUSINESS_PARAM_ERROR = 42200;             // 业务参数校验错误
    
    const INVALID_PHONE = 42210;                    // 无效手机号
    const INVALID_VERIFY_CODE = 42211;              // 无效的手机验证码

    /*
     * 系统错误(503)
     */
    const SERVICE_UNAVAILABLE = 50300;              // 服务不可用
}
