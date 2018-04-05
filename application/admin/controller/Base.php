<?php
namespace app\admin\controller;

use think\Controller;
use Auth\Auth;

class Base extends Controller
{
    protected function initialize()
    {
        $auth = new auth();
        if (empty(session('uid'))) {
            return $this->error('无权限！',url('index/index/index'));
        }elseif ($auth->check('admin',session('uid'))) {
            return $this->error('无权限！',url('index/index/index'));
        }
    }
}