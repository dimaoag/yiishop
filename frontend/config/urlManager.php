<?php
return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
//    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => true,
    'rules' => [
        '' => 'site/index',
        '<_a:about>' => 'site/<_a>',
        'contact' => 'contact/contact/index',
        'signup' => 'auth/signup/index',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        'auth/network/auth' => 'auth/network/auth',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        'catalog' => 'shop/catalog/index',
//        ['class' => 'frontend\urls\CategoryUrlRule'],
        'catalog/<id:\d+>' => 'shop/catalog/product',


        'cabinet' => 'cabinet/default/index',
        'cabinet/<_c:[\w\-]+>' => 'cabinet/<_c>/index',
        'cabinet/<_c:[\w\-]+>/<id:\d+>' => 'cabinet/<_c>/view',
        'cabinet/<_c:[\w\-]+>/<_a:[\w-]+>' => 'cabinet/<_c>/<_a>',
        'cabinet/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'cabinet/<_c>/<_a>',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];