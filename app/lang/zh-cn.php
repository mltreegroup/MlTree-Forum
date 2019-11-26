<?php
/**
 * 中文语言包以及语言包配置实例
 * 空白语言包位于./example.php
 */
return [
    // system
    'success' => '成功',
    'error' => '错误',
    'State error' => '状态错误',
    'Insufficient authority' => '权限不足',
    'Created successfully' => '创建成功',
    'Updated successflly' => '更新成功',
    'Deleted successfully' => '删除成功',

    // JsonToken
    'JsonToken Expired' => 'JsonToken已过期',
    'sign validation error' => 'Sign验证错误',

    // app\model\Users
    'user does not exist' => '用户不存在',
    'Wrong account or password' => '账号或密码错误',
    'Email registered' => '邮箱已注册',
    'The registration is successful. We sent you an activation email. Please operate after activation' => '注册成功。我们给你发了一封激活邮件，请激活后操作。',
    'The registration is successful.Welcome' => '注册成功，欢迎',
    'Changed successfully' => '密码修改成功',
    'Password validation failed' => '密码验证失败',

    // app\controller\User
    'Login successfully' => '登录成功',
    'Old Or New Password is required' => '新或旧不能为空',

    // app\controller\Topic
    'Topic created successfully' => 'Topic创建成功',
    'Topic does not exist' => 'Topic不存在',

    // app\controller\Forum
    'Forum does not exist' => 'Forum不存在',

    // app\controller\Comment
    'Comment success' => '评论成功',

    // app\validate\User
    'Nick is required' => '昵称不能为空',
    'Account is required' => '账号不能为空',
    'Bad Email format' => '邮箱格式错误',
    'Email is required' => '邮箱不能为空',
    'Password is required' => '密码不能为空',

    // app\validate\Topic
    'Required for the Tid' => 'Tid必须',
    'Required for the plate' => '所属板块不能为空',
    'Title is required' => '标题不能为空',
    'Title less than 255 words' => '标题不能超过255个字符',
    'Content is required' => '内容不能为空',

    // app\validate\Forum
    'ForumName is required' => '板块名称不嫩为空',

    // app\validate\Comment
    'Tid is required' => 'Tid不能为空',
    'Bad TID format' => 'Tid格式错误',
];
