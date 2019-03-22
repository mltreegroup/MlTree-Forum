<?php
return [
    'pid' => '', // 发布到官方插件中心，经过认证后的加密PID
    'name' => 'reg2sms', // 插件名称
    'path' => 'reg2sms', // 插件目录
    'version' => '1.0.0', // 插件版本
    'author' => [ // 插件作者信息
        'name' => 'Kingsr', //作者名称
        'contacts' => 'i@kingsr.cc', // 作者联络方式
    ],
    'description' => '注册需要进行绑定手机才能发帖。此插件使用腾讯云短信服务。', // 插件表述
    'permissions' => ['database', 'view', 'option'], // 待定，不一定保留，因为启用后无法限制插件的调用
    'about' => 'https://forum.kingsr.cc', // 关于
];
