<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Handlers\FileHandler;

/**
 * 后台插件控制器
 * Class PlugsController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class PlugsController extends Controller
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
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function upstate(Request $request)
    {
        // 存储路径
        $fileStoragePath = file_storage_path($request->md5, pathinfo($request->filename, PATHINFO_EXTENSION));
        
        // 检查文件是否上传
        if (($fileUrl = app(FileHandler::class)->getFileUrl($fileStoragePath))) {
            return $this->result([
                'url' => $fileUrl
            ], 'IS_FOUND');
        }
        
        // 需要上传文件，生成上传配置参数
        $config = [
            'file_path' => $fileStoragePath,
            'server' => url('admin/plugs/upload'),
            'token' => md5($fileStoragePath . session_id())
        ];
        return $this->result($config, 'NOT_FOUND');
    }

    /**
     * 通用文件上传
     *
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {
        // 校验扩展名
        $ext = $request->file->getClientOriginalExtension();
        if (!in_array($ext, app(FileHandler::class)->getAllowedExts())) {
            return new_json_encode([
                'code' => 'ERROR',
                'msg' => trans('administrator.invalid_file_exts')
            ]);
        }
        
        // 存储路径
        $fileStoragePath = file_storage_path($request->md5, $ext);
        
        // 文件上传Token验证
        if ($request->token !== md5($fileStoragePath . session_id())) {
            return new_json_encode([
                'code' => 'ERROR',
                'msg' => '文件上传验证失败'
            ]);
        }
        
        // 将图片移动到我们的目标存储路径中
        if ($request->file->storeAs(dirname($fileStoragePath), pathinfo($fileStoragePath, PATHINFO_BASENAME))) {
            if (($url = app(FileHandler::class)->getFileUrl($fileStoragePath))) {
                return new_json_encode([
                    'data' => [
                        'url' => $url
                    ],
                    'code' => 'SUCCESS',
                    'msg' => '文件上传成功'
                ]);
            }
        }
        return new_json_encode([
            'code' => 'ERROR',
            'msg' => '文件上传失败'
        ]);
    }

    /**
     * 位置选择
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function poipicker()
    {
        return $this->view();
    }
}
