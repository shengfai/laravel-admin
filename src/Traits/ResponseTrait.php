<?php
namespace Shengfai\LaravelAdmin\Traits;

use Exception;
use Illuminate\Http\Response;
use Shengfai\LaravelAdmin\Contracts\ErrorCodes;

/**
 * HTTP 响应特性
 * trait ResponseTrait
 *
 * @package \Shengfai\LaravelAdmin\Traits
 * @author ShengFai <shengfai@qq.com>
 */
trait ResponseTrait
{

    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param string $location
     * @param mixed $content
     * @return \Illuminate\Http\Response
     */
    public function created(string $location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(ErrorCodes::HTTP_CREATED)->header('Content-Type', 'application/json');
        
        if (! is_null($location)) {
            $response->header('Location', $location);
        }
        
        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function noContent(array $headers = [])
    {
        return response()->noContent(ErrorCodes::HTTP_NO_CONTENT, $headers)->header('Content-Type', 'application/json');
    }

    /**
     * 重定向
     *
     * @param string $url
     * @param array $params
     * @param int $code
     * @param array $with
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(string $url, array $params = [], int $code = 302, array $with = [])
    {
        return redirect()->route($url, $params, $code)->with($with);
    }

    /**
     * 响应
     *
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function response(array $data = [], int $code = 200, array $headers = [])
    {
        // 返回数据
        if (empty($data)) {
            return response()->noContent($code, $headers);
        }
        
        return response(['data' => $data], $code, $headers);
    }

    /**
     * Return an error response
     *
     * @param int $statusCode
     * @param Exception $exception
     * @param string $message
     * @param int $error_code
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function failed(int $statusCode, Exception $exception, string $message = null, int $error_code = null)
    {
        if (app()->bound('sentry') && $exception) {
            app('sentry')->captureException($exception);
        }
    
        return response([
            'error_code' => $error_code ?? $exception->getCode(),
            'message' => $message ?? $exception->getMessage()
        ], $statusCode);
    }
    
    /**
     * 响应输出
     *
     * @param mixed $responseData
     * @param int $code
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function write($responseData, int $code = 200, array $headers = [])
    {
        // Ajax
        if (request()->expectsJson() || request()->wantsJson()) {
            return response($responseData, $code, $headers)->header('Content-Type', 'application/json');
        }
        
        // JSONP
        if (request()->has('callback')) {
            return response()->json($responseData)->setCallback(request()->input('callback'));
        }
    }
}
