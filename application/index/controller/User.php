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
        if($uid == 0 && empty(session('uid')))
        {
            return $this->error('用户不存在！','index\index\index');
        }else {
            if($uid == 0)
            {
                $uid = session('uid');
            }

            //获取用户帖子信息
            $userTopicList = Db::name('topic')->where('uid',$uid)->select();
            foreach ($userTopicList as $key => $value) {
            $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
            $value['time_format'] = time_format($value['create_time']);
            $value['userData'] =Db::name('user')->where('uid',$value['uid'])->field('username,avatar')->find();
            $userTopicList[$key] = $value;
        }
            return view('index',[
            'option' => $this->siteOption('用户信息'),
            'userData' => $user->getInfo($uid),
            'userTopic' => $userTopicList,
            ]);
        }
        
    }

    public function set()
    {
        if(empty(session('uid')))
        {
            return \redirect('index\user\login');
        }
        if(!empty(input('post.')))
        {
            if(empty(session('salt')))
            {
                return ['code'=>'-1','message'=>'非法操作！'];
            }
            if(input('post.type') == 'pass')
            {
                $user = userModel::get(session('uid'));
                $userPass = md5(input('oldpassword','','htmlspecialchars').$user->salt.$user->email);
                if($userPass !== $user->password)
                {
                    return ['code'=>'-1','message'=>'新密码不一致或旧密码不正确。'];
                }elseif (input('password') !== input('repassword')) {
                    return ['code'=>'-1','message'=>'新密码不一致或旧密码不正确。'];
                }else {
                    $user->password = input('password');
                    $user->save();
                    session(null);
                    return $this->success('修改成功！请使用新密码登录。','login');
                    // return ['code'=>'0','message'=>'修改成功！请使用新密码登录。'];
                }
            }
        }
    }

    public function login()
    {
        if(!empty(session('uid')))
        {
            return redirect('index');
        }
        if(!empty(input('post.')))
        {
            $user = UserModel::where('email',input('post.email'))->find();
            if($user == null)
            {
                return json(['code'=>'-1','message'=>'用户或不存在']);
            }else{
                $userPass = md5(input('password','','htmlspecialchars').$user->salt.input('post.email'));
                if($userPass === $user->password)
                {
                    session('uid',$user->uid);
                    session('gid',$user->gid);
                    session('salt',$user->salt);
                    session('username',$user->username);
                    Db::name('user')->where('uid',$user->uid)->setInc('logins');//增加登录次数值
                    return json(['code'=>'0','message'=>'登录成功！欢迎回来……','url'=>url('index\user\index')]);
                }else{
                    return ['code'=>'-1','message'=>'用户名或密码错误！'];
                }
            }
        }
        return view('login',[
            'option' => $this->siteOption('登录'),
        ]);
    }

    public function reg()
    {
        if(!empty(session('uid')))
        {
            return redirect('index');
        }
        if(!empty(input('post.'))){
            if(Option::getValue('reg_status') == '1')
            {
                return json(['code'=>'-1','message'=>'站点关闭注册']);
            }

            if(Db::name('user')->where('email',input('post.email'))->find() != null){
                return json(['code'=>'-1','message'=>'该邮箱已注册！']);
            }
            if(input('post.password') !== input('post.repassword'))
            {
                return json(['code'=>'-1','message'=>'两次密码不一致！']);
            }
            $result = $this->validate(input('post.'),'app\index\validate\User');

            if($result !== true){
                return json(['code'=>'-1',$result]);
            }
            $userSalt = createStr(6);
            $userData = [
                'username' => input('post.username','','htmlspecialchars'),
                'email' => input('post.email'),
                'password' => input('password','','htmlspecialchars'),
                'gid' => Option::getValue('defauleGroup'),
                'salt' => $userSalt,
            ];
            
            $user = new UserModel;
            
            $user->allowField(true)->save($userData);
            if(Option::getValue('reg_email') == '1')
            {
                \app\index\model\Confirm::create([
                    'code' => rand(100000,999999),
                    'time' => time(),
                    'type' => 'register',
                ]);

                //调用Mailmodel，发送邮件
                $email = model("Mail");
                $email->send(input('post.mail'),input('post.username'),Option::getValue('reg_mail_title'),Option::getValue('reg_mail_content'));
                return json(['code'=>0,'message'=>'注册成功！我们已经发送了一封激活邮件，赶紧到邮箱中激活吧！','url'=>url('index\user\login')]);
            }else{
                return json(['code'=>0,'message'=>'注册成功！正在跳转至登陆界面……','url'=>url('index\user\login')]);
            }
        }
        return view('reg',[
            'option' => $this->siteOption('注册'),
        ]);
    }

    public function logout()
    {
        if(!empty(session('uid')))
        {
            session(null);
            return $this->success('退出成功！','index/index/index');
        }else {
            return $this->error('当前无需退出呢！');
        }
    }
}
