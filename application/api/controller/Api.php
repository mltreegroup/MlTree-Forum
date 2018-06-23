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
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;

class Api extends Base
{
    public function index()
    {
        return ;
    }

    public function Search($keyword = '', $type = 'topic')
    {
        switch ($type) {
            case 'topic':
                $data = Db::name('topic')->where('subject,content', 'like', '%'.$keyword.'%')->select();
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

    public function commentList($tid, $page=1)
    {
        $max = Option::getValue('commentNum');
        $comment = Comment::page('comment')->where('tid', $tid)->page($page, $max)->select();

        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid', $value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];
            $value['time_format'] = time_format($value['create_time']);
        }

        $count = Comment::page('comment')->where('tid', $tid)->count('cid');
        $pages = ceil($count / $max);
        return json(['code'=>'3001','data'=>$comment,'pages'=>$pages]);
    }

    public function commentConent($cid)
    {
        $comment = Comment::get($cid);
        $comment->userData = Db::name('user')->where('uid', $comment->uid)->field('username')->find();
        return json(['code'=>'3001','message'=>$comment,'time'=>time()]);
    }

    public function topiclist($page=1, $t=1, $fid=0)
    {
        $max = Option::getValue('forumNum');
        switch ($t) {
            case 2:
                if ($fid == 0) {
                    $topicData = topic::page('topic')->where('essence', 1)->page($page, $max)->order('create_Time DESC')->select();
                } else {
                    $topicData = topic::page('topic')->where('essence', 1)->where('fid', $fid)->page($page, $max)->order('create_Time DESC')->select();
                }
                $count = topic::page('topic')->where('essence', 1)->count('tid');
                $pages = ceil($count / $max);
                break;
            
            default:
                if ($fid == 0) {
                    $topicData = topic::page($page, $max)->order('create_Time DESC')->select();
                } else {
                    $topicData = topic::page($page, $max)->where('fid', $fid)->order('create_Time DESC')->select();
                }
                $count = topic::count('tid');
                $pages = ceil($count / $max);
                break;
        }
        //数据处理（content等去除标签)
        $user = new User;
        foreach ($topicData as $key => $value) {
            $value['content'] = strip_tags($value['content']);
            $value['time_format'] = time_format($value['create_time']);
            $value['userData'] = $user->where('uid', $value['uid'])->field('username,avatar')->find();
            $value['forumName'] = Db::name('forum')->where('fid', $value['fid'])->field('name')->find()['name'];
            $value['Badge'] = outBadge($value);
        }
        return json(['code'=>'0','data'=>$topicData,'pages'=>$pages]);
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

            $time = session('regMail');
            if ($time > time()) {
                $time = $time-time();
                return json(['code'=>'-1','message'=>'还有'.$time.'后可以再次获取','time'=>time(),'regTime'=>session('regMail')]);
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
                session('regMail', time()+60);
                session('regCode', $code);
                return json(['code'=>'0','message'=>'发送成功！','time'=>time(),'regTime'=>session('regMail')]);
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

            $time = session('resetMail');
            if ($time > time()) {
                $time = $time-time();
                return json(['code'=>'-1','message'=>'还有'.$time.'后可以再次获取','time'=>time(),'resetTime'=>session('resetMail')]);
            }

            $semail = new Mail;
            $code = createStr(6);
            $arry = [
                                '{siteTitle}' => Option::getValue('siteTitle'),
                                '{userName}' => $res->username,
                                '{code}' => $code,
                            ];
            $title = Option::getValue('reset_mail_title');
            $content = Option::getValue('reset_mail_content');
            $title = strtr($title, $arry);
            $content = strtr($content, $arry);
            $res = $semail->send($email, $res->username, $title, $content);
            
            if ($res !== true) {
                return json(['code'=>'-1','message'=>$semail->errorMsg,'time'=>time(),$email]);
            } else {
                session('resetMail', time()+60);
                session('resetCode', $code);
                return json(['code'=>'0','message'=>'发送成功！','time'=>time(),'resetTime'=>session('resetMail')]);
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
}
