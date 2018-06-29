<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Option;
use think\Db;
use Auth\Auth;
use app\index\model\User;
use app\common\model\Message;

class Base extends Controller
{
	protected function initialize()
    {
        $data = Db::name('links')->order('sold')->select();
        if (User::isLogin(cookie('userKey'))) {
            $this->assign('userData', Db::name('user')->where('uid', session('uid'))->find());
        }

        $msgObj = new Message;
        $msg = $msgObj->getMessageList(session('uid'),0);
        $this->assign('msg',['unread'=>count($msg['data'])]);

        $this->assign('option', Option::getValues('base'));
        $this->assign('theme', Option::getValues('theme'));
        $this->assign('links', $data);
        $auth = new Auth();
        if (request()->action() != 'login' && request()->action() != '_error') {
            if (Option::getValue('siteStatus') != 1 && !$auth->check('admin', session('uid'))) {
                return $this->redirect('index/index/_error');
                return $this->error('站点正在维护，请稍后……','index/user/login');
            }
        }
    }    
    public function siteOption($titleSign = null,$option=[])
    {
        $siteData = Option::getValues(['base']);
        !empty($titleSign) ? $siteData['siteTitle'] = $titleSign.' - '.$siteData['siteTitle'] : $siteData['siteTitle'];
        if (!empty($option)) {
            foreach ($option as $key => $value) {
                if (isset($siteData[$key]) && !empty($value)) {
                    $siteData[$key] = $value;
                }
            }
        }
        return $siteData;
    }
}