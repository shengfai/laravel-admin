<?php

use Shengfai\LaravelAdmin\Models\Tag;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '标签',
    'heading' => '标签管理',
    'single' => '标签',
    'model' => Tag::class,
    
    'columns' => [
        'sortable' => [
            'title' => '<button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>',
            'width' => 100,
            'output' => function ($value, $model) {
                return Presenter::sortable($model->id, $model->order_column);
            }
        ],
        'name' => [
            'title' => '名称',
            'width' => '',
            'output' => function ($value, $model) {
                return $model->name;
            }
        ],
        'slug' => [
            'title' => '固定链接',
            'width' => '',
            'output' => function ($value, $model) {
                return $model->slug;
            }
        ],
        'type' => [
            'title' => '所属分类',
            'width' => 150
        ],
        'taggables_count' => [
            'title' => '关联数',
            'width' => 100,
            'output' => function ($value, $model) {
                return $model->taggables()->count();
            }
        ],
        'created_at' => [
            'title' => '创建时间',
            'width' => 150,
            'sortable' => true
        ],
        'operation' => [
            'title' => '管理',
            'width' => 90,
            'output' => function ($value, $model) {
                return '<a data-title="编辑标签" data-modal="' . route('admin.model.item', [
                    'model' => 'tags',
                    'id' => $model->id
                ]) . '?action=edit">编辑</a><span class="text-explode">';
            }
        ]
    ],
    
    'actions' => [
        'create' => [
            'title' => '添加',
            'action' => function ($modelName) {
                return route('admin.model.new', $modelName);
            }
        ]
    ],
    
    'edit_fields' => [
        'name' => [
            'title' => '名称',
            'type' => 'text'
        ],
        'type' => [
            'title' => '分类',
            'type' => 'text'
        ]
    ]
];
