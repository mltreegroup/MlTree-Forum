<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\index\model\Option;
use app\index\model\Comment;
use app\index\model\Topic;
use app\index\model\User;
use app\index\model\Mail;
use think\Db;
use Auth\Auth;
use app\common\model\Message;

class Api extends Base
{
    public function index()
    {
        return 'Welcome to MlTree Forum!';
    }

    public function Search($keyword = '', $type = 'topic')
    {
        switch ($type) {
            case 'topic':
                $topic = new Topic();
                $data = $topic->Search($keyword);
                return json(['code'=>'2020','data'=>$data,'time'=>time()]);
                break;
            
            default:
                return ;
                break;
        }
    }

    public function del($type = 'topic', $id=0, $uid=0)
    {
        if (empty($uid) && empty(session('uid'))) {
            return json(['code'=>'1000','message'=>'尚未登录','time'=>time()]);
        } elseif ($uid == 0) {
            $uid = session('uid');
        }
        switch ($type) {
            case 'topic':
                $auth = new Auth;
                if ($auth->check('delete', $uid) || $auth->check('admin', $uid)) {
                    Db::transaction(function () {
                        Db::name('topic')->find((int)input('id'));
                        Db::name('topic')->delete((int)input('id'));
                    });
                } else {
                    return json(['code'=>'3004','message'=>'无权限','time'=>time()]);
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    public function topicList($page=1, $type='common', $fid=0)
    {
        $topic = new Topic;
        $list = $topic->TopicList($page, $type, $fid);
        return json(['code'=>'0','data'=>$list[0],'pages'=>$list[1]]);
    }

    public function commentList($tid, $page=1)
    {
        $tid = 0 ? $tid = 1 : $tid;
        $comment = new Comment();
        $list = $comment->ListPage($tid, $page);
        return json(['code'=>'3001','data'=>$list[0],'pages'=>$list[1]]);
    }

    public function getRegCode()
    {
        if (!empty(input('post.'))) {
            $email = input('post.email');
            $username = input('post.username');

            $res = user::where('email', $email)->find();
            if (!empty($res)) {
                return json(['code'=>'-1','message'=>'该邮箱已注册']);
            }

            $time = session('reg.Time');
            if ($time > time()) {
                $time = $time-time();
                return json(['code'=>'-1','message'=>'还有'.$time.'后可以再次获取','time'=>time(),'regTime'=>session('reg.Time')]);
            }

            $semail = new Mail;
            $code = createStr(6);
            $arry = [
                                '{siteTitle}' => Option::getValue('siteTitle'),
                                '{userName}' => $username,
                                '{code}' => $code,
                            ];
            $title = Option::getValue('reg_mail_title');
            $content = Option::getValue('reg_mail_content');
            $title = strtr($title, $arry);
            $content = strtr($content, $arry);
            $res = $semail->send($email, $username, $title, $content);
            
            if ($res !== true) {
                return json(['code'=>'-1','message'=>$semail->errorMsg,'time'=>time(),$email]);
            } else {
                session('reg.Time', time()+60);
                session('reg.Code', $code);
                return json(['code'=>'0','message'=>'发送成功！','time'=>time(),'regTime'=>session('reg.Time')]);
            }
        }
    }

    public function getResetCode()
    {
        if (request()->isPost()) {
            $email = input('post.email');

            $res = user::where('email', $email)->find();
            if (empty($res)) {
                return json(['code'=>'-1','message'=>'邮箱不存在！']);
            }

            $time = session('forget.Time');
            if ($time > time()) {
                $time = $time-time();
                return json(['code'=>'-1','message'=>'还有'.$time.'后可以再次获取','time'=>time(),'forgetTime'=>session('forget.Time')]);
            }

            $semail = new Mail;
            $code = createStr(6);
            $arry = [
                                '{siteTitle}' => Option::getValue('siteTitle'),
                                '{userName}' => $res->username,
                                '{code}' => $code,
                            ];
            $title = Option::getValue('forget_mail_title');
            $content = Option::getValue('forget_mail_content');
            $title = strtr($title, $arry);
            $content = strtr($content, $arry);
            $res = $semail->send($email, $res->username, $title, $content);
            
            if ($res !== true) {
                return json(['code'=>'-1','message'=>$semail->errorMsg,'time'=>time(),$email]);
            } else {
                session('forget.Time', time()+60);
                session('forget.Code', $code);
                return json(['code'=>'0','message'=>'发送成功！','time'=>time(),'forgetTime'=>session('forget.Time')]);
            }
        }
    }

    public function getValue()
    {
        if (request()->isPost()) {
            $data = input('post.');
            switch ($data['type']) {
                case 'topic':
                    $topicData = topic::get($data['Id']);
                    if (empty($topicData)) {
                        return json(['code'=>'-1','message'=>'Topic不存在','time'=>time()]);
                    }
                    return json(['code'=>'0','message'=>'Success!','data'=>$topicData,'time'=>time()]);

                    break;
                case 'comment':
                    $comment = comment::get($data['Id']);
                    if (empty($comment)) {
                        return json(['code'=>'-1','message'=>'Comment不存在','time'=>time()]);
                    }
                    return json(['code'=>'0','message'=>'Success!','data'=>$comment,'time'=>time()]);

                    break;

                default:
                    
                    break;
            }
        }
    }

    public function MessageList($uid)
    {
        $msg = new Message;
        $res = $msg->getMessageList($uid);
        return json($res);
    }

    public function getMessage($mid)
    {
        $msg = new Message;
        $res = $msg->getMessage($mid);
        if (!$res[0]) {
            return json(\outResult(-1, $res[1]));
        }
        return json(\outResult(0, $res[1]));
    }

    public function addMessage()
    {
        if (request()->isPost()) {
            $data = input('post.', '', 'htmlentities');
            $msg = new Message;
            $res = $msg->addMessage($data['toUid'], $data['uid'], $data['title'], $data['content']);
            if (!$res[0]) {
                return json(\outResult(-1, $res[1]));
            }
            return json(\outResult(0, $res[1]));
        }
    }

    public function readMessage()
    {
        if (request()->isPost()) {
            $data = input('post.', '', 'htmlentities');
            $msg = new Message;
            $res = $msg->readMessage($data['mid']);
            if (!$res[0]) {
                return json(\outResult(-1, $res[1]));
            }
            return json(\outResult(0, 'Success'));
        }
    }

    public function delMessage()
    {
        if (request()->isPost()) {
            $data = input('post.', '', 'htmlentities');
            $msg = new Message;
            
            if ($data['mid'] == 'all' || $data['mid'] == 'read') {
                $res = $msg->delMessage($data['mid'], $data['uid']);
            } else {
                $res = $msg->delMessage($data['mid']);
            }
            if (!$res[0]) {
                return json(\outResult(-1, $res[1]));
            }
            return json(\outResult(0, 'Success'));
        }
    }
}
