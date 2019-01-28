<?php
namespace app\forum\controller;

use app\forum\controller\Base;

use app\common\model\Common;

class Index extends Base
{
    public function index()
    {
        // for ($i = 0; $i < 10000; $i++) {
        //     Comment::create([
        //         'tid' => '3968',
        //         'uid' => 1,
        //         'content' => $i,
        //     ]);
        // }
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
