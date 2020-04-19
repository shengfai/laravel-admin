<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Contracts;

/**
 * 推荐位约定
 * Interface PositionContract.
 */
interface PositionContract
{
    /**
     * 设置推送至推荐位统一格式.
     *
     * @return array
     */
    public function getPositionedData(): array;
}
