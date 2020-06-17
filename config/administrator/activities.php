<?php

use Illuminate\Support\Arr;
use Spatie\Activitylog\Models\Activity;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '日志',
    'heading' => '日志管理',
    'single' => '日志',
    'model' => Activity::class,

    'columns' => [
        'id' => [
            'title' => '编号',
            'width' => 70
        ],
        'log_name' => [
            'title' => '类型',
            'width' => 80,
            'output' => function ($value, $model) {
                return Arr::get([
                    'run' => '运行日志',
                    'console' => '操作日志',
                    'behavior' => '行为日志'
                ], $model->log_name);
            }
        ],
        'causer' => [
            'title' => '用户',
            'width' => 100,
            'output' => function ($value, $model) {
                return Presenter::getUserinfo($model->causer);
            }
        ],
        'ip' => [
            'title' => 'IP',
            'width' => 110
        ],
        'method' => [
            'title' => '方法',
            'width' => 80
        ],
        'host' => [
            'title' => '主机',
            'width' => 130
        ],
        'node' => [
            'title' => '节点',
            'width' => ''
        ],
        'user_agent' => [
            'title' => 'UserAgent'
        ],
        'description' => [
            'title' => '内容'
        ],
        'created_at' => [
            'title' => '时间',
            'width' => 150,
            'sortable' => true
        ],
        'properties' => [
            'title' => '提交参数',
            'visible' => false
        ],
        'operation' => [
            'title' => '管理',
            'width' => 80,
            'output' => function ($value, $model) {
                $link = route('admin.model.item', [
                    'model' => 'activities',
                    'id' => $model->id
                ]);
                return Presenter::linkable($link);
            }
        ]
    ],

    // 'batch_actions' => [
    //     'delete' => [
    //         'title' => '删除',
    //         'action' => function ($modelName) {
    //             return 'delete';
    //         }
    //     ]
    // ],

    'filters' => [
        'log_name' => [
            'title' => '日志类型',
            'type' => 'enum',
            'options' => [
                'all' => '全部日志',
                'run' => '运行日志',
                'console' => '操作日志',
                'behavior' => '行为日志'
            ]
        ]
    ]
];
