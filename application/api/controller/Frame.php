<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
use app\index\model\Option;
use Auth\Auth;

class Frame extends Controller
{
    public function initialize()
    {
        $auth = new Auth();
        if (request()->action() != 'login') {
            if (Option::getValue('siteStatus') != 1 && !$auth->check('admin', session('uid'))) {
                return json(['code'=>'-1','message'=>'站点正在维护，请稍后……','time'=>time()]);
            }
        }
        if(empty(session('uid')))
        {
            return json(['code'=>'-1','message'=>'非法操作']);
        }
        $this->assign('theme', Option::getValues('theme'));
    }

    public function siteOption($titleSign = null)
    {
        $siteData = Option::getValues(['base']);
        if (!empty($titleSign)) {
            $siteData['siteTitle'] = $titleSign.' - '.$siteData['siteTitle'];
        }
        return $siteData;
    }

    public function index()
    {
        return json(['code'=>0,'message'=>'Welcome to MlTree Forum']);
    }

    public function topicFrame()
    {
        //Option::getValues('theme');
        
        return view('topic');
    }
}
