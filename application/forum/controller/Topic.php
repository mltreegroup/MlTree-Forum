<?php
namespace app\forum\controller;

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
            return \redirect('forum\index\index');
        }

        $topic = new topicModel;
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

    public function editor($uid = 0, $tid = 0)
    {
        if ($uid == 0) {
            $uid = session('uid');
        }
        if ($uid == 0 || $tid == 0) {
            return redirect('forum\index\index');
        } elseif (!User::isLogin()) {
            return $this->error('请先登录。', 'forum/user/login');
        }

        //查询帖子信息
        $topic = topicModel::get($tid);
        $forumData = Db::name('forum')->field('fid,name')->select();
        //查询用户信息
        $user = user::get($uid);
        //判断是否拥有权限
        $auth = new auth;

        if ($auth->check('update', $uid) || $auth->check('admin', $uid)) {
            if (!empty(input('post.'))) {
                $res = $this->validate(input('post.'), 'app\common\validate\Topic.create');
                if ($res !== true) {
                    return \outRes(-1, $res);
                } else {
                    $topic = new topicModel;
                    $data = [
                        'subject' => input('post.subject', '', 'htmlspecialchars'),
                        'content' => input('post.content', '', 'htmlspecialchars'),
                    ];
                    $topic->update($data, ['tid' => $tid]);
                    return outRes(0, '发布成功，正在跳转...', url('forum/topic/index', ['tid' => $tid]));
                    //return json(['code' => '1', 'message' => '发布成功，正在跳转……', 'url' => url('index/topic/index', ['tid' => $tid])]);
                }
            }
            dump($topic);
            return $this->mtfView('topic/editor', '编辑', [
                'topicData' => $topic,
                'forum' => $forumData,
            ]);
            // return view('update', [
            //     'topicData' => $topic,
            //     'forum' => $forumData,
            //     'option' => $this->siteOption('编辑 - ' . $topic->subject),
            // ]);
        } else {
            return $this->error('无权限');
        }
    }
}
