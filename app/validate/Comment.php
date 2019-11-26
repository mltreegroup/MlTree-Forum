<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Comment extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'tid' => 'require|number',
        'uid' => 'require|number',
        'content' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'tid.require' => 'Tid is required',
        'tid.number' => 'Bad TID format',
        'content.require' => 'Content is required',
    ];

    protected $sence = [
        'create' => ['tid', 'content'],
        'update' => ['tid', 'content'],
    ];
}
