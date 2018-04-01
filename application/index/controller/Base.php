<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use auth\auth;

class Base extends Controller
{
    protected $beforeActionList = [
        
    ];

    

    public function siteOption($titleSign = null)
    {
        $siteData = \app\index\model\Option::getValues(['base']);
        if (!empty($titleSign)) {
            $siteData['siteTitle'] = $titleSign.' - '.$siteData['siteTitle'];
        }
        return $siteData;
    }
}
