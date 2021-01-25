<?php

use Illuminate\Support\Arr;
use Shengfai\LaravelAdmin\Models\Tag;
use Shengfai\LaravelAdmin\Models\Dimension;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '标签',
    'heading' => '标签管理',
    'single' => '标签',
    'model' => Tag::class,
    
    'query_parameters' => [
        'relationships' => [
            'with' => ['dimension'],
            'count' => ['taggables']
        ]
    ],
    
    'columns' => [
        'sortable' => [
            'title' => '<button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>',
            'width' => 80,
            'output' => function ($value, $model) {
                return Presenter::sortable($model->id, $model->sort);
            }
        ],
        'name' => [
            'title' => '名称',
            'width' => 180,
            'output' => function ($value, $model) {
                return $model->name;
            }
        ],
        'dimension_id' => [
            'title' => '维度',
            'width' => 120,
            'output' => function ($value, $model) {
                return '<span class="color-blue">' . $model->dimension->name . '</span>';
            }
        ],
        'parent_id' => [
            'title' => '父标签',
            'width' => 180,
            'output' => function ($value, $model) {
                return '<span class="color-blue">' . $model->parent->name . '</span>';
            }
        ],
        'taggables_count' => [
            'title' => '关联数',
            'width' => 80,
            'output' => function ($value, $model) {
                return $model->taggables_count;
            }
        ],
        'remark' => [
            'title' => '备注',
            'width' => '',
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->remark . '</span>';
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
        'parent_id' => [
            'title' => '父标签',
            'type' => 'tag',
            'customized' => false,
            'maxselection' => 1,
            'options' => function () {
                $tags = Tag::ofParent(0)->pluck('name', 'id');
                return Arr::prepend($tags->toArray(), '无', 0);
            }
        ],
        'name' => [
            'title' => '名称',
            'type' => 'text'
        ],
        'dimension_id' => [
            'title' => '维度',
            'type' => 'tag',
            'customized' => false,
            'maxselection' => 1,
            'options' => function () {
                return Dimension::pluck('name', 'id')->toArray();
            }
        ],
        'remark' => [
            'title' => '备注',
            'type' => 'textarea'
        ]
    ]
];
