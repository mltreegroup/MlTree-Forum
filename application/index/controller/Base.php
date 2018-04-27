<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use auth\auth;

class Base extends Controller
{
    protected $beforeActionList = [
        'beforeLogin' => ['only' => 'upload,avatarUpload,picUpload'],
    ];

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
        if(empty(session('uid')))
        {
            return json(['code'=>'1000','message'=>'错误，尚未登录','time'=>time()]);
        }
    }
}
