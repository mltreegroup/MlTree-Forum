<?php
namespace app\index\controller;

use app\index\model\Option;
use think\Controller;
use think\Db;
use Auth\Auth;

class Base extends Controller
{
    protected $beforeActionList = [
        'beforeLogin' => ['only' => 'upload,avatarUpload,picUpload'],
    ];

    protected function initialize()
    {
        $data = Db::name('links')->order('sold')->select();
        if (!empty(session('uid'))) {
            $this->assign('userData', Db::name('user')->where('uid', session('uid'))->find());
        }
        $this->assign('option', Option::getValues('base'));
        $this->assign('theme', Option::getValues('theme'));
        $this->assign('links', $data);
        $auth = new Auth();
        if (request()->action() != 'login' && request()->action() != '_error') {
            if (Option::getValue('siteStatus') != '1' && !$auth->check('admin', session('uid'))) {
                
                return $this->redirect('index/index/_error');
                return $this->error('站点正在维护，请稍后……','index/user/login');
            }
        }
    }

    public function siteOption($titleSign = null)
    {
        $siteData = \app\index\model\Option::getValues(['base']);
        if (!empty($titleSign)) {
            $siteData['siteTitle'] = $titleSign.' - '.$siteData['siteTitle'];
        }
        return $siteData;
    }

    public function beforeLogin()
    {
        if (empty(session('uid'))) {
            return json(['code'=>'1000','message'=>'错误，尚未登录','time'=>time()]);
        }
    }
}
