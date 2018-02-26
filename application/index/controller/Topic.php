<?php
namespace app\index\controller;

use think\Db;
use app\index\controller\Base;
use app\index\model\Topic as topicModel;
use app\index\model\Atta;
use app\index\model\User;

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
        $user = model('user');
        return view('index',[
            'option' => $this->siteOption($topic->subject),
            'topicData' => $topic,
            'userData' => $user->getInfo($topic->uid),
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
                Atta::setCreate($tid,input('post.files'));

                return json(['code'=>'1','message'=>'发布成功，正在跳转……','url'=>url('index/topic/index',['tid'=>$tid])]);
            }
        }

        $forumData = Db::name('forum')->field('fid,name')->select();
        return view('create',[
            'option' => $this->siteOption('发帖'),
            'userData' => $user->getInfo(session('uid')),
            'forum' => $forumData,
        ]);
    }
}