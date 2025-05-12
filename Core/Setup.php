<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$config = [
    '基础设置' => [
        'title' => '基础设置',
        'fields' => [
            [
                'type' => 'Text',
                'name' => 'FaviconUrl',
                'value' => null,
                'label' => 'Favicon',
                'description' => '请输入网站图标地址，如果不填则默认使用' . Get::SiteUrl(false) . 'favicon.ico'
            ],
            [
                'type' => 'Textarea',
                'name' => 'Custom_Code_Head',
                'value' => null,
                'label' => '顶部代码',
                'description' => '顶部自定义代码，将会在页面head标签插入'
            ],
            [
                'type' => 'Textarea',
                'name' => 'Custom_Code_Foot',
                'value' => null,
                'label' => '底部代码',
                'description' => '底部自定义代码，将会在页面底部body标签插入'
            ]
        ]
    ],
    'TTDF-Options' => [
        'title' => '其他设置',
        'fields' => [
            [
                'type' => 'Select',
                'name' => 'TTDF_RESTAPI_Switch',
                'value' => 'true',
                'label' => 'REST API',
                'description' => '<div style="color:red;font-size:24px;">请勿关闭，否则地球会爆炸！</div>TTDF框架内置的 REST API<br/>使用教程可参见 <a href="https://github.com/Typecho-Framework/Typecho-Theme-Development-Framework#rest-api" target="_blank">*这里*</a>',
                'options' => [
                    'true' => '开启',
                    'false' => '关闭'
                ]
            ],
        ]
    ],
];

return $config;
