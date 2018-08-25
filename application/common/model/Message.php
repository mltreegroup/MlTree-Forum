<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\index\model\User;
use Auth\Auth;
use app\index\model\Comment;
use app\index\model\Topic;

class Message extends Model
{
    protected $pk = 'mid';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'time';

    public static function getRe($code, $data)
    {
        $data = [
            'code' => $code,
            'data' => $data,
            'count' => count($data),
            'time' => time(),
        ];
        return $data;
    }

    /**
     * 获取指定toUid的消息列表，返回数组形式
     * @param [int] $toUid
     * @param [int] $status [要求返回消息阅读状态0未读1已读2全部]
     * @return [array] $data ['code'=>code,'data'=>$data,'count'=>count,'time'=>time()]
     */
    public function getMessageList($toUid, $status = 2)
    {
        if ($status != 2) {
            $Message = Db::name('message')
                ->where('toUid', $toUid)
                ->where('status', '0')
                ->order('time', 'desc')
                ->select();
        } else {
            $Message = Db::name('message')
                ->where('toUid', $toUid)
                ->order('time', 'desc')
                ->select();
        }
        return self::getRe(0, $Message);
    }

    /**
     * 增加一条短信息
     * @param [int] $uid [默认为1]
     * @param [int] $toUid [获得者UID]
     * @param [string] $content [消息内容]
     * @return [array] $return [bool,mid]
     */
    public function addMessage($toUid, $uid = 1, $title, $content)
    {
        $auth = new Auth;
        if (!$auth->check('admin,message', $uid, 'and')) {
            return [false, '无权限'];
        }
        if (empty($title) || empty($content)) {
            return [false, '标题或内容不能为空'];
        }
        $user = User::get($uid);

        $data = [
            'uid' => $uid,
            'userName' => $user->username,
            'avatar' => $user->avatar,
            'toUid' => $toUid,
            'title' => $title,
            'content' => $content,
        ];
        $res = Message::create($data);
        return [true, $res->mid];
    }

    public function addAllMessage($toUid = [], $uid = 1, $title, $content)
    {
        $auth = new Auth;
        if (!$auth->check('admin,message', $uid, 'and')) {
            return [false, '无权限'];
        }
        if (empty($title) || empty($content)) {
            return [false, '标题或内容不能为空'];
        }
        $user = User::get($uid);

        foreach ($mid as $key => $value) {
            $res = [
                'uid' => $uid,
                'userName' => $user->username,
                'avatar' => $user->avatar,
                'toUid' => $value['uid'],
                'title' => $title,
                'content' => $content,
            ];
            $data[] = $res;
        }

        $res = Message::saveAll($data);
        return [true, $res->mid];
    }

    /**
     * 将信息标记已读
     * @param [array] $midarray [mid数组]
     * @return [array]
     */
    public function readMessage($midarray)
    {
        if (empty($midarray)) {
            return [false, 'Mid数组不能为空'];
        }
        if ($midarray == 'all') {
            Message::where('toUid', session('uid'))->update(['status' => 1]);
            return [true];
        }
        Message::where('mid', $midarray)->update(['status' => 1]);
        return [true];
    }

    /**
     * 删除指定或全部Message
     * @param [string|int] $mid [可选值：数字|all|read]
     * @return [array] $return
     */
    public function delMessage($mid, $uid = 0)
    {
        if (!user::isLogin()) {
            return [false, '无权限'];
        }
        $auth = new Auth;
        if (!$auth->check('admin,message', $uid, 'and')) {
            return [false, '无权限'];
        }
        if ($mid == 'all') {
            Db::name('message')
                ->where('toUid', $uid)
                ->delete();
            return [true];
        } elseif ($mid == 'read') {
            Db::name('message')
                ->where('toUid', $uid)
                ->where('status', '1')
                ->delete();
            return [true];
        } else {
            Db::name('message')
                ->delete($mid);
            return [true];
        }
    }

    /**
     * 获取指定mid内容
     * @param [int] $mid [Mid]
     * @return [array] $return
     */
    public function getMessage($mid)
    {
        if (empty($mid)) {
            return [false, 'mid不能为空'];
        }
        $res = Message::get($mid);
        if (empty($res)) {
            return [false, 'MID不存在'];
        }
        return [true, $res];
    }

    public function addCommentMsg($cid)
    {
        if (!user::isLogin()) {
            return [false, '无权限'];
        }
        $comment = Comment::get($cid);
        $topic = Topic::get($comment->tid);
        $user = User::get($topic['uid']);
        $auth = new Auth;
        if (!$auth->check('admin,message', 1, 'and')) {
            return [false, '无权限'];
        }
        $reuser = User::get($comment->uid);
        $content = config('mtf.Message.comment');

        $array = [
            '{title}' => $topic->subject,
            '{username}' => $user->username,
            '{reuser}' => $reuser->username,
            '{reuserUrl}' => url('index/user/index', ['uid' => $reuser->uid]),
            '{topicUrl}' => url('index/topic/index#reply-content-' . $cid, ['tid' => $topic->tid]),
        ];
        $content = strtr($content, $array);
        $title = '你的Topic被评论了';
        $this->addMessage($topic->uid, 1, $title, $content);
        return true;
    }

    public function addReplyMsg($cid)
    {
        if (!user::isLogin()) {
            return [false, '无权限'];
        }
        $comment = Comment::get($cid);
        $topic = Topic::get($comment->tid);
        $user = User::get($topic->uid);
        $auth = new Auth;
        if (!$auth->check('admin,message', 1, 'and')) {
            return [false, '无权限'];
        }
        $recomment = Comment::get($comment->reCid);
        $reuser = User::get($recomment->uid);
        $content = config('mtf.Message.reply');
        $array = [
            '{title}' => $topic->subject,
            '{username}' => $user->username,
            '{reuser}' => $reuser->username,
            '{reuserUrl}' => url('index/user/index', ['uid' => $reuser->uid]),
            '{topicUrl}' => url('index/topic/index#reply-content-' . $cid, ['tid' => $topic->tid]),
            '{comment}' => $recomment->content,
        ];
        $content = strtr($content, $array);
        $title = strtr('你在『{title}』的评论被回复了', $array);
        $this->addMessage($recomment->uid, $comment->uid, $title, $content);
        return true;
    }

    public function addTopMsg($tid)
    {
        if (!user::isLogin()) {
            return [false, '无权限'];
        }
        $topic = Topic::get($tid);
        $user = User::get($topic->uid);
        $auth = new Auth;
        if (!$auth->check('admin,message', 1, 'and')) {
            return [false, '无权限'];
        }
        $content = config('mtf.Message.top');
        $array = [
            '{title}' => $topic->subject,
            '{username}' => $user->username,
            '{topicUrl}' => url('index/topic/index', ['tid' => $topic->tid]),
        ];
        $content = strtr($content, $array);
        $title = '你的Topic被设为置顶';
        $this->addMessage($topic->uid, 1, $title, $content);
        return true;
    }

    public function addEssenceMsg($tid)
    {
        if (!user::isLogin()) {
            return [false, '无权限'];
        }
        $topic = Topic::get($tid);
        $user = User::get($topic->uid);
        $auth = new Auth;
        if (!$auth->check('admin,message', 1, 'and')) {
            return [false, '无权限'];
        }
        $content = config('mtf.Message.top');
        $array = [
            '{title}' => $topic->subject,
            '{username}' => $user->username,
            '{topicUrl}' => url('index/topic/index', ['tid' => $topic->tid]),
        ];
        $content = strtr($content, $array);
        $title = '你的Topic被设为精华';
        $this->addMessage($topic->uid, 1, $title, $content);
        return true;
    }
}
