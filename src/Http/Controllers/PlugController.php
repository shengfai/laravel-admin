<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\File;
use Shengfai\LaravelAdmin\Handlers\FileHandler;
use Overtrue\LaravelUploader\StrategyResolver;

/**
 * 后台插件控制器
 * Class PlugController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class PlugController extends Controller
{

    /**
     * 选择图标
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function icon()
    {
        $field = request()->get('field', 'icon');
        
        return $this->view(compact('field'));
    }

    /**
     * 文件上传
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function upfile(Request $request)
    {
        // 上传模式
        $mode = $request->mode;
        
        // 文件类型
        $types = $request->type;
        
        // 扩展格式
        $mimes = app(FileHandler::class)->getFileMine($types);
        
        $field = $request->field;
        
        return $this->view(compact('mode', 'types', 'mimes', 'field'));
    }

    /**
     * 文件状态检查
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function upstate(Request $request)
    {
        // 检查文件是否上传
        $file_hash = $request->get('md5');
        
        // 校验文件是否存在
        if ($data = $this->validateFileExists($file_hash))
            return $data;
        
        return $this->write([
            'code' => 'NOT_FOUND',
            'data' => [
                'server' => url('admin/plugs/upload'),
                'token' => md5($request->md5 . session_id())
            ]
        ]);
    }

    /**
     * 通用文件上传
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // 检查文件是否上传
        $file_hash = md5_file($request->file('file')->path());
        
        // 校验文件是否存在
        if ($data = $this->validateFileExists($file_hash))
            return $data;
        
        $response = StrategyResolver::resolveFromRequest($request, $request->get('strategy', 'default'))->upload();
        
        // 创建文件
        File::create([
            'type_id' => $request->get('type_id', 0),
            'hash' => md5_file($response->strategy->getFile()->getRealPath()),
            'mime' => $response->mime,
            'size' => $response->size,
            'relative_url' => $response->relativeUrl,
            'original_name' => $response->originalName,
            'url' => $response->url
        ]);
        
        return response()->json([
            'data' => [
                'url' => $response->url,
                'mime' => $response->mime,
                'size' => $response->size,
                'filename' => $response->filename,
                'original_name' => $response->originalName,
                'relative_url' => $response->relativeUrl
            ],
            'code' => 'SUCCESS',
            'msg' => '文件上传成功'
        ]);
    }

    /**
     * 位置选择
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function poipicker()
    {
        return $this->view();
    }

    /**
     * 校验文件是否存在
     *
     * @param string $file_hash
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function validateFileExists(string $file_hash)
    {
        // 文件存在
        if (File::ofHash($file_hash)->exists()) {
            $file = File::ofHash($file_hash)->first();
            return $this->write([
                'code' => 'SUCCESS',
                'data' => [
                    'url' => $file->url,
                    'mime' => $file->mime,
                    'size' => $file->size,
                    'filename' => \class_basename($file->relative_url),
                    'original_name' => $file->original_name,
                    'relative_url' => $file->relative_url
                ]
            ]);
        }
    }
}
