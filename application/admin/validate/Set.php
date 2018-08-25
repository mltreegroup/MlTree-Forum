<?php
namespace app\admin\validate;

use think\Validate;

class Set extends Validate
{
    protected $rule = [
        'siteTitle'  => 'require|max:60|token',
        'notice' => 'print',
        'siteDes' => 'print',
        'siteFooterJs' => 'print',
        'siteIcp' => 'print',
        'siteKeywords' => 'print',
        
        'siteStatus'   => 'require|number|token',
        'regStatus' => 'require|number',
        'defaulegroup' => 'require|number',
        'allowQQreg' => 'require|number',
        'closeContent' => 'print',
        'full' => 'require|number',
        
        'themePrimary' => 'require|token',
        'themeLayout' => 'require',
        'themeAccent' => 'require',
        'discolour' => 'require',

        'fromAdress' => 'require|email',
        'fromName' => 'require',
        'replyTo' => 'print',
        'smtpHost' => 'require',
        'smtpPass' => 'require',
        'smtpPort' => 'require|number',
        'smtpUser' => 'print',

        'name' => 'require|token',
        'introduce' => 'print',
        'fid' => 'number',
        'cgroup' => 'print',

        'Id' => 'number',
        'picurl' => 'url',
        'url' => 'require|url',
        'sold' => 'number',
        'title' => 'require|token',

        'email' => 'require|email|token',
        'gid' => 'number',
        'password' => 'print',
        'status' => 'require|number',
        'uid' => 'require|number',
        'username' => 'require',

        'groupName' => 'require|token',
        'rules' => 'print',

        'name' => 'require|token',
        'status' => 'require|number',
        'title' => 'require',
        'type' => 'require|number',

        'content' => 'require|token',
    ];

    protected $scene = [
        'base' => ['siteTitle','notice','siteDes','siteFooterJs','siteIcp','siteKeywords'],

        'baseMail' => [
            'fromAdress',
            'fromName',
            'replyTo' ,
            'smtpHost',
            'smtpPass',
            'smtpPort',
            'smtpUser',
        ],

        'baseReg' => ['siteStatus','regStatus','defaulegroup','allowQQreg','full'],

        'baseTheme' => [
            'themePrimary',
            'themeLayout',
            'themeAccent',
            'discolour',
        ],

        'forum' => [
            'name',
            'fid',
        ],

        'link' => [
            'Id',
            'picurl',
            'url' ,
            'sold',
            'title',
        ],

        'user' => [
            'email',
            'gid',
            'password',
            'status',
            'uid',
            'username',
        ],

        'group' => [
            'groupName',
            'rules',
        ],

        'auth' => [
            'name',
            'status',
            'title',
            'type',
        ],

        'message' => [
            'title',
            'content',
        ],

        ];
}