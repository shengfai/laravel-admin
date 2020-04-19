<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Traits;

/**
 * HTTP 响应
 * trait Response.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月8日
 */
trait Response
{
    /**
     * 默认返回资源类型.
     *
     * @var string
     */
    protected $restDefaultType = 'json';

    /**
     * 设置响应类型.
     *
     * @return \App\Http\Traits\Response
     */
    public function setType(string $type = '')
    {
        $this->restType = (!empty($type)) ? $type : $this->restDefaultType;

        return $this;
    }

    /**
     * 失败响应.
     *
     * @param int    $code
     * @param int    $error_code
     * @param string $message
     * @param string $debug
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function error(array $data, array $headers = [])
    {
        $responseData = [
            'error_code' => $data['error_code'],
            'message' => $data['message'],
        ];

        // Debug信息
        if (('production' !== config('app.env')) && isset($data['debug'])) {
            $responseData['debug'] = $data['debug'];
        }

        return $this->write($responseData, $data['code'], $headers);
    }

    /**
     * 成功响应.
     *
     * @param number $code
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function success(array $responseData = [], int $code = 200, array $headers = [])
    {
        return $this->write($responseData, $code, $headers);
    }

    /**
     * 重定向.
     *
     * @param number $code
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(string $url, array $params = [], int $code = 302, array $with = [])
    {
        return redirect()->route($url, $params, $code)->with($with);
    }

    /**
     * 响应输出.
     *
     * @param mixed $responseData
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function write($responseData, int $code = 200, array $headers = [])
    {
        // 以 JSONP 响应返回
        if (\request()->has('callback')) {
            return response()->json($responseData)->setCallback(\request()->input('callback'));
        }

        // 获取响应格式
        if (!isset($this->restType) || empty($this->restType)) {
            $this->setType();
        }

        return response($responseData, $code, $headers)->header('Content-Type', $this->restType);
    }
}
