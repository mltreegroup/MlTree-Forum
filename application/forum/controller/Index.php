<?php
namespace app\forum\controller;

use app\forum\controller\Base;

use app\common\model\Comment;

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

    public function test($page=0)
    {
        $topic = new Topic;
        $res = Topic::page($page,10)->order(['tid'=>'desc','create_time'=>'desc'])->select();
        dump($res);
        //return json($res);
    }   
}
