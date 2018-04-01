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

    public function api($page=2,$t=1)
    {
        $max = Option::getValue('forumNum');
        switch ($t) {
            case 2:
                $topicData = topic::page('topic')->where('essence',1)->page($page,$max)->order('create_Time DESC')->select();
                $count = topic::page('topic')->where('essence',1)->count('tid');
                $pages = ceil($count / $max);
                break;
            
            default:
                $topicData = topic::page($page,$max)->order('create_Time DESC')->select();
                $count = topic::count('tid');
                $pages = ceil($count / $max);
                break;
        }
        //数据处理（content等去除标签)
        $user = model('user');
        foreach ($topicData as $key => $value) {
            $value['content'] = strip_tags($value['content']);
            $value['time_format'] = time_format($value['create_time']);
            $value['userData'] = $user->where('uid',$value['uid'])->field('username,avatar')->find();
            $value['Badge'] = outBadge($value);
        }
        return json(['code'=>'0','data'=>$topicData,'pages'=>$pages]);
        
    }
}