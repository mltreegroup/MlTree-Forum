<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User as userModel;
use app\index\model\Option;
use think\Db;

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
            if (empty(session('salt'))) {
                return ['code'=>'-1','message'=>'非法操作！'];
            }
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
                    return json(['code'=>'0','message'=>'登录成功！欢迎回来……','url'=>url('index/user/index')]);
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
            $data = input('post.','','strip_tags,htmlspecialchars');

            $res = userModel::register($data);

            if($res[0])
            {
                return json(['code'=>0,'message'=>'注册成功！正在前往登录界面……','time'=>time()]);
            }else{
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
    
}
