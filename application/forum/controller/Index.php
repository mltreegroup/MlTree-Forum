<?php
namespace app\forum\controller;

use app\common\model\Common;
use app\forum\controller\Base;

class Index extends Base
{
    public function index()
    {
        return $this->mtfView('forum/index');
    }

    public function Search($kw = '', $tp = 'topic')
    {
        $data = Common::Serach($kw);
        return $this->mtfView('public/serach', '搜索：' . $kw, ['data' => $data, 'kw' => $kw]);
    }

    public function test()
    {
        return $this->mtfView('forum/test');
    }
}
