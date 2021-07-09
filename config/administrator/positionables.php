<?php
use Shengfai\LaravelAdmin\Models\Positionable;

return [
    'title' => '推荐内容',
    'heading' => '推荐内容管理',
    'single' => '推荐内容',
    'model' => Positionable::class,
    
    'query_parameters' => [
        'sorting' => [
            'default' => '-id',
            'allowed' => [
                'id',
                'created_at'
            ]
        ],
        'filtering' => [
            'allowed' => [
                'id'
            ]
        ],
        'including' => [
            'allowed' => []
        ],
        'relationships' => [
            'with' => [],
            'count' => [
                'datas'
            ]
        ]
    ],
    
    'columns' => [
        'id' => [
            'title' => '编号',
            'width' => 60,
            'align' => 'center',
            'output' => function ($value, $model) {
                return sprintf("%'.03d", $value);
            }
        ],
        'name' => [
            'title' => '名称',
            'width' => 180,
            'output' => function ($value, $model) {
                return '<img style="width:28px; vertical-align: text-bottom;" data-tips-image="" src="' . ($model->cover_pic ?: '/admin/images/image.png') . '"> ' . $value;
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
            'width' => 80,
            'align' => 'center',
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->created_at->diffForHumans() . '</span>';
            }
        ]
    ],
    
    'actions' => [],
    
    'edit_fields' => [
        'title' => [
            'title' => '名称',
            'type' => 'text'
        ],
        'cover_pic' => [
            'title' => '封面',
            'type' => 'image',
            'naming' => 'random',
            'location' => public_path(),
            'size_limit' => 2,
            'tips' => '建议上传图片的尺寸比例：16:9',
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
