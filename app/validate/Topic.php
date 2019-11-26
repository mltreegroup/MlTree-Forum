<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Topic extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'tid' => 'require|number',
        'fid' => 'require|number',
        'title' => 'require|max:255',
        'content' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'tid.require' => 'Required for the Tid',
        'fid.require' => 'Required for the plate',
        'title.require' => 'Title is required',
        'title.max' => 'Title less than 255 words',
        'content.require' => 'Content is required',
    ];

    protected $scene = [
        'create' => ['fid', 'title', 'content'],
        'update' => ['tid', 'title', 'content'],
    ];
}
