<?php
namespace app\index\validate;

use think\Validate;

class Topic extends Validate
{
    protected $rule = [
        'title'  => 'require|max:60',
        'fid'   => 'require',
        'content' => 'require|token',
        'captcha|验证码'=>'require|captcha',
    ];

    protected $scene = [
        'create' => ['title','fid','content'],
    ];
}