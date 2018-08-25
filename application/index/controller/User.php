<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User as userModel;
use connect\qqconnect\QC;
use app\common\model\Message;

class User extends Base
{
    public function index($uid=0)
    {
        $user = new userModel();
        if ($uid == 0 && empty(session('uid'))) {
            return $this->error('用户不存在！', 'index\index\index');
        }
        if (!userModel::isLogin(cookie('userKey')) || $uid != session('uid') && $uid != 0) {
            $userTopicList = userModel::getTopicList($uid);
            $user = new userModel;
            $userInfo = $user->getInfor($uid);
            if(!$userInfo)
            {
                return $this->error('用户不存在');
            }
            return view('index_public', [
                'option' => $this->siteOption('用户信息'),
                'userData' => userModel::get(session('uid')),
                'userInfo' => $userInfo,
                'userTopic' => $userTopicList,
            ]);
        } else {
            $userTopicList = userModel::getTopicList(session('uid'));
            $user =new userModel;
            $userInfo = $user->getInfor(session('uid'));
            return view('index', [
                'option' => $this->siteOption('用户信息'),
                'userData' => $userInfo,
                'userTopic' => $userTopicList,
            ]);
        }

        return view();
    }

    public function reg()
    {
        if (userModel::isLogin()) {
            return redirect('index');
        }
        if (request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = userModel::register($data);
            if ($res[0]) {
                return json(['code'=>0,'message'=>'注册成功！正在前往登录界面……','url'=>url('index/user/login'),'time'=>time()]);
            } else {
                return json(['code'=>'-1','message'=>$res[1],'time'=>time()]);
            }
        }

        return view('reg', [
            'option' => $this->siteOption('注册'),
        ]);
    }

    public function login()
    {
        if (userModel::isLogin()) {
            return redirect('index');
        }
        if (request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = userModel::login($data);
            if ($res[0]) {
                return json(['code'=>0,'message'=>'登录成功！欢迎回来……','url'=>url('index/user/index'),'time'=>time()]);
            } else {
                return json(['code'=>'-1','message'=>$res[1],'time'=>time()]);
            }
        }
        return view('login', [
            'option' => $this->siteOption('登录'),
        ]);
    }

    public function logout()
    {
        if (userModel::isLogin()) {
            userModel::logout();
            return $this->success('退出成功！', 'index/index/index');
        } else {
            return $this->error('当前无需退出呢！');
        }
    }

    public function ResetPas()
    {
        if (\request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = $this->validate($data, 'app\index\validate\User.ResetPas');
            if (!$res) {
                return json(outResult(-1, $res));
            }
            if ($data['password'] !== $data['repassword']) {
                return json(\outResult(-1, '两次密码不一致'));
            }
            $res = userModel::resetPas(session('uid'), $data['oldpassword'], $data['password']);
            if ($res[0]) {
                userModel::logout();
                return json(outResult(0, $res[1], url('index/user/login')));
            } else {
                return json(\outResult(-1, $res[1]));
            }
        }
    }

    public function forgetPas()
    {
        if (userModel::isLogin()) {
            return \redirect('index/user/index');
        }
        if (\request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = $this->validate($data, 'app\index\validate\User.forgetPas');
            if (!$res) {
                return json(outResult(-1, $res));
            }
            if ($data['password'] !== $data['repassword']) {
                return json(\outResult(-1, '两次密码不一致'));
            }
            $res = userModel::forgetPas($data);
            if ($res[0]) {
                return json(outResult(0, $res[1], url('index/user/login')));
            } else {
                return json(\outResult(-1, $res[1]));
            }
        }
        return view('forget', [
            'option' => $this->siteOption('找回账号'),
        ]);
    }

    public function set()
    {
        if (!userModel::isLogin()) {
            return \redirect('index/user/login');
        }
        if (request()->isPost()) {
            userModel::where('uid', session('uid'))->update(['motto'=>input('post.motto')]);
            return json(\outResult(0, '修改个人信息成功'));
        }
    }

    public function Message()
    {
        $this->assign('option', $this->siteOption('消息盒子'));
        if (!userModel::isLogin()) {
            return \redirect('index/user/login');
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
        $qc->qq_callback();    // access_token
        $qc->get_openid();     // openid
        $userInfo = userModel::where('qqconnectId', session('openid'))->find();
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
            return \redirect('index/user/index');
        } else {
            if (Option::getValue('allowQQreg') == 1) {
                return view('qqconnect', [
                    'option' => $this->siteOption('注册'),
                ]);
            } else {
                return $this->error('尚未注册，请注册后绑定QQ再使用QQ登录！', 'index/user/reg');
            }
        }
    }

    public function qqconnect($type = '1')
    {
        if ($type == '1') {
            if (!empty(input('post.'))) {
                $data = input('post.', '', 'strip_tags,htmlspecialchars');
                $info = userModel::where('email', $data['email'])->find();
                if (empty($info)) {
                    return json(['code'=>'-1','message'=>'用户不存在或错误','time'=>time()]);
                } else {
                    $info->qqconnectId = session('openid');
                    $info->setInc('logins');
                    $info->save();
                    session('uid', $info->uid);
                    session('gid', $info->gid);
                    session('username', $info->username);
                    return json(['code'=>0,'message'=>'绑定成功，正在跳转……','url'=>url('index\user\index'),'time'=>time()]);
                }
            }
        } else {
        }
    }
}
