<?php
use Spatie\Permission\Models\Role;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '角色',
    'heading' => '角色管理',
    'single' => '角色',
    'model' => Role::class,
    
    'columns' => [
        'sortable' => [
            'title' => '<button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>',
            'width' => 100,
            'output' => function ($value, $model) {
                return Presenter::sortable($model->id, $model->sort);
            }
        ],
        'name' => [
            'title' => '名称',
            'width' => 120
        ],
        'remark' => [
            'title' => '描述',
            'width' => ''
        ],
        'status' => [
            'title' => '状态',
            'width' => 110,
            'output' => function ($value, $model) {
                return Presenter::switchable($model);
            }
        ],
        'created_at' => [
            'title' => '时间',
            'width' => 150,
            'sortable' => true,
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->created_at->toDateTimeString() . '</span>';
            }
        ],
        'operation' => [
            'title' => '管理',
            'width' => 90,
            'output' => function ($value, $model) {
                return '<a data-title="编辑角色" data-modal="' . route('admin.model.item', [
                    'model' => 'roles',
                    'id' => $model->id
                ]) . '?action=edit">编辑</a><span class="text-explode">|</span><a data-title="角色授权" data-open="' . route('admin.roles.authorizations', $model->id) . '">授权</a>';
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
            'title' => '角色名称',
            'type' => 'text'
        ],
        'remark' => [
            'title' => '角色描述',
            'type' => 'textarea'
        ],
        'status' => [
            'title' => '是否启用',
            'type' => 'switch',
            'options' => [
                '启用',
                '禁用'
            ]
        ]
    ]
];
