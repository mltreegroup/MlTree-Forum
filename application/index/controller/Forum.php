<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\Db;

class Forum extends Base
{
    public function index($fid = 1)
    {
        $data = Db::name('forum')->where('fid',$fid)->find();
        $option = [
            'siteDes' => $data['seoDes'],
            'siteKeywords' => $data['seoKeywords'],
        ];
        $option = $this->siteOption($data['name'],$option);
        !empty($data['notice']) ? $option['notice'] = $data['notice'] : $option['notice'];
        $this->assign('fid', $fid);
        $this->assign('option', $option);
        return view();
    }
}
