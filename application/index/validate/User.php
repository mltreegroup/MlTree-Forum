<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'  => 'require|max:10',
        'oldpassword' => 'require|max:30|min:3',
        'password'   => 'require|max:30|min:3',
        'repassword' => 'require|max:30|min:3',
        'email' => 'require|email',
        'captcha|验证码'=>'require|captcha',
    ];

    protected $scene = [
        'register' => ['username','password','repassword','email','captcha'],
        'login' => ['email','passwrd','captcha'],
        'valiEmail' => ['email'],
        'ResetPas' => ['oldpassword','password','repassword'],
        'forgetPas' => ['email','password','repassword','captcha'],
    ];
}