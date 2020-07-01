<?php

namespace Shengfai\LaravelAdmin\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Presenter
{

    /**
     * @param int $id
     * @param int $sort
     * @return string
     */
    public static function sortable(int $id, int $sort)
    {
        return '<span class="list-table-sort-td"><input name="_' . $id . '" value="' . $sort .
                 '" class="list-sort-input"></span>';
    }

    /**
     * @param Model $model
     * @param string $name
     * @return string
     */
    public static function switchable(Model $model = null, string $name = 'status')
    {
        $checked = $model->{$name} ? 'checked' : '';
        $action = route('admin.model.save', [
            'model' => Str::of(class_basename($model))->plural()->lower(),
            'id' => $model->id
        ]);
        return '<input data-action="' . $action . '" data-csrf="' . csrf_token() . '" type="checkbox" name="' . $name .
                 '" lay-skin="switch" lay-filter="switchable" lay-text="开启|关闭"' . $checked . '>';
    }

    /**
     * @param string $url
     * @param string $target
     */
    public static function linkable(string $url, string $target = 'modal', $title = '链接')
    {
        return '<a data-title="' . $title . '" data-' . $target . '="' . $url . '">' . $title . '</a>';
    }

    public static function getUserinfo($user)
    {
        $name = $user->name ?? '未设置';
        $avatar = $user->avatar ?? '/admin/images/avatar.png';
        return '<div class="inline-block text-top margin-right-5"><img class="layui-btn-radius" style="height:30px; float: left;" data-tips-image="" src="' .
                 $avatar . '"><p class="color-desc">' . $name . '</p></div>';
    }

    /**
     * @return string
     */
    public static function getFullName()
    {
        return '';
    }
}