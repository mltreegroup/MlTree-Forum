<?php
namespace app\index\model;

require("../vendor/autoload.php");

use think\Model;
use think\Db;
use app\index\model\Option;
use Md\MDAvatars;

class User extends Model
{
    public $userInfo;
    public $groupData;

    protected $pk = 'uid';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_date';
    protected $updateTime = 'login_date';
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
        return password_encode($data['password']);
    }
    protected function setGidAttr()
    {
        return Option::getValue('defaulegroup');
    }

    public static function checkCaptcha($code)
    {
        if (!captcha_check($code)) {
            return false;
        }
        return true;
    }

    public static function register($regInfomation)
    {
        if (Option::getValue('regStatus') != '1') {
            return [false,'当前站点关闭注册'];
        }
        if (!empty(Db::name('user')->where('email', $regInfomation['email'])->find())) {
            return [false,'该邮箱已被注册'];
        }
        $validate = new \app\index\validate\User;
        if (!$validate->check($regInfomation)) {
            return [false,$validate->getError()];
        }
        if(!empty(session('openid')))
        {
            $regInfomation['qqconnectId'] = session('openid');
        }

        if (session('regCode') === $regInfomation['code']) {

            $avatar = new MDAvatars($regInfomation['username'], 512);//生成个性头像
            $file = '/avatar/'.md5($regInfomation['username']).'512.png';//保存头像到/avatar/
            $avatar->Save($file);
            $regInfomation['avatar'] = $file;//写入头像url信息

            $user = user::create($regInfomation);
            session('regMail', null);
            session('regCode', null);
            return [true];
        } else {
            return [false,'邮箱验证码错误'];
        }
    }

    public static function allowCreate($uid, $fid)
    {
        $data = Db::name('forum')->where('fid', $fid)->find();
        $user = user::get($uid);
        $res = strpos($data['cgroup'].',', $user->gid.',') || $data['cgroup'].',' == $user->gid.',';
        return $res;
    }
}
