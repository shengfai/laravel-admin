<?php

/**
 * The main site settings page.
 */

return [
    
    /*
     * Settings page title
     * @type string
     */
    'title' => '站点配置',

    /*
     * The edit fields array
     *
     * @type array
     */
    'edit_fields' => [
        'site_name' => [
            'title' => '站点名称',
            'type' => 'text',
            'tips' => '网站名称，显示在浏览器标签上'
        ],
        'site_keywords' => [
            'title' => '关键词',
            'type' => 'text'
        ],
        'site_description' => [
            'title' => '站点描述',
            'type' => 'text'
        ],
        'site_copy' => [
            'title' => '版权信息',
            'type' => 'text',
            'tips' => '程序的版权信息设置，在后台登录页面显示'
        ],
        'app_name' => [
            'title' => '程序名称',
            'type' => 'text',
            'tips' => '当前程序名称，在后台主标题上显示'
        ],
        'app_version' => [
            'title' => '程序版本',
            'type' => 'text',
            'tips' => '当前程序版本号，在后台主标题上标显示'
        ],
        'site_icon' => [
            'title' => '站点Icon',
            'type' => 'image',
            'naming' => 'random',
            'location' => public_path(),
            'size_limit' => 2,
            'tips' => '请上传ico格式图片。建议上传ICO图标的尺寸为：64*64。',
            'sizes' => [
                [
                    200,
                    150,
                    'crop',
                    public_path() . '/resize/',
                    100
                ]
            ]
        ]
    ],

    /*
     * The validation rules for the form, based on the Laravel validation class
     *
     * @type array
     */
    'rules' => [
        'site_name' => 'required|max:50',
        'logo' => 'site_icon'
    ],

    /*
     * This is run prior to saving the JSON form data
     *
     * @type function
     * @param array		$data
     *
     * @return string (on error) / void (otherwise)
     */
    'before_save' => function (&$data) {
        $data['site_name'] = trim($data['site_name']);
    },

    /*
     * The permission option is an authentication check that lets you define a closure that should return true if the current user
     * is allowed to view this settings page. Any "falsey" response will result in a 404.
     *
     * @type closure
     */
    'permission' => function () {
        return Auth::user()->hasRole('Founder');
    },

    /*
     * This is where you can define the settings page's custom actions
     */
    'actions' => [
        // Ordering an item up
        'clear_page_cache' => [
            'title' => 'Clear Page Cache',
            'messages' => [
                'active' => 'Clearing cache...',
                'success' => 'Page Cache Cleared',
                'error' => 'There was an error while clearing the page cache'
            ],
            // the settings data is passed to the closure and saved if a truthy response is returned
            'action' => function (&$data) {
                Cache::forget('pages');
                return true;
            }
        ]
    ]
];
