<?php
namespace app\forum\controller;

use app\common\model\Mail;
use app\common\model\Message;
use app\common\model\User as UserModel;
use app\forum\controller\Base;
use \connect\qqconnect\QC;

class User extends Base
{
    public function index($uid = 0)
    {
        $user = new UserModel();
        if ($uid == 0 && empty(session('uid'))) {
            return $this->error('用户不存在！', 'index/index/index');
        }
        if (!UserModel::isLogin(cookie('userKey')) || $uid != session('uid') && $uid != 0) {
            $user = new UserModel;
            $userInfo = $user->getInfor($uid);
            $userTopicList = UserModel::getTopicList($uid);
            if (!$userInfo) {
                return $this->error('用户不存在');
            }
            /**
             * 置入钩子
             * 钩子名称：userIndex
             * 钩子参数：[type,userInfo,userTopic]
             */
            \app\common\hook\Plugin::call('userIndex', $this, $data = [
                'type' => 'Visitor',
                'userInfo' => $userInfo,
                'userTopic' => $userTopicList,
            ]);

            return $this->mtfView('user/index', '用户信息',
                [
                    'type' => 'Visitor',
                    'userInfo' => $userInfo,
                    'userTopic' => $userTopicList,
                ]
            );
        } else { //增加，减少查询次数
            $userInfo = $user->getInfor(session('uid'));
            $userTopicList = UserModel::getTopicList(session('uid'));
        }
        return $this->mtfView('user/index', '用户信息', [
            'type' => 'Self',
            'userInfo' => $userInfo,
            'userTopic' => $userTopicList,
        ]);
    }

    public function reg()
    {
        if (UserModel::isLogin()) {
            return redirect('index');
        }
        if (request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            /**
             * 置入钩子
             * 钩子名称：userReg
             * 钩子参数：$data
             */
            \app\common\hook\Plugin::call('userRegBefor', $this, $data);
            $res = UserModel::register($data);
            \app\common\hook\Plugin::call('userRegAfter', $this, $data);
            if ($res[0]) {
                return outRes(0, '注册成功！正在前往登录界面', url('forum/user/login'));
            } else {
                return outRes(-1, $res[1]);
            }
        }

        return $this->mtfView('user/reg', '注册');
    }

    public function login()
    {
        if (UserModel::isLogin()) {
            return redirect('index');
        }
        if (request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            /**
             * 置入钩子
             * 钩子名称：userLogin
             * 钩子参数：$data
             */
            \app\common\hook\Plugin::call('userLogin', $this, $data);
            $res = UserModel::login($data);
            if ($res[0]) {
                return outRes(0, '登录成功！欢迎回来……', url('forum/user/index'));
            } else {
                return outRes(-1, $res[1]);
            }
        }
        return $this->mtfView('user/login', '登录');
    }

    public function logout()
    {
        if (UserModel::isLogin()) {
            UserModel::logout();
            return $this->success('退出成功！');
        } else {
            return $this->error('当前无需退出呢！');
        }
    }

    public function ResetPwd()
    {
        if (\request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = $this->validate($data, 'app\common\validate\User.ResetPas');
            if (!$res) {
                return (outRes(-1, $res));
            }
            if ($data['password'] !== $data['repassword']) {
                return (outRes(-1, '两次密码不一致'));
            }
            $res = UserModel::resetPas(session('uid'), $data['oldpassword'], $data['password']);
            if ($res[0]) {
                UserModel::logout();
                return (outRes(0, $res[1], url('index/user/login')));
            } else {
                return (outRes(-1, $res[1]));
            }
        }
    }

    public function forgetPwd()
    {
        if (UserModel::isLogin()) {
            return redirect('forum/user/index');
        }
        if (request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = $this->validate($data, 'app\common\validate\User.forgetPas');
            if (!$res) {
                return outRes(-1, $res);
            }
            if ($data['password'] !== $data['repassword']) {
                return outRes(-1, '两次密码不一致');
            }
            $res = UserModel::forgetPas($data);
            if ($res[0]) {
                return outRes(0, $res[1], url('forum/user/login'));
            } else {
                return outRes(-1, $res[1]);
            }
        }
        return $this->mtfView('user/forget', '找回账号');
    }

    public function Active($uid, $code, $time)
    {
        $res = UserModel::activateUser($uid, $code, $time);
        if ($res[0]) {
            return $this->success($res[1], url('forum/user/login'));
        } else {
            return $this->error($res[1], url('forum/index/index'));
        }
    }

    /**
     * 重新发送激活邮件
     */
    public function reActive($email)
    {
        $email = base64_decode($email);
        $user = UserModel::getByEmail($email);
        if (empty($user)) {
            return $this->error('账号不存在或错误。');
        } elseif (empty($user->code) && $user->status != 0) {
            return $this->error('账号无需激活或位未知错误');
        } elseif (session('ActiveTime') > time()) {
            return $this->error('请稍后再尝试获取激活邮件');
        }
        $code = createStr(30);
        $time = strtotime('+5 minutes');
        $user->code = $code;
        $user->status = 0;
        $user->save();
        session('ActiveTime', $time);

        $mail = new Mail;
        $mail->SendActiveLink($user, $code, $time);
        return $this->success('激活邮件已经发送，请注意查看邮箱');
    }

    public function set()
    {
        if (!UserModel::isLogin()) {
            return redirect('index/user/login');
        }
        if (request()->isPost()) {
            UserModel::where('uid', session('uid'))->update(['motto' => input('post.motto')]);
            return outRes(0, '修改个人信息成功');
        }
    }

    public function Message()
    {
        $this->assign('option', $this->siteOption('消息盒子'));
        if (!UserModel::isLogin()) {
            return redirect('index/user/login');
        }
        $msgObj = new Message;
        $msg = $msgObj->getMessageList(session('uid'));
        $msgObj->readMessage('all');
        $this->assign('messageData', $msg['data']);
        return view('message', [
            'option' => $this->siteOption('消息盒子'),
        ]);
    }

    public function qqLogin()
    {
        $qc = new QC();
        $res = $qc->qq_login();
        return redirect($res);
    }

    public function callback($code, $state)
    {
        $qc = new QC();
        $qc->qq_callback(); // access_token
        $qc->get_openid(); // openid
        $userInfo = UserModel::where('qqconnectId', session('openid'))->find();
        if (!empty($userInfo)) {

            session('uid', $userInfo->uid);
            session('gid', $userInfo->gid);
            session('username', $userInfo->username);
            $code = createStr(32);
            cookie('userKey', $code);
            session('userKey', $code);

            $userInfo->setInc('logins');
            $userInfo->save();
            $this->assign('userData', $userInfo);
            return redirect('index/user/index');
        } else {
            if (Option::getValue('allowQQreg') == 1) {
                return view('qqconnect', [
                    'option' => $this->siteOption('注册'),
                ]);
            } else {
                return $this->error('尚未注册，请注册后绑定QQ再使用QQ登录！', 'forum/user/reg');
            }
        }
    }

    public function qqconnect($type = '1')
    {
        if ($type == '1') {
            if (!empty(input('post.'))) {
                $data = input('post.', '', 'strip_tags,htmlspecialchars');
                $info = UserModel::where('email', $data['email'])->find();
                if (empty($info)) {
                    return outRes(-1, '用户不存在或错误');
                } else {
                    $info->qqconnectId = session('openid');
                    $info->setInc('logins');
                    $info->save();
                    session('uid', $info->uid);
                    session('gid', $info->gid);
                    session('username', $info->username);
                    return outRes(0, '绑定成功，正在跳转……', url('forum/user/index'));
                }
            }
        } else {
        }
    }
}
