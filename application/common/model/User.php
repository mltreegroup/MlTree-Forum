<?php
namespace app\common\model;

use app\common\validate\User as userValidate;
use Auth\Auth;
use think\Db;
use think\Model;

class User extends Model
{
    public $userObj;
    public $groupData;
    protected $pk = 'uid';
    protected $auto = ['login_ip'];
    protected $insert = ['create_ip'];
    protected $createTime = 'create_date';

    protected function setCreateIpAttr()
    {
        return \request()->ip();
    }
    protected function setLoginIpAttr()
    {
        return \request()->ip();
    }
    protected function setPasswordAttr($val, $data)
    {
        return password_encode($val);
    }
    protected function setGidAttr()
    {
        return Option::getValue('defaulegroup');
    }
    protected function getStatusTextAttr($val, $data)
    {
        $status = [-1 => '封禁', 0 => '未激活', 1 => '正常'];
        return $status[$data['status']];
    }

    public function topic()
    {
        return $this->hasMany('topic', 'uid');
    }

    public function setUser()
    {
        $this->groupData = Db::name('group')->where('gid', 2)->find();
        $this->uid = 0;
    }

    /**
     * register [用户注册]
     * @param [array] $userInforMation [用户注册信息]
     * @return [array]                 [返回数组形式返回值]
     */
    public static function register($userInforMation)
    {
        if (Option::getValue('regStatus') != '1') {
            return [false, '当前站点已经关闭注册'];
        } elseif (!empty(Db::name('user')->where('email', $userInforMation['email'])->find())) {
            return [false, '此邮箱已注册'];
        }
        $validate = new userValidate();
        if (!$validate->scene('register')->check($userInforMation)) {
            return [false, $validate->getError()];
        }
        //写入默认用户组信息
        $userInforMation['gid'] = Option::getValue('defaulegroup');

        // 判断如果是qq互联来的用户，则不用进行邮箱验证
        // 如果不是则判断是否需要邮箱验证
        if (!empty(session('openid'))) {
            $userInforMation['openid'] = session('openid');
            \session('openid',null);
            $user = user::create($userInforMation);
            return [true, '注册成功'];
        }
        if (Option::getValue('emailActive') == '1') {
            $userInforMation['code'] = createStr(30);
            $userInforMation['status'] = 0;

            $time = strtotime('+5 minutes');

            $user = user::create($userInforMation);
            //发送激活邮件
            $mail = new Mail;
            $res = $mail->SendActiveLink($user, $userInforMation['code'], $time);

            session('ActiveTime', $time);

            $msg = '注册成功，我们会给你发送一封邮件用于激活账户';
        }
        empty($msg) ? $mg = '注册成功' : $msg;
        return [true, $msg];
    }

    /**
     * login [用户登录]
     * @param [array] $loginData [用户登录信息]
     * @return [array]
     */
    public static function login($loginData)
    {
        $userData = Db::name('user')->where('email', $loginData['email'])->find();
        if (empty($userData)) {
            return [false, '用户不存在'];
        }
        if ($userData['status'] != '1') {
            $msg = user::getStatus($userData['status']);
            return [false, $msg[1]];
        }
        $validate = new userValidate();
        if (!$validate->scene('login')->check($loginData)) {
            return [false, $validate->getError()];
        }
        if (!password_verify($loginData['password'], $userData['password'])) {
            return [false, '用户名或密码错误'];
        }
        $userData['logins'] += 1;
        $userData['login_ip'] = request()->ip();
        $userData['login_date'] = time();

        Db::name('user')->where('uid', $userData['uid'])->update($userData);

        $userKey = createStr(32); //生成userKey

        session('uid', $userData['uid']);
        session('gid', $userData['gid']);
        session('username', $userData['username']);
        session('userKey', $userKey); //写入userKey
        cookie('userKey', $userKey);

        return [true, '登录成功'];
    }

    /**
     * 小程序登录函数
     * @param string $code 登录返回的code
     */
    public static function loginWx($code)
    {
        $_conf = config('mtf.Wxapplet');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$_conf['appid']}&secret={$_conf['appSecret']}&js_code={$code}&grant_type=authorization_code";
        $res = \curlGet($url, true);
        $res = json_decode($res);
        
    }

    /**
     * 激活账户
     * @param int $uid 欲激活的uid
     * @param string $code 激活码
     * @param string $time 链接附带的时间戳
     */
    public static function activateUser($uid, $code, $time)
    {
        $user = user::get($uid);
        if (empty($user)) {
            return [false, '失效或不存在'];
        }
        $status = false;
        if ($user->code == $code) {
            if ($time == session('ActiveTime')) {
                $user->status = 1;
                $user->code = '';
                $user->save();
                session('ActiveTime', null);
                $msg = '激活成功';
                $status = true;
            } else {
                $msg = '时间戳失效或有误';
            }
        } else {
            $msg = '失效或不存在';
        }

        return [$status, $msg];
    }

    /**
     * 判断用户是否登录
     */
    public static function isLogin($userKey = '')
    {
        if (empty(session('uid'))) {
            return false;
        }
        $userKey == null ? $userKey = cookie('userKey') : $userKey;
        if ($userKey !== session('userKey')) {
            return false;
        }
        return true;
    }

    public static function resetPas($uid, $oldPas, $newPas)
    {
        if (!self::isLogin()) {
            return [false, '未登录'];
        }
        $userObj = self::get($uid);
        if (!password_verify($oldPas, $userObj->password)) {
            return [false, '旧密码错误'];
        }
        $userObj->password = $newPas;
        $userObj->save();
        return [true, '密码修改成功'];
    }

    public static function forgetPas($findData)
    {
        $userData = Db::name('user')->where('email', $findData['email'])->find();
        if (empty($userData)) {
            return [false, '用户不存在'];
        }
        if (session('forget.Email') !== $findData['email']) {
            return [false, '信息错误或不存在'];
        }
        if (session('forget.Time') < time()) {
            return [false, '验证码已失效，请重新获取'];
        }
        if (session('forget.Code') !== $findData['code']) {
            return [false, '邮件验证码错误'];
        }

        $userData['password'] = password_encode($findData['password']);
        Db::name('user')->where('uid', $userData['uid'])->update($userData);
        session('forget.', null);
        return [true, '密码重置完成，请用新密码登录'];
    }

    public static function logout()
    {
        session(null);
        cookie(null);
    }

    public function getInfor($uid)
    {
        $this->userObj = self::get($uid);
        !empty($this->userObj->qqconnectId) ? $qqconnect = '已绑定' : $qqconnect = '未绑定';
        if ($this->userObj == null) {
            return false;
        }
        $data = [
            'uid' => $this->userObj->uid,
            'avatar' => $this->userObj->avatar,
            'motto' => $this->userObj->motto,
            'groupId' => $this->userObj->gid,
            'username' => $this->userObj->username,
            'email' => $this->userObj->email,
            'groupData' => $this->getGroupData($this->userObj->gid),
            'qqconnectStatus' => $qqconnect,
            'topics' => $this->userObj->topics,
            'essence' => $this->userObj->essence,
            'comments' => $this->userObj->comments,
            'create_date' => $this->userObj->create_date,
            'login_date' => $this->userObj->login_date,
            'status' => $this->userObj->status,
            'statusText' => $this->userObj->statusText,
        ];
        if (fastAuth('admin', session('uid'))) {
            $data['create_ip'] = $this->userObj->create_ip;
            $data['login_ip'] = $this->userObj->login_ip;
        }
        return $data;
    }

    public function getGroupData($gid = 0)
    {
        if ($gid == 0) {
            $gid = session('gid');
        }
        $data = Db::name('group')->where('gid', $gid)->find();
        return $data;
    }

    public function authCheck($authName)
    {
        $auth = new Auth();
        return $auth->check($authName, $this->userObj->uid);
    }

    public static function getTopicList($uid)
    {
        $userTopicList = Db::name('topic')->where('uid', $uid)->paginate(10);

        foreach ($userTopicList as $key => $value) {
            $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
            $value['time_format'] = time_format($value['create_time']);
            $value['memberData'] = Db::name('user')->where('uid', $value['uid'])->field('username,avatar')->find();
            $userTopicList[$key] = $value;
        }
        return $userTopicList;
    }

    public static function allowCreate($uid, $fid)
    {
        $data = Db::name('forum')->where('fid', $fid)->find();
        if ($data['cgroup'] == 0) {
            return true;
        }
        $user = user::get($uid);
        $res = strpos($data['cgroup'] . ',', $user->gid . ',') || $data['cgroup'] . ',' == $user->gid . ',';
        return $res;
    }

    public static function getStatus($code = 0, $uid = 0)
    {
        switch ($code) {
            case 0:
                $msg = '未激活';
                break;
            case -1:
                $msg = '封禁';
                break;
            default:
                $msg = '正常';
                break;
        }

        return [true, $msg];
    }
}
