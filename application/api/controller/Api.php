<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\index\model\Option;
use app\index\model\Comment;
use think\Db;

class Api extends Base
{
    public function index()
    {
        
    }

<<<<<<< HEAD
    public function Search($keyword = '',$type = 'topic')
    {
        switch ($type) {
            case 'topic':
                $data = Db::name('topic')->where('subject,content','like','%'.$keyword.'%')->select();
                foreach ($data as $key => $value) {
                    $user = \app\index\model\User::get($value['uid']);
                    $value['username'] = $user->username;
                }
                return json(['code'=>'1','data'=>$data,'time'=>time()]);
                break;
            
            default:
                return ;
                break;
        }
    }

=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    public function commentList($tid,$page=1)
    {
        $max = Option::getValue('commentNum');
        $comment = Comment::page('comment')->where('tid',$tid)->page($page,$max)->select();

        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid',$value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];
            $value['time_format'] = time_format($value['create_time']);
        }

        $count = Comment::page('comment')->where('tid',$tid)->count('cid');
        $pages = ceil($count / $max);
        return json(['code'=>'0','data'=>$comment,'pages'=>$pages]);
        
    }

    public function commentConent($cid)
    {
        $comment = Comment::get($cid);
        $comment->userData = Db::name('user')->where('uid',$comment->uid)->field('username')->find();
        return json(['code'=>'3001','message'=>$comment,'time'=>time()]);
    }

}
