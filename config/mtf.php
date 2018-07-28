<?php

return [
/**
 * QQ互联登录配置
 */
    'qqconnect' => [
        'appid' => '',
        'appkey' => '',
        'callback' => '',
        'scope' => 'get_user_info',
        'errorReport' => true
    ],

    /**
     * 配置回复信息模板
     * 可选参数：
     * {username} 帖子所属人昵称
     * {reuser} 回复用户昵称
     * {reuserUrl} 回复用户地址
     * {topicUrl} 帖子地址
     */
    'Message' => [
        'comment' => '<a href="/user.html">{username}</a>：<a href="{reuserUrl}">{reuser}</a>评论了你的Topic『<a href="{topicUrl}">{title}</a>』',
        'reply' => '<a href="/user.html">{username}</a>：<a href="{reuserUrl}">{reuser}</a>回复了你在『<a href="{topicUrl}">{title}</a>』的评论:<br>『<br>{comment}<br>』',
        'top' => '<a href="/user.html">{username}</a>：你的Topic『<a href="{topicUrl}">{title}</a>』被设置为置顶',
        'essence' => '<a href="/user.html">{username}</a>：你的Topic『<a href="{topicUrl}">{title}</a>』被设置为精华',
        'move' => '<a href="/user.html">{username}</a>：你的Topic『<a href="{topicUrl}">{title}</a>』被移动',
    ],
];
