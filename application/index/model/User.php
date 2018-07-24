<?php
namespace app\index\model;

use think\Model;
use think\Db;
use app\index\validate\User as userValidate;
use Auth\Auth;

class User extends Model
{
    public $userObj;
    public $groupData;
    protected $pk = 'uid';
    protected $auto = ['login_ip'];
    protected $insert = ['create_ip'];

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
            return [false,'当前站点已经关闭注册'];
        } elseif (!empty(Db::name('user')->where('email', $userInforMation['email'])->find())) {
            return [false,'此邮箱已注册'];
        }
        $validate = new userValidate();
        if (!$validate->scene('register')->check($userInforMation)) {
            return [false,$validate->getError()];
        }
        if (!empty(session('openid'))) {
            $userInforMation['openid'] = session('openid');
        }

        if (Option::getValue('regMail') == '1') {
            if (session('reg.Time') < time()) {
                return [false,'验证码已失效，请重新获取'];
            }
            if (session('reg.Code') !== $userInforMation['code']) {
                return [false,'邮件验证码错误'];
            }
        }

        $user = user::create($userInforMation);
        session('regMail', null);
        session('regCode', null);
        return [true,'注册成功'];
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
            return [false,'用户不存在'];
        }
        if ($userData['status'] != '1') {
            return [false,'用户被封禁'];
        }
        $validate = new userValidate();
        if (!$validate->scene('login')->check($loginData)) {
            return [false,$validate->getError()];
        }
        if (!password_verify($loginData['password'], $userData['password'])) {
            return [false,'用户名或密码错误'];
        }
        $userData['logins'] += 1;
        $userData['login_ip'] = request()->ip();
        $userData['login_date'] = time();

        Db::name('user')->where('uid', $userData['uid'])->update($userData);

        $userKey = createStr(32);//生成userKey

        session('uid', $userData['uid']);
        session('gid', $userData['gid']);
        session('username', $userData['username']);

        session('userKey', $userKey);//写入userKey
        cookie('userKey', $userKey);

        return [true,'登录成功'];
    }

    public static function isLogin($userKey='')
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
            return [false,'未登录'];
        }
        $userObj = self::get($uid);
        if (!password_verify($oldPas, $userObj->password)) {
            return [false,'旧密码错误'];
        }
        $userObj->password = $newPas;
        $userObj->save();
        return [true,'密码修改成功'];
    }

    public static function forgetPas($findData)
    {
        $userData = Db::name('user')->where('email', $findData['email'])->find();
        if (empty($userData)) {
            return [false,'用户不存在'];
        }
        if (session('forget.Time') < time()) {
            return [false,'验证码已失效，请重新获取'];
        }
        if (session('forget.Code') !== $findData['code']) {
            return [false,'邮件验证码错误'];
        }

        $userData['password'] = password_encode($findData['password']);
        Db::name('user')->where('uid', $userData)->update($userData);
        session('forget.', null);
        return [true];
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
        return [
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
        ];
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
        $userTopicList = Db::name('topic')->where('uid', $uid)->select();
            
        foreach ($userTopicList as $key => $value) {
            $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
            $value['time_format'] = time_format($value['create_time']);
            $value['userData'] = Db::name('user')->where('uid', $value['uid'])->field('username,avatar')->find();
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
        $res = strpos($data['cgroup'].',', $user->gid.',') || $data['cgroup'].',' == $user->gid.',';
        return $res;
    }
}
