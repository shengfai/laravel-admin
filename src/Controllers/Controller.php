<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Support\Str;

/**
 * 控制台基类
 * Class Controller.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月8日
 */
class Controller extends AdminController
{
    /**
     * 获取当前控制器名.
     *
     * @return string
     */
    public function getCurrentControllerName($baseName = false)
    {
        $controller = $this->getCurrentAction()['controller'];

        return $baseName ? strtolower(Str::before(class_basename($controller), 'Controller')) : $controller;
    }

    /**
     * 获取当前方法名.
     *
     * @return string
     */
    public function getCurrentMethodName()
    {
        return $this->getCurrentAction()['method'];
    }

    /**
     * 获取当前控制器与方法.
     *
     * @return array
     */
    public function getCurrentAction()
    {
        $action = \Route::current()->getActionName();
        list($class, $method) = explode('@', $action);

        return [
            'controller' => $class,
            'method' => $method,
        ];
    }
}
