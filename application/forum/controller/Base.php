<?php
namespace app\forum\controller;

use app\common\controller\Base as BaseController;
use app\common\model\Message;
use app\common\model\Option;
use app\common\model\User;
use Auth\Auth;
use think\Db;

class Base extends BaseController
{
    /**
     * Base控制器初始化方法，用于渲染 link user option msg 等数据，同时判断是否有访问权限
     */
    protected function initialize()
    {
        if(!isInstall()){
            return $this->redirect('install\index\index');
        }
        $data = Db::name('links')->order('sold')->select();
        if (User::isLogin(cookie('userKey'))) {
            $user = new User;
            $this->assign('memberData', $user->getInfor(\session('uid')));
        }
        $forumList = Db::name('forum')->field('fid,name,topics,introduce')->select();
        // $msgObj = new Message;
        // $msg = $msgObj->getMessageList(session('uid'), 0);
        // $this->assign('msg', ['unread' => count($msg['data'])]);

        $this->assign('site', Option::getValues('base'));
        $this->assign('links', $data);
        $this->assign('forumList',$forumList);
        $auth = new Auth();
        if (request()->action() != 'login' && request()->action() != '_error') {
            if (Option::getValue('siteStatus') != 1 && !$auth->check('admin', session('uid'))) {
                return $this->error('站点正在维护，请稍后……', 'forum/user/login');
            }
        }
    }
}
