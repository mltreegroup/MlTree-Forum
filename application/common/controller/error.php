<?php
namespace app\common\controller;

use think\Controller;
use app\common\model\Option;

class Error extends Controller
{
    public function __construct()
    {
		$option = Option::getValues();
		$option['siteTitle'] = '出现错误 - '.$option['siteTitle'];
        $this->assign('option',$option);
    }

    public function index()
    {
        return view('error', [
            'error' => [
                'title' => '出错了',
                'content' => '页面已经无法找到了，估计已经飞到火星~'
            ]
        ]);
    }
}
