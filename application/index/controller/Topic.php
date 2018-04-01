<?php
namespace app\index\controller;

use think\Db;
use app\index\controller\Base;
use app\index\model\Topic as topicModel;
use app\index\model\Atta;
use app\index\model\User;
use app\index\model\Group;
use app\index\model\Comment;
use Auth\Auth;

class Topic extends Base
{
    public function index($tid = 0)
    {
        $user = new User;
        if($tid == 0)
        {
            return \redirect('index\index\index');
        }elseif (!empty(session('uid'))) {
            $this->assign('userData',$user->getInfo(session('uid')));
        }
        //获取帖子信息
        $topic = topicModel::get($tid);
        if(empty($topic))
        {
            return $this->error('暂未找到此内容！','index/index/index');
        }
        $topicUser = $user::get($topic->uid);

        $comment = $topic->comments;

        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid',$value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];

        }

        return view('index',[
            'option' => $this->siteOption($topic->subject),
            'topicData' => $topic,
            'topicUser' => $topicUser,
            'commentData' => $comment,
        ]);
    }
    
    public function create()
    {
        if(empty(session('uid')))
        {
            return redirect('index\user\login');
        }

        $user = model('user');//引入user模型后面调用

        if(!empty(input('post.')))
        {
            $res = $this->validate(input('post.'),'app\index\validate\Topic.create');
            if($res !== true)
            {
                return ['code'=>'-1','message'=>$res];
            }else {
                $topic = new topicModel;
                $data = [
                    'subject' => input('post.title'),
                    'content' => input('post.content','','htmlspecialchars'),
                    'fid' => (int)input('post.fid'),
                    'uid' => session('uid'),
                    'userip' => '',//在model中自动写入
                ];
                $topic->save($data);
                $tid = $topic->tid;

                //设置附件信息
                $re = Atta::setCreate($tid,input('post.files'));
                //set user topics
                $user = User::get(session('uid'));
                $user->topics = $user->topics + 1;
                $user->save();

                return json(['code'=>'1','message'=>'发布成功，正在跳转……','url'=>url('index/topic/index',['tid'=>$tid])]);
            }
        }

        $forumData = \think\Db::name('forum')->field('fid,name')->select();
        return view('create',[
            'option' => $this->siteOption('发帖'),
            'userData' => $user->getInfo(session('uid')),
            'forum' => $forumData,
        ]);
    }

    public function update($uid = 0,$tid = 0)
    {
        if($uid == 0)
        {
            $uid = session('uid');
            }
        if($uid == 0 || $tid == 0)
        {
            return redirect('index\index\index');
        }elseif(empty(session('uid'))){
            return $this->error('请先登录。','index/user/login');
            }



        //查询帖子信息
        $topic = topicModel::get($tid);
        $forumData = \think\Db::name('forum')->field('fid,name')->select();
        //查询用户信息
        $user = user::get($uid);
        //判断是否拥有权限
        $auth = new auth;

        if($auth->check('update',$uid))
        {
            if(!empty(input('post.')))
            {
                $res = $this->validate(input('post.'),'app\index\validate\Topic.create');
            if($res !== true)
            {
                return ['code'=>'-1','message'=>$res];
            }else {
                $topic = new topicModel;
                $data = [
                    'subject' => input('post.title'),
                    'content' => input('post.content','','htmlspecialchars'),
                ];
                $topic->save($data,['tid'=>$tid]);

                //设置附件信息
                $re = Atta::setCreate($tid,input('post.files'));

                return json(['code'=>'1','message'=>'发布成功，正在跳转……','url'=>url('index/topic/index',['tid'=>$tid])]);
                }
        }
            return view('update',[
                'topicData'=> $topic,
                'forum' => $forumData,
                'option' => $this->siteOption('编辑 - '.$topic->subject),
                'userData' => $user->getInfo($uid),
            ]);
        }else{
            return '没有更新权限';
        }
    }

    public function comment($tid = 0)
    {
        if(empty(session('uid')))
        {
            return ['code'=>0,'message'=>'未登录','url'=>url('index/user/login')];
        }
        if($tid == 0)
        {
            return;
        }

        $uid = session('uid');
        if(!empty(input('post.')))
        {
            $data = [
                'tid' => $tid,
                'uid' => $uid,
                'content' => input('post.content'),
            ];

            $topic = topicModel::get($tid);
            $topic->comments()->save($data);
            $topic->comment = $topic->comment + 1;
            $topic->save();

            //修改用户信息
            $user = user::get($uid);
            $user->comments = $user->comments + 1;
            $user->save();

            return ['code'=>1,'message'=>'回复成功！'];
        }
    }

}