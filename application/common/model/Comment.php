<?php
namespace app\common\model;

use think\Db;
use think\Model;

class Comment extends Model
{
    protected $pk = 'cid';
    protected $autoWriteTimestamp = true;

    public function setContentAttr($val)
    {
        $res = replyRegular($val);
        $val = str_replace('{' . $res[0] . '}', $res[3], $val);
        return $val;
    }

    /**
     * 获取指定tid下的评论列表
     * @param [int] $tid [require|主题id]
     * @param [int] $page [require|页码]
     * @param [int] $number [require|每页显示条数]
     * @return [array] [$comment,$pages] [评论内容及总页数]
     */
    public function ListPage($tid, $page, $number = 0)
    {
        $number == 0 ? $number = Option::getValue('commentNum') : $number;
        $comment = Comment::page('comment')->where('tid', $tid)->page($page, $number)->select();
        if (empty($comment)) {
            return [false, 'NULL'];
        }
        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid', $value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];
            !empty($data['motto']) ? $value['motto'] = $data['motto'] : $value;
            $value['time_format'] = time_format($value['create_time']);
        }
        $count = Comment::page('comment')->where('tid', $tid)->count('cid');
        $pages = ceil($count / $number);
        return [true, $comment, $pages];
    }

    public function add($info)
    {
        if (!User::isLogin()) {
            return outRes(102001, '无权限或未登录');
        }
        $uid = session('uid');
        if ($info['tid'] == 0) {
            return outRes(102002, '参数不正确');
        }
        $topic = Topic::get($info['tid']);
        if (empty($topic)) {
            return outRes(102003, '帖子不存在');
        } elseif ($topic->closed == 1 && !fastAuth('admin', $uid)) {
            return outRes(102004, '帖子已经关闭');
        }

        $info['uid'] = $uid;
        $comment = Comment::create($info);
        $topic->comment += 1;
        $topic->save();

        //修改用户信息
        $user = User::get($uid);
        $user->comments += 1;
        $user->save();

        //发送通知
        $msg = new Message;
        if (input('post.recid') != 0) {
            $msg->addReplyMsg($comment->cid);
        }
        $msg->addCommentMsg($comment->cid);

        return outRes(0, '评论成功', url('forum/topic/index#mtf-commentid-' . $comment->cid, ['tid' => $info['tid']]));
    }
}
