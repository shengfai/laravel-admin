<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Overtrue\LaravelUploader\StrategyResolver;
use Shengfai\LaravelAdmin\Handlers\FileHandler;
use Shengfai\LaravelAdmin\Models\File;

/**
 * 后台插件控制器
 * Class PlugsController.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class PlugsController extends Controller
{
    /**
     * 选择图标.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function icon()
    {
        $field = request()->get('field', 'icon');

        return $this->view(compact('field'));
    }

    /**
     * 文件上传.
     *
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
     * 文件状态检查.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function upstate(Request $request)
    {
        // 检查文件是否上传
        $file_hash = $request->get('md5');
        if (File::ofHash($file_hash)->exists()) {
            $file = File::ofHash($file_hash)->first();

            return $this->result([
                'url' => $file->url,
            ], 'IS_FOUND');
        }

        return $this->result([
            'server' => url('admin/plugs/upload'),
            'token' => md5($request->md5.session_id()),
        ], 'NOT_FOUND');
    }

    /**
     * 通用文件上传.
     *
     * @return string
     */
    public function upload(Request $request)
    {
        $response = StrategyResolver::resolveFromRequest($request, $request->get('strategy', 'default'))->upload();

        File::create([
            'type_id' => $request->get('type_id', 0),
            'hash' => $request->get('md5'),
            'mime' => $response->mime,
            'size' => $response->size,
            'relative_url' => $response->relativeUrl,
            'original_name' => $response->originalName,
            'url' => $response->url,
        ]);

        return response()->json([
            'data' => [
                'url' => $response->url,
                'mime' => $response->mime,
                'size' => $response->size,
                'filename' => $response->filename,
                'relative_url' => $response->relativeUrl,
            ],
            'code' => 'SUCCESS',
            'msg' => '文件上传成功',
        ]);
    }

    /**
     * 位置选择.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function poipicker()
    {
        return $this->view();
    }
}
