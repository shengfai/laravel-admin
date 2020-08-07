<?php

use Shengfai\LaravelAdmin\Models\Module;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '模型',
    'heading' => '模型管理',
    'single' => '模型',
    'model' => Module::class,
    
    'columns' => [
        'sortable' => [
            'title' => '<button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>',
            'width' => 100,
            'output' => function ($value, $model) {
                return Presenter::sortable($model->id, $model->sort);
            }
        ],
        'id' => [
            'title' => 'ID',
            'width' => 80
        ],
        'name' => [
            'title' => '名称',
            'width' => 120,
            'output' => function ($value, $model) {
                return $model->name;
            }
        ],
        'namespace' => [
            'title' => '命名空间',
            'width' => '',
            'output' => function ($value, $model) {
                return $model->namespace;
            }
        ],
        'created_at' => [
            'title' => '创建时间',
            'width' => 150,
            'sortable' => true,
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->created_at . '</span>';
            }
        ],
        'operation' => [
            'title' => '管理',
            'width' => 90,
            'output' => function ($value, $model) {
                return '<a data-title="编辑模型" data-modal="' . route('admin.model.item', [
                    'model' => 'modules',
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
        'namespace' => [
            'title' => '命名空间',
            'type' => 'text'
        ]
    ]
];
