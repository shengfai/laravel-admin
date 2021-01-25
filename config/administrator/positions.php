<?php

use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '推荐位',
    'heading' => '推荐位管理',
    'single' => '推荐位',
    'model' => Position::class,
    
    'query_parameters' => [
        'relationships' => [
            'count' => ['datas']
        ]
    ],
    
    'columns' => [
        'sortable' => [
            'title' => '<button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>',
            'width' => 80,
            'align' => 'center',
            'output' => function ($value, $model) {
                return Presenter::sortable($model->id, $model->sort);
            }
        ],
        'id' => [
            'title' => 'ID',
            'width' => 60,
            'align' => 'center'
        ],
        'name' => [
            'title' => '名称',
            'width' => 200,
            'output' => function ($value, $model) {
                return '<img style="width:30px; vertical-align: text-bottom;" data-tips-image="" src="' .
                         ($model->cover_pic ?  : '/admin/images/image.png') . '"> ' . $value;
            }
        ],
        'datas_count' => [
            'title' => '推荐数',
            'width' => 80,
            'align' => 'center',
            'output' => function ($value, $model) {
                return $model->datas_count;
            }
        ],
        'description' => [
            'title' => '描述',
            'width' => '',
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->description . '</span>';
            }
        ],
        'created_at' => [
            'title' => '创建时间',
            'width' => 120,
            'sortable' => true,
            'align' => 'center',
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->created_at->diffForHumans() . '</span>';
            }
        ],
        'operation' => [
            'title' => '管理',
            'width' => 120,
            'output' => function ($value, $model) {
                return '<a data-title="编辑标签" data-modal="' . route('admin.model.item', [
                    'model' => 'positions',
                    'id' => $model->id
                ]) . '?action=edit">编辑</a> <span class="text-explode">|</span>
            			<a data-open="' . route('admin.positions.datas', $model->id) . '">关联内容</a>';
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
        'cover_pic' => [
            'title' => '封面',
            'type' => 'image',
            'naming' => 'random',
            'location' => public_path(),
            'size_limit' => 2,
            'tips' => '建议上传图片的尺寸为：128*128。',
            'sizes' => [
                [
                    200,
                    150,
                    'crop',
                    public_path() . '/resize/',
                    100
                ]
            ],
            'required' => false
        ],
        'description' => [
            'title' => '描述',
            'type' => 'textarea',
            'required' => false
        ]
    ]
];