<?php

/*
 * This file is part of the shengfai/laravel-admin.
 *
 * (c) shengfai <shengfai@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Shengfai\LaravelAdmin\Handlers;

/**
 * 数据处理器
 * Class DataHandler.
 *
 * @author ShengFai <shengfai@qq.com>
 *
 * @version 2020年3月10日
 */
class DataHandler
{
    /**
     * 获取百分比.
     *
     * @return float
     */
    public static function getPercentageValue(int $value, int $total, int $precision = 4)
    {
        // 总数为空
        if ($total <= 0) {
            return sprintf('%01.2f', 0);
        }

        return sprintf('%01.2f', (round($value / $total, $precision) * 100));
    }

    /**
     * 一维数据数组生成数据树.
     *
     * @param array  $array 数据列表
     * @param string $id    父ID Key
     * @param string $pid   ID Key
     * @param string $son   定义子数据Key
     *
     * @return array
     */
    public static function arr2tree(array $array, string $id = 'id', string $pid = 'parent_id', string $son = 'sub'): array
    {
        list($tree, $map) = [
            [],
            [],
        ];
        foreach ($array as $item) {
            $map[$item[$id]] = $item;
        }
        foreach ($array as $item) {
            if (isset($item[$pid]) && isset($map[$item[$pid]])) {
                $map[$item[$pid]][$son][] = &$map[$item[$id]];
            } else {
                $tree[] = &$map[$item[$id]];
            }
        }
        unset($map);

        return $tree;
    }

    /**
     * 一维数据数组生成数据树.
     *
     * @param array  $array 数据列表
     * @param string $id    ID Key
     * @param string $pid   父ID Key
     *
     * @return array
     */
    public static function arr2table(array $array, string $id = 'id', string $pid = 'parent_id', string $path = 'path', string $ppath = ''): array
    {
        $tree = [];
        foreach (self::arr2tree($array, $id, $pid) as $attr) {
            $attr[$path] = "{$ppath}-{$attr[$id]}";
            $attr['sub'] = isset($attr['sub']) ? $attr['sub'] : [];
            $attr['spt'] = substr_count($ppath, '-');
            $attr['spl'] = str_repeat('&nbsp;&nbsp;&nbsp;├&nbsp;&nbsp;', $attr['spt']);
            $sub = $attr['sub'];
            unset($attr['sub']);
            $tree[] = $attr;
            if (!empty($sub)) {
                $tree = array_merge($tree, self::arr2table($sub, $id, $pid, $path, $attr[$path]));
            }
        }

        return $tree;
    }

    /**
     * 获取数据树子ID.
     *
     * @param array  $array 数据列表
     * @param int    $id    起始ID
     * @param string $key   子Key
     * @param string $pkey  父Key
     *
     * @return array
     */
    public static function getChildrenIds(string $array, int $id = 0, string $key = 'id', string $pkey = 'parent_id'): array
    {
        $ids = [
            $id,
        ];
        foreach ($array as $vo) {
            if (intval($vo[$pkey]) > 0 && intval($vo[$pkey]) === $id) {
                $ids = array_merge($ids, self::getChildrenIds($array, intval($vo[$key]), $key, $pkey));
            }
        }

        return $ids;
    }

    /**
     * 获取数据树子ID.
     *
     * @param array  $array 数据列表
     * @param int    $id    起始ID
     * @param string $key   子Key
     * @param string $pkey  父Key
     *
     * @return array
     */
    public static function getArrSubIds(array $array, int $id = 0, string $key = 'id', string $pkey = 'parent_id'): array
    {
        $ids = [
            $id,
        ];
        foreach ($array as $vo) {
            if (intval($vo[$pkey]) > 0 && intval($vo[$pkey]) === $id) {
                $ids = array_merge($ids, self::getArrSubIds($array, intval($vo[$key]), $key, $pkey));
            }
        }

        return $ids;
    }
}
