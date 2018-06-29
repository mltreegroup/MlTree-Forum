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
use app\common\model\Message;

class Topic extends Base
{
    public function index($tid)
    {
        if ($tid == 0) {
            return \redirect('index/index/idnex');
        }
        $topic = topicModel::get($tid);
        if (empty($topic)) {
            return $this->error('暂未找到此内容！', 'index/index/index');
        }
        $topic->views += 1;
        $topic->isAutoWriteTimestamp(false)->save();
        $topic->update_time = date('Y-m-d H:i:s', $topic->update_time);
        $topicUser = user::get($topic->uid);
        $comment = $topic->comments;
        $atta = Atta::where('sign', $topic->sign)->select();
        count($atta) == 0 ? $atta = null : $atta;//解决ThinkPHP模型奇怪的array()不为空的问题
        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid', $value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];
        }
        return view('index', [
            'option' => $this->siteOption($topic->subject),
            'topicData' => $topic,
            'topicUser' => $topicUser,
            'commentData' => $comment,
            'attaList' => $atta,
        ]);
    }

    public function create()
    {
        if (!User::isLogin()) {
            return redirect('index\user\login');
        }
        if (request()->isPost()) {
            $topic = new TopicModel;
            $res = $topic->found(session('uid'), input('post.', '', 'htmlspecialchars'));
            if ($res[0]) {
                return json([
                'code'=>'1',
                'message'=>'发布成功，正在跳转……',
                'url'=>url('index/topic/index', ['tid'=>$res[1]]),
                ]);
            } else {
                return json(\outResult(-1, $res[1]));
            }
        }

        $forumData = Db::name('forum')->field('fid,name,cgroup')->select();
        
        return view('create', [
            'option' => $this->siteOption('发帖'),
            'forum' => $forumData,
            'attaSign' => createStr(30),
        ]);
    }

    public function update($uid = 0, $tid = 0)
    {
        if ($uid == 0) {
            $uid = session('uid');
        }
        if ($uid == 0 || $tid == 0) {
            return redirect('index\index\index');
        } elseif (!User::isLogin()) {
            return $this->error('请先登录。', 'index/user/login');
        }

        //查询帖子信息
        $topic = topicModel::get($tid);
        $forumData = \think\Db::name('forum')->field('fid,name')->select();
        //查询用户信息
        $user = user::get($uid);
        //判断是否拥有权限
        $auth = new auth;

        if ($auth->check('update', $uid) || $auth->check('admin', $uid)) {
            if (!empty(input('post.'))) {
                $res = $this->validate(input('post.'), 'app\index\validate\Topic.create');
                if ($res !== true) {
                    return ['code'=>'-1','message'=>$res];
                } else {
                    $topic = new topicModel;
                    $data = [
                    'subject' => input('post.subject', '', 'htmlspecialchars'),
                    'content' => input('post.content', '', 'htmlspecialchars'),
                ];
                    $topic->update($data, ['tid'=>$tid]);
                    return json(['code'=>'1','message'=>'发布成功，正在跳转……','url'=>url('index/topic/index', ['tid'=>$tid])]);
                }
            }
            return view('update', [
                'topicData'=> $topic,
                'forum' => $forumData,
                'option' => $this->siteOption('编辑 - '.$topic->subject),
            ]);
        } else {
            return $this->error('无权限');
        }
    }

    public function comment($tid = 0)
    {
        if (!User::isLogin()) {
            return json(['code'=>0,'message'=>'未登录','url'=>url('index/user/login')]);
        }
        if ($tid == 0) {
            return json(\outResult(-1, '非法操作'));
        }

        $uid = session('uid');
        if (!empty(input('post.'))) {
            $data = [
                'tid' => $tid,
                'uid' => $uid,
                'content' => input('post.content'),
            ];
            !empty(input('post.recid')) ? $data['reCid'] = input('post.recid') : $data;

            $topic = topicModel::get($tid);
            if ($topic->closed == 1) {
                return ['code'=>-1,'message'=>'Topic已被关闭，禁止回复'];
            }
            $res = $topic->comments()->save($data);
            $topic->comment += 1;
            $topic->save();

            //修改用户信息
            $user = user::get($uid);
            $user->comments += 1;
            $user->save();

            //发送通知
            $msg = new Message;
            if (input('post.recid') != 0) {
                $msg->addReplyMsg($res->cid);
            }
            $msg->addCommentMsg($res->cid);
            
            return ['code'=>1,'message'=>'回复成功！'];
        }
    }

    public function set($type, $tid)
    {
        $topic = topicModel::get($tid);
        $msgObj = new Message;
        $msg = $msgObj->getMessageList(session('uid'), 0);
        
        $this->assign('msg', ['unread'=>count($msg['data'])]);
        if ($type == 'top') {
            if (!empty($topic)) {
                $topic->tops == 1 ? $topic->tops = 0 : $topic->tops = 1 ;
                $topic->isAutoWriteTimestamp(false)->save();
                if ($topic->tops == 1) {
                    $msg = new Message;
                    $msg->addTopMsg($tid);
                }
                return $this->success('设置为置顶成功');
            }
        } elseif ($type == 'essence') {
            if (!empty($topic)) {
                $topic->essence == 1 ? $topic->essence = 0 : $topic->essence = 1 ;
                $topic->isAutoWriteTimestamp(false)->save();
                if ($topic->essence == 1) {
                    $msg = new Message;
                    $msg->addEssenceMsg($tid);
                }
                return $this->success('设置为精华成功');
            }
        } elseif ($type == 'close') {
            if (!empty($topic)) {
                $topic->closed == 1 ? $topic->closed = 0 : $topic->closed = 1 ;
                $topic->isAutoWriteTimestamp(false)->save();
                return $this->success('设置为关闭成功');
            }
        }
    }
}
