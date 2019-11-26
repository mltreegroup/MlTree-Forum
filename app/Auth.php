<?php
declare (strict_types = 1);

namespace app;

use app\model\Users;
use think\facade\Db;

class Auth
{
    public $errMsg = '';
    public $uid = 0;
    public $rule; // 规则列表

    protected $_config = [
        'auth_on' => true,
        'auth_type' => 1,
    ];

    public function __construct()
    {
        if (isset(request()->jwt)) {
            $this->uid = \request()->jwt->uid;
        }
        $this->rule = Db::name('rule')->select();
    }

    /**
     * 检查权限
     * @param string|array $name 为空默认认证当前URL
     * @param int $uid 为空默认认证当前uid
     * @param string $relation
     * @param boolean $checkStatus 同时检查状态是否为1
     * @return boolean
     */

    //['create','admin','app\controller\User.login','']我要验证的
    //[1,2,3,4] =>
    //
    public function check($name = null, $uid = 0, $relation = 'or', $checkStatus = true)
    {
        $uid = $uid == 0 ?? $this->uid;
        $userinfo = $this->getUserInfo($uid);
        $userList = $this->getAuthList($uid);
        $_url = \request()->controller(true) . '\\' . \request()->action(); // 当前URL
        $_class = 'app\\controller\\' . \request()->controller(true); //

        if ($userinfo->status != 1 && $checkStatus) {
            return [false, \lang('Abnormal user status')];
        }

        // 如果name为空，默认验证当前url
        if (empty($name)) {
            $_rule = Db::name('rule')->where('rule', $_url)->where('type', 'url')->find();
            if (empty($_rule)) { // Pass by default if no this rule
                return true;
            } else {
                if (\in_array($_rule['ruid'], $userList)) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = \array_filter(\explode(',', $name));
            } else {
                $name = [$name];
            }
        }
        // 将name转为rule数组
        foreach ($name as $val) {
            $_name[] = \strtolower(Db::name('rule')->where('name', $val)->value('rule'));
        }
        $list = [];
        //将数组id转为实际规则
        foreach ($userList as $auth) {
            $list[] = \strtolower(Db::name('rule')->where('ruid', $auth)->value('rule'));
        }

        // 比较两个数组，取差集
        $diff = array_diff($_name, $list);

        if (empty($diff)) {
            return true;
        } elseif ($relation === 'or') {
            if (count($diff) < count($_name)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 获取指定用户的权限列表
     * @param int $uid
     * @return array
     */
    public function getAuthList($uid)
    {
        $user = Users::find($uid);
        $group = $user->group;

        $list = \array_filter(\explode(',', $group->rule));

        return $list;
    }

    public function getUserInfo($uid)
    {
        $user = Users::find($uid);
        return $user;
    }
}
