<?php
namespace Shengfai\LaravelAdmin\Traits;

/**
 * HTTP 响应
 * trait Response
 *
 * @package \Shengfai\LaravelAdmin\Traits
 * @author ShengFai <shengfai@qq.com>
 */
trait Response
{

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
        if (\request()->expectsJson() || \request()->wantsJson()) {
            return response($responseData, $code, $headers)->header('Content-Type', 'application/json');
        }
    }
}
