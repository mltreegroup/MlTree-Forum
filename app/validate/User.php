<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'nick' => 'max:50|require',
        'user' => 'require',
        'email' => 'email|require',
        'pwd' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'nick.require' => 'Nick is required',
        'user.require' => 'Account is required',
        'email.email' => 'Bad Email format',
        'email.require' => 'Email is required',
        'pwd.require' => 'Password is required',
    ];

    protected $scene = [
        'login' => ['user', 'pwd'],
        'register' => ['email', 'nick', 'pwd'],
    ];
}
