<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User as userModel;
use app\index\model\Option;
use think\Db;
use connect\qqconnect\QC;

class User extends Base
{
    public function index($uid = 0)
    {
        $user = new userModel;
        if ($uid == 0 && empty(session('uid'))) {
            return $this->error('用户不存在！', 'index\index\index');
        } else {
            if ($uid == 0) {
                $uid = session('uid');
            }
            //获取用户帖子信息
            $userTopicList = Db::name('topic')->where('uid', $uid)->select();
            
            foreach ($userTopicList as $key => $value) {
                $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
                $value['time_format'] = time_format($value['create_time']);
                $value['userData'] =Db::name('user')->where('uid', $value['uid'])->field('username,avatar')->find();
                $userTopicList[$key] = $value;
            }
            if (session('uid') == $uid) {
                return view('index', [
                                'option' => $this->siteOption('用户信息'),
                                'userData' => userModel::get($uid),
                                'userTopic' => $userTopicList,
                                ]);
            }
            $userInfo = userModel::get($uid);
            $userInfo['gorupData'] = Db::name('group')->where('gid', $userInfo['gid'])->find();
            
            return view('index_public', [
                        'option' => $this->siteOption('用户信息'),
                        'userData' => userModel::get(session('uid')),
                        'userInfo' => $userInfo,
                        'userTopic' => $userTopicList,
                        ]);
        }
    }
    public function set()
    {
        if (empty(session('uid'))) {
            return \redirect('index\user\login');
        }
        if (!empty(input('post.'))) {
            if (input('post.type') == 'pass') {
                $user = userModel::get(session('uid'));
                if (password_verify(input('post.password'), $user->password)) {
                    return ['code'=>'-1','message'=>'新密码不一致或旧密码不正确。'];
                } elseif (input('password') !== input('repassword')) {
                    return ['code'=>'-1','message'=>'新密码不一致或旧密码不正确。'];
                } else {
                    $user->password = input('password');
                    $user->save();
                    session(null);
                    return $this->success('修改成功！请使用新密码登录。', 'login');
                    // return ['code'=>'0','message'=>'修改成功！请使用新密码登录。'];
                }
            }
        }
    }
    public function login()
    {
        if (!empty(session('uid'))) {
            return redirect('index');
        }
        if (!empty(input('post.'))) {
            $user = UserModel::where('email', input('post.email'))->find();
            if ($user == null) {
                return json(['code'=>'-1','message'=>'用户或不存在']);
            } else {
                if (password_verify(input('post.password'), $user->password)) {
                    session('uid', $user->uid);
                    session('gid', $user->gid);
                    session('username', $user->username);
                    Db::name('user')->where('uid', $user->uid)->setInc('logins');
                    //增加登录次数值
                    return json(['code'=>'0','message'=>'登录成功！欢迎回来……','url'=>'/user.html']);
                } else {
                    return ['code'=>'-1','message'=>'用户名或密码错误！'];
                }
            }
        }
        return view('login', [
                    'option' => $this->siteOption('登录'),
                ]);
    }

    public function reg()
    {
        if (!empty(session('uid'))) {
            return redirect('index');
        }
        if (!empty(input('post.'))) {
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

    public function logout()
    {
        if (!empty(session('uid'))) {
            session(null);
            return $this->success('退出成功！', 'index/index/index');
        } else {
            return $this->error('当前无需退出呢！');
        }
    }

    public function Reset()
    {
        if (!empty(session('uid'))) {
            return redirect('index');
        }

        if (request()->isPost()) {
            $data = input('post.', '', 'strip_tags,htmlspecialchars');
            $res = userModel::Reset($data);
            if ($res[0]) {
                return json(['code'=>0,'message'=>'密码重置完成，请使用新密码登录。','url'=>url('index/user/login'),'time'=>time()]);
            } else {
                return json(['code'=>'-1','message'=>$res[1],'time'=>time()]);
            }
        }
        return view('reset',[
            'option' => $this->siteOption('重置密码'),
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
            $userInfo->setInc('logins');
            $userInfo->save();
            $this->assign('userData', $userInfo);
            return $this->success('欢迎回来,'.$userInfo->username, url('index/user/index'));
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
