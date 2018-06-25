<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
use Auth\Auth;
use app\index\model\Option;

class Base extends Controller
{
    protected $beforeActionList = [
        
    ];

    protected function initialize()
    {
        $auth = new Auth();
        if (request()->action() != 'login') {
            if (Option::getValue('siteStatus') != 1 && !$auth->check('admin', session('uid'))) {
                return json(['code'=>'-1','message'=>'站点正在维护，请稍后……','time'=>time()]);
            }
        }
    }

    public function siteOption($titleSign = null)
    {
        $siteData = Option::getValues(['base']);
        if (!empty($titleSign)) {
            $siteData['siteTitle'] = $titleSign.' - '.$siteData['siteTitle'];
        }
        return $siteData;
    }
}
