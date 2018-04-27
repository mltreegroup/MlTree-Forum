<?php
namespace app\index\controller;

use think\Db;
use app\index\controller\Base;
use app\index\model\Topic as topicModel;
use app\index\model\Atta;
use app\index\model\User;
use app\index\model\Group;
use app\index\model\Comment;
use app\index\model\Option;
use Auth\Auth;

class Topic extends Base
{
    protected function initialize()
    {
        if(!empty(session('uid')))
        {
            $this->assign('userData',user::get(session('uid')));
        }
    }

    public function index($tid = 0)
    {
        $user = new User;
        if($tid == 0)
        {
            return \redirect('index\index\index');
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

        if($auth->check('update',$uid) || $auth->check('admin',$uid))
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
            ]);
        }else{
            return $this->error('无权限');
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

    public function set($type,$tid)
    {
        $auth = new Auth;
        $uid = session('uid');
        if($type === 'top')//置顶操作
        {
            if($auth->check('top',$uid) || $auth->check('admin',$uid))
            {
                $topic = topicModel::get($tid);
                $topic->tops = 1;
                $topic->save();
                return $this->success('置顶成功');
            }else{
                return $this->error('无权限');
            }

            if($auth->check('essence',$uid) || $auth->check('admin',$uid))
            {
                $topic = topicModel::get($tid);
                $topic->essence = 1;
                $topic->save();

                //增加精华帖子数
                $topic = topicModel::get($tid);
                $user = user::get($topic->uid);
                $user->essence = $user->essence + 1;
                $user->save();

                return $this->success('设置精华成功');
            }else{
                return $this->error('无权限');
            }

            if($auth->check('delete',$uid) || $auth->check('admin',$uid))
            {
                $topic = topicModel::get($tid);

                //删除用户帖子数
                $user = user::get($topic->uid);
                $user->topics = $user->topics - 1;
                $user->save();

                //执行删除动作
                $topic->delete();

                return $this->success('删除成功');
            }else{
                return $this->error('无权限');
            }
        }
    }
    

}