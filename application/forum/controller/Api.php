<?php
namespace app\forum\controller;

use app\common\model\Comment;
use app\common\model\Topic;
use app\common\model\User;
use app\forum\controller\Base;

class Api extends Base
{
    public function index()
    {
        return json(['code' => 0, 'apilist' => '', 'time' => time()]);
    }

    public function getTopicList()
    {
        $topic = new Topic;
        if (\request()->isPost()) {
            $data = $topic->TopicList(input('post.page'), input('post.type'));
            return outRes(0, ['data' => $data[0], 'pages' => $data[1]], null, 'data');
        }
        $data = $topic->TopicList(1, 'common');
        return outRes(0, ['data' => $data[0], 'pages' => $data[1]], null, 'data');
    }

    public function getTopicData($tid)
    {
        $topic = new topic;
        $res = $topic->getTopic($tid);

        if (!$res[0]) {
            return outRes(100, $res[1], 0, 'data');
        }
        return outRes(0, $res[1], null, 'data');
    }

    public function getCommentList($tid = 0, $page = 1)
    {
        $comment = new Comment;
        if ($tid == 0) {
            return outRes(0, 'NULL', null, 'data');
        }
        $data = $comment->ListPage($tid, $page);
        if ($data[0]) {
            return outRes(0, ['data' => $data[1], 'pages' => $data[2]], null, 'data');
        }
        return outRes(0, 'NULL', null, 'data');
    }

    public function auth()
    {

    }

    public function postComment()
    {
        if (!User::isLogin()) {
            return outRes(10201,'无权限或未登录');
        }
    }
}
