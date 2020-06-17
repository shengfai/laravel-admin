<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Traits;

use Illuminate\Database\Eloquent\Builder;
use Shengfai\LaravelAdmin\Contracts\Conventions;

/**
 * 本地查询作用域
 * trait Scope.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
trait Scope
{
    /**
     * 查询指定状态记录.
     *
     * @param mixed $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsable(Builder $query)
    {
        return $query->where('status', Conventions::STATUS_USABLE);
    }
    
    /**
     * 查询指定状态记录.
     *
     * @param mixed $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus(Builder $query, $status = null)
    {
        // 单个值
        if (is_numeric($status)) {
            return $query->where('status', $status);
        }

        // 数组条件
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }

        return $query;
    }

    /**
     * 查询指定类型记录.
     *
     * @param int $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, int $type = null)
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }

    /**
     * 查询指定排序记录.
     *
     * @param int $order
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSort(Builder $query, int $order = null)
    {
        if (is_null($order)) {
            return $query;
        }

        return $query->where('sort', $order);
    }

    /**
     * 查询最近的记录.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent(Builder $query)
    {
        return $query->orderBy('id', 'DESC');
    }

    /**
     * 查询排序记录.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSorted(Builder $query, string $sort = 'DESC')
    {
        return $query->orderBy('sort', $sort);
    }
}
