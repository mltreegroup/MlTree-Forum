<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\index\model\User;
use app\index\model\Option;
use Auth\Auth;

class Base extends Controller
{
	protected function initialize()
    {
        $uid = session('uid');
        $user = Db::name('user')->where('uid',session('uid'))->find();
        $this->assign('userData',$user);
        $this->assign('option',$this->siteOption('后台管理'));
        $this->assign('theme', Option::getValues('theme'));
        if (!User::isLogin()) {
        return $this->error('无权限！', url('index/index/index'));
        }
        $auth = new auth();
        if (empty($uid)) {
            return $this->error('无权限！',url('index/index/index'));
        }elseif (!$auth->check('admin',$uid)) {
            return $this->error('无权限！',url('index/index/index'));
        }
    }

    public function siteOption($titleSign = null)
    {
        $siteData = Option::getValues(['base']);
        if (!empty($titleSign)) {
            $siteData['siteTitle'] = $titleSign.' - '.$siteData['siteTitle'];
        }
        return $siteData;
    }
}