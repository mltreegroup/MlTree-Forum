<?php
namespace app\index\validate;

use think\Validate;

class Topic extends Validate
{
    protected $rule = [
        'subject'  => 'require|max:60|token',
        'fid'   => 'require',
        'content' => 'require',
        'captcha|验证码'=>'require|captcha',
    ];

    protected $scene = [
        'create' => ['subject','fid','content'],
    ];
}