<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\index\model\Option;
use app\index\model\Comment;
use think\Db;
use Auth\Auth;

class Api extends Base
{
    public function index()
    {
        return ;
    }

    public function Search($keyword = '',$type = 'topic')
    {
        switch ($type) {
            case 'topic':
                $data = Db::name('topic')->where('subject,content','like','%'.$keyword.'%')->select();
                foreach ($data as $key => $value) {
                    $user = \app\index\model\User::get($value['uid']);
                    $value['username'] = $user->username;
                }
                return json(['code'=>'2020','data'=>$data,'time'=>time()]);
                break;
            
            default:
                return ;
                break;
        }
    }

    public function del($type = 'topic',$id=0,$uid=0)
    {
        if (empty($uid)) {
            return json(['code'=>'1000','message'=>'尚未登录','time'=>time()]);
        }
        switch ($type) {
            case 'topic':
                $auth = new Auth;
                if($auth->check('delete',$uid) || $auth->check('admin',$uid))
                {
                    $res = Db::transaction(function () {
                        Db::name('topic')->find($id);
                        Db::name('topic')->delete($id);
                    });
                    if($res)
                    {
                        return json(['code'=>'3003','message'=>'删除成功','time'=>time()]);
                    }else{
                        return json(['code'=>'3004','message'=>'删除失败','time'=>time()]);
                    }
                }
                break;
            
            default:
                # code...
                break;
        }
    }

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
        return json(['code'=>'3001','data'=>$comment,'pages'=>$pages]);
        
    }

    public function commentConent($cid)
    {
        $comment = Comment::get($cid);
        $comment->userData = Db::name('user')->where('uid',$comment->uid)->field('username')->find();
        return json(['code'=>'3001','message'=>$comment,'time'=>time()]);
    }

}
