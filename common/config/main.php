<?php
return [
	'language'=>'zh-CN',
    'timeZone'=>'Asia/Chongqing',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

		'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
             'enableStrictParsing'=>FALSE,//不要求网址严格匹配，则不需要输入rules
            'rules' => [
            ],
        ],
        'formatter' => [
            'dateFormat' => 'Y-M-d',
            'datetimeFormat' => 'Y-M-d HH:mm:ss',
            'timeFormat' => 'H:i:s',
        ],

    ],
];
