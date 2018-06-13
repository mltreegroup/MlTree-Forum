<?php
namespace app\api\validate;

use think\Validate;

class Api extends Validate
{
    protected $rule = [
        'tid'  => 'require|token',
        'fid'   => 'require',
        'content' => 'require|token',
        'captcha|验证码'=>'require|captcha',
    ];

    protected $scene = [
        'create' => ['title','fid','content'],
    ];
}