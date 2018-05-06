<?php
namespace app\index\controller;

use think\Db;
use app\index\controller\Base;
use app\index\model\Topic;
use app\index\model\Comment;
use app\index\model\Option;

class Forum extends Base
{
    public function forumlist()
    {
        $topicData = topic::limit(Option::getValue('forumNum'))->order('create_Time DESC')->toJson();
        return $topicData;
    }

    public function getEssen()
    {
        $topicData = topic::where('essence',1)->limit(Option::getValue('forumNum'))->order('create_Time DESC')->toJson();
        return $topicData;
    }

    
}