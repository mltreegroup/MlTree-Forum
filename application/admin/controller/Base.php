<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\User;
use Auth\Auth;

class Base extends Controller
{
    protected function initialize()
    {
        $uid = session('uid');
        if (!User::isLogin()) {
            return $this->error('无权限！', url('forum/index/index'));
        }
        $auth = new auth();
        if (empty($uid)) {
            return $this->error('无权限！', url('forum/index/index'));
        } elseif (!$auth->check('admin', $uid)) {
            return $this->error('无权限！', url('forum/index/index'));
        }
    }

    public function adminView($url = '', $data = [])
    {
        $this->assign($data);
        return view(config('template.view_path') . $url . '.html');
    }
}
