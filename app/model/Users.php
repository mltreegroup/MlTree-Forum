<?php
declare (strict_types = 1);

namespace app\model;

use think\facade\Cache;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin think\Model
 */
class Users extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $pk = 'uid';

    public function getLastTimeAttr($val)
    {
        return \date('Y-m-d H:i:s', $val);
    }

    public function getStatusTextAttr($val, $values)
    {
        $text = [0 => '封禁', 1 => '正常', 2 => '未激活', 3 => '禁止回复', 4 => '禁止发贴', 5 => '禁止发言', 6 => '禁止登录'];
        return $text[$values['status']];
    }

    public function group()
    {
        return $this->hasOne(Groups::class, 'gid', 'gid');
    }

    public function topics()
    {
        return $this->hasMany(Topics::class, 'uid', 'uid');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'uid', 'uid');
    }

    public static function Login($user, $pwd)
    {
        $user = Users::where('email|phone', $user)->find();
        if (empty($user)) {
            return [false, 'user does not exist'];
        }
        /**
         * BeforeUserLogin Hook
         * @position app\model\Users.Login 44
         * @name BeforeUserLogin
         * @param string $user
         * @param string $pwd
         */
        event('BeforeUserLogin', ['user' => $user, 'pwd' => $pwd]);

        if (\password_verify($pwd, $user['password'])) {
            if ($user['gid'] == 1) {
                $admin = true;
            } else {
                $admin = false;
            }
            $jwt = \app\model\JsonToken::createJWT('system', 'Login generation', time(), ['nick' => $user['nick'], 'uid' => $user['uid'], 'avatar' => $user['avatar'], 'admin' => $admin]);

            $user->last_ip = \request()->ip();
            $user->last_time = time();
            $user->save();

            // 将生成的jwt写入缓存
            Cache::set('user_' . $user->uid . '_jwt', $jwt, config('jsontoken.jwtExpTime'));

            /**
             * AfterUserLogin Hook
             * @position app\model\Users.login 61
             * @name AfterUserLogin
             * @param Users $user
             */
            \event('AfterUserLogin', $user);

            return [true, $jwt];
        } else {
            return [false, 'Wrong account or password'];
        }
    }

    public static function Register($email, $nick, $pwd)
    {
        $user = Users::getByEmail($email);
        if (!empty($user)) {
            return [false, 'Email registered'];
        }

        $defaultGroup = Options::getValue('defaultGroup');
        $insertData = [
            'email' => $email,
            'nick' => $nick,
            'password' => password_hash($pwd, PASSWORD_DEFAULT),
            'gid' => $defaultGroup,
            'create_ip' => \request()->ip(),
            'status' => 1,
        ];
        /**
         * BeforeUserReg Hook
         * @position app\model\Users.register 90
         * @param array $insertData
         */
        \event('BeforeUserReg', $insertData);

        $user = Users::create($insertData);

        if (Options::getValue('activeMail') == 1) {
            $mail = new \app\common\Mail;
            $_code = \createStr();
            $user->handle_code = $_code;
            $user->overdue_time = time() + (int) Options::getValue('reg_active_time');
            $user->status = 2;
            $user->save();

            $mail->SendActiveLink($user, );
            return [true, 'The registration is successful. We sent you an activation email. Please operate after activation'];
        }
        return [true, 'The registration is successful.Welcome'];
    }

    public static function changePwd($old, $new)
    {
        $uid = \request()->jwt->uid;
        $user = Users::find($uid);
        if (\password_verify($old, $user->password)) {
            // ChangePWd
            $user->password = password_hash($new, PASSWORD_DEFAULT);
            $user->save();

            // Delete JWT User To ChangePWd
            Cache::tag('user_' . $uid)->delete('user_kwt');

            \event('ChangeUserPWd', ['uid' => $uid, 'oldPwd' => $old, 'newPwd' => $new]);
            return [true, 'Changed successfully'];
        }
        return [false, 'Password validation failed'];
    }

    public static function forgetPwd()
    {
        $uid = \request()->jwt->uid;
        $user = Users::find($uid);
    }

    /**
     * 一个简单的方法用于检查用户的状态是否允许执行指定操作
     */
    public static function checkStatus($action, $uid = null)
    {
        $jwt = \request()->jwt;
        if ($jwt->admin) {
            return true;
        }
        $uid ?? $uid = $jwt->uid;
        $user = Users::find($uid);
        if (empty($user) || $user->status == 0 || $user->status == 2) {
            return false;
        }
        if ($action === 'login') {
            return $user->status == 6 ? false : true;
        } elseif ($action === 'postTopic') {
            return $user->status == 4 || $user->status == 5 ? false : true;
        } elseif ($action === 'postComment') {
            return $user->status == 3 || $user->status == 5 ? false : true;
        }
        return true;
    }
}
