<?php

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
