<?php
namespace app\index\controller;

use think\Db;
use app\index\controller\Base;
use app\index\model\Topic;
use app\index\model\Comment;
use app\index\model\Option;

class Forum extends Base
{
    public function index($fid = 1)
    {
        $data = Db::name('topic')->where('fid',$fid)->count();
        $this->assign('fid', $fid);
        $this->assign('option', $this->siteOption());

        return view();
    }
}
