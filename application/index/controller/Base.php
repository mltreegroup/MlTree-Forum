<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Base extends Controller
{ 
    protected $beforeActionList = [
        'userStatus'  =>  ['only'=>'create,set'],
    ];

    public function userStatus()
    {
        $uid = session('uid');
        if(empty($uid))
        {
            return redirect('index\user\login');
        }
    }
}
