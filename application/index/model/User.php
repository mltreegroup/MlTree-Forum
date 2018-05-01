<?php
namespace app\index\model;

use think\Model;
use think\Db;


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
    protected function setPasswordAttr($val,$data)
    {
        return password_encode($data['password']);
    }
    

    static function checkCaptcha($code)
    {
		if(!captcha_check($code)){
		 	return false;
		}
		return true;
    }
    
}

