<?php
namespace app\index\controller;

use app\index\controller\Base;

class Index extends Base
{
    public function index()
    {
        if(!empty(session('uid')))
        {
            $user = model('user');
            $this->assign('userData',$user->getInfo(session('uid')));
        }
        // dump($this->siteOption());
        $this->assign('option',$this->siteOption());
        return view();
    }

}
