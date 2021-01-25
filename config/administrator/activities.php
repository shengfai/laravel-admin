<?php
use Illuminate\Support\Arr;
use Spatie\Activitylog\Models\Activity;
use Shengfai\LaravelAdmin\Fields\Presenter;

return [
    'title' => '日志',
    'heading' => '日志管理',
    'single' => '日志',
    'model' => Activity::class,
    
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
                'log_name'
            ]
        ],
        'including' => [
            'allowed' => []
        ],
        'relationships' => [
            'with' => [],
            'count' => []
        ]
    ],
    
    'columns' => [
        'id' => [
            'title' => '编号',
            'width' => 60,
            'align' => 'center'
        ],
        'log_name' => [
            'title' => '类型',
            'width' => 70,
            'align' => 'center',
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
            'width' => 90,
            'output' => function ($value, $model) {
                return Presenter::getUserinfo($model->causer);
            }
        ],
        'ip' => [
            'title' => 'IP',
            'width' => 90
        ],
        'method' => [
            'title' => '方法',
            'width' => 60
        ],
        'host' => [
            'title' => '主机',
            'width' => 120
        ],
        'node' => [
            'title' => '节点',
            'width' => ''
        ],
        'user_agent' => [
            'title' => 'UserAgent',
            'width' => 120
        ],
        'description' => [
            'title' => '内容',
            'width' => 90
        ],
        'created_at' => [
            'title' => '时间',
            'width' => 150,
            'sortable' => true,
            'output' => function ($value, $model) {
                return '<span class="color-desc">' . $model->created_at . '</span>';
            }
        ],
        'properties' => [
            'title' => '提交参数',
            'visible' => false
        ],
        'operation' => [
            'title' => '管理',
            'width' => 60,
            'output' => function ($value, $model) {
                $link = route('admin.model.item', [
                    'model' => 'activities',
                    'id' => $model->id
                ]);
                return Presenter::linkable($link);
            }
        ]
    ],
    
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
