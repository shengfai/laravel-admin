<?php

namespace Shengfai\LaravelAdmin\Models;

use Spatie\Tags\Tag as OriginalTag;

/**
 * 标签模型
 * Class Tag.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年7月31日
 */
class Tag extends OriginalTag
{
    /**
     * the column of order
     *
     * @var string $order_column
     */
    protected $sortable = [
        'order_column_name' => 'order_column'
    ];

    public function getOrderColumnName()
    {
        return $this->sortable['order_column_name'] ?? 'sort';
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taggables()
    {
        return $this->hasMany(Taggable::class);
    }

    /**
     *
     * @param string $name
     * @param string $type
     * @param string $locale
     */
    public static function findFromString(string $name, string $type = null, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        return static::query()->where("name->{$locale}", $name)->withType($type)->first();
    }
}
