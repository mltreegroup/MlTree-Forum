<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Topic;
use app\common\model\Message;

class Index extends Base
{
    public function index()
    {
        // $msg = new Message;
        // $res = $msg->getMessageList(1);
        // dump($res);
        $topic = new Topic();
        $tops = $topic->getTops();
        $this->assign('tops', $tops);
        return view();
    }

    public function Search($keyword = '', $type='topic')
    {
        $this->assign('option', $this->siteOption('搜索 - '.$keyword));
        switch ($type) {
            case 'topic':
                $topic = new Topic();
                $data = $topic->Search($keyword);
                break;
            
            default:
                return ;
                break;
        }

        return view('search', [
            'data' => $data,
            'count' => count($data),
            'keyword' => $keyword,
        ]);
    }

    public function _error()
    {
        $data = [
                    'title' => '站点正在进行闭站维护……',
                    'content' => Option::getValue('closeContent'),
                ];
        $this->assign('information', $data);
        $this->assign('option', $this->siteOption('出现错误'));
        return view('error');
    }
}
