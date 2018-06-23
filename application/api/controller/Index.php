<?php
namespace app\api\controller;

use app\index\controller\Base;
use think\Db;

class Index extends Base
{
    public function index()
    {
        return json(['code'=>0,'message'=>'Welcome to MlTree Forum']);
    }
}
