<?php
namespace app\forum\controller;

use app\common\model\Common;
use app\forum\controller\Base;

class Index extends Base
{
    public function index()
    {
        \app\common\hook\Plugin::call('index', $this);
        return $this->mtfView('forum/index');
    }

    public function Forum($fid = 0)
    {
        if ($fid == 0) {
            return \redirect(url('forum/index/index'));
        }
        $this->assign('fid', $fid);
        \app\common\hook\Plugin::call('forumIndex', $this);
        return $this->mtfView('forum/forum');
    }

    public function Search($kw = '', $tp = 'topic')
    {
        $kwa = [$kw];
        \app\common\hook\Plugin::call('search', $kwa);
        $data = Common::Serach($kw);
        return $this->mtfView('public/serach', '搜索：' . $kw, ['data' => $data, 'kw' => $kw]);
    }

    public function test()
    {
        return $this->mtfView('forum/test');
    }
}
