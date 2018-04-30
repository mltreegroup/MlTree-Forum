<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User;
use think\Db;

class Index extends Base
{
    public function index()
    {
        if(!empty(session('uid')))
        {
            $user = model('user');
            $this->assign('userData',user::get(session('uid')));
        }
        //输出置顶帖子
        $topic = Db::name('topic')->where('tops','in','1')->order('create_time DESC')->select();

        foreach ($topic as $key => $value) {
            $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
            $value['time_format'] = time_format($value['create_time']);
            $value['userData'] =Db::name('user')->where('uid',$value['uid'])->field('username,avatar')->find();
            $topic[$key] = $value;
        }
        $this->assign('tops',$topic);
        $this->assign('option',$this->siteOption());
        return view();
    }

}
