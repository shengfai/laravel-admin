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
use Shengfai\LaravelAdmin\Exceptions\InvalidArgumentException;

/**
 * 系统配置控制器
 * Class SettingsController.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $this->title = '站点配置';

        $settings = \Option::all();

        return $this->view(compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        try {
            foreach ($request->post() as $name => $value) {
                if ('_token' !== $name) {
                    settings($name, $value);
                }
            }

            return $this->success('数据更新成功!', '');
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }
}
