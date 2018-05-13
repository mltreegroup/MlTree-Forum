<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'  => 'require|max:10',
        'password'   => 'require|max:30|min:3',
        'email' => 'require|email',
        'captcha|验证码'=>'require|captcha',
    ];

    protected $scene = [
        'login' => ['email','passwrd','captcha'],
    ];
}