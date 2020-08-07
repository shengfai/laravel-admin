<?php

use Shengfai\LaravelAdmin\Models\Dimension;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '维度',
    'heading' => '维度管理',
    'single' => '维度',
    'model' => Dimension::class,
    
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
        'slug' => [
            'title' => '固定连接',
            'width' => '',
            'output' => function ($value, $model) {
                return $model->slug;
            }
        ],
        'limits' => [
            'title' => '限制数',
            'width' => 80,
            'output' => function ($value, $model) {
                return $model->limits;
            }
        ],
        'modules' => [
            'title' => '限制模型',
            'width' => '',
            'output' => function ($value, $model) {
                $modules = $model->modules()->pluck('name');
                return '<a data-modal="' . route('admin.dimensions.modules', $model->id) . '">' .
                         ($modules->isEmpty() ? '未设置' : implode('|', $modules->toArray())) . '</a>';
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
                return '<a data-title="编辑维度" data-modal="' . route('admin.model.item', [
                    'model' => 'dimensions',
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
        'limits' => [
            'title' => '限制数',
            'type' => 'number'
        ]
    ]
];
