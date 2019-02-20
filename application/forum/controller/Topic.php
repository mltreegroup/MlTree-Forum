<?php
namespace app\forum\controller;

use app\common\model\Message;
use app\common\model\Topic as TopicModel;
use app\common\model\User;
use app\forum\controller\Base;
use Auth\Auth;
use think\Db;

class Topic extends Base
{
    public function index($tid = 0)
    {
        if ($tid == 0) {
            return redirect(url('forum\index\index'));
        }
        \app\common\hook\Plugin::call('topicIndex', $tid);
        $topic = new TopicModel;
        $res = $topic->getTopic($tid);
        if (!$res[0]) {
            return $this->error($res[1], 'forum\index\index');
        }

        $this->assign('topicData', $res[1]);

        return $this->mtfView('topic/index', $res[1]['subject']);
    }

    public function create()
    {
        if (!User::isLogin()) {
            return redirect(url('forum/user/login'));
        }
        if (request()->isPost()) {
            $topic = new TopicModel;
            $res = $topic->found(session('uid'), input('post.', '', 'htmlspecialchars'));
            if ($res[0]) {
                return outRes(0, '发布成功，正在前往话题页', url('forum/topic/index', ['tid' => $res[1]]));
            } else {
                return outRes(-1, $res[1]);
            }
        }
        $forumData = Db::name('forum')->field('fid,name,cgroup')->select();
        return $this->mtfView('topic/create', '创建话题', [
            'forum' => $forumData,
            'attaSign' => createStr(30),
        ]);
    }

    public function editor($tid = 0)
    {
        $uid = session('uid');
        if ($uid == 0 && $tid == 0) {
            return redirect(url('forum\index\index'));
        } elseif (!User::isLogin()) {
            return $this->error('请先登录。', 'forum/user/login');
        }

        //查询帖子信息
        $topic = TopicModel::get($tid);
        $forumData = Db::name('forum')->field('fid,name')->select();
        //查询用户信息
        $user = user::get($uid);
        //判断是否拥有权限
        $auth = new auth;
        if ($auth->check('update', $uid) && $topic->uid == $uid || $auth->check('admin', $uid)) {
            if (!empty(input('post.'))) {
                $res = $this->validate(input('post.'), 'app\common\validate\Topic.create');
                if ($res !== true) {
                    return \outRes(-1, $res);
                } else {
                    $topic = new TopicModel;
                    $data = [
                        'subject' => input('post.subject'),
                        'content' => input('post.content'),
                    ];
                    $topic->update($data, ['tid' => $tid]);
                    return outRes(0, '发布成功，正在跳转...', url('forum/topic/index', ['tid' => $tid]));
                }
            }
            return $this->mtfView('topic/editor', '编辑', [
                'topicData' => $topic,
                'forum' => $forumData,
            ]);
        } else {
            return $this->error('无权限');
        }
    }

    public function set()
    {
        if (!fastAuth('admin', session('uid'))) {
            return outRes(0, '无权限');
        }

        $type = input('post.type');
        $tid = input('post.tid');
        $msg = input('?post.msg');

        $topic = TopicModel::get($tid);
        $msgObj = new Message;

        if ($type == 'top') {
            if (!empty($topic)) {
                $resMsg = '已取消置顶';
                $topic->tops == 1 ? $topic->tops = 0 : $topic->tops = 1;
                $topic->isAutoWriteTimestamp(false)->save();
                if ($topic->tops == 1 && $msg) {
                    $msgObj->addTopMsg($tid);
                    $resMsg = '已设置为置顶';
                }
                return outRes(0, $resMsg);
            }
        } elseif ($type == 'essence') {
            if (!empty($topic)) {
                $resMsg = '已取消精华';
                $topic->essence == 1 ? $topic->essence = 0 : $topic->essence = 1;
                $topic->isAutoWriteTimestamp(false)->save();
                if ($topic->essence == 1 && $msg) {
                    $msgObj->addEssenceMsg($tid);
                    $resMsg = '已设置为精华';
                }
                return outRes(0, $resMsg);
            }
        } elseif ($type == 'closed') {
            if (!empty($topic)) {
                $topic->closed == 1 ? $topic->closed = 0 : $topic->closed = 1;
                $topic->isAutoWriteTimestamp(false)->save();
                if ($topic->closed == 1) {
                    $resMsg = '已关闭主题';
                } else {
                    $resMsg = '已开启主题';
                }
                return outRes(0, $resMsg);
            }
        }
    }
}
