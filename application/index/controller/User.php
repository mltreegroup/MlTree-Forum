<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User as userModel;
use app\index\model\Option;
use think\Db;

class User extends Base
{
    public $user;

    protected $beforeActionList = [
        'userInfo'=> ['except' => 'login,reg,logout,sendMail'] ,
    ];

    public function userInfo()
    {
        if(!empty(session('uid')))
        {
            $user = new userModel();
            $this->user = $user;
        }else{

        }
    }

    public function index($uid = 0)
    {
        if($uid == 0 && empty(session('uid')))
        {
            return $this->error('用户不存在！','index\index\index');
        }else {
            if($uid == 0)
            {
                $uid = session('uid');
            }
            return view('index',[
            'title' => '用户信息 - MlTree Forum',
            'userData' => $this->user->getInfo($uid),
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
                $user = new userModel();
                $userData = $user::get(session('uid'));
                $userPass = md5(input('password','','htmlspecialchars').$user->salt.$user->email);
                if($userPass !== $user->password)
                {
                    return ['code'=>'-1','message'=>'新密码不一致或旧密码不正确。'];
                }elseif (input('password') !== input('repassword')) {
                    return ['code'=>'-1','message'=>'新密码不一致或旧密码不正确。'];
                }else {
                    $user->password = input('password');
                    $user->save();
                    return ['code'=>'0','message'=>'修改成功！请使用新密码登录。'];
                }
            }
        }
    }

    public function create()
    {
        $this->assign('title','发帖 - MlTree Forun');
        return view();
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
                }
            }
        }
        return view();
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
        return view();
    }

    public function logout()
    {
        if(!empty(session('uid')))
        {
            session(null);
            return json(['code'=>'0','message'=>'退出成功！']);
        }else {
            return json(['code'=>'-1','message'=>'当前无需退出呢~']);
        }
    }

    public function sendMail()
    {
        $email = model("Mail");
        $email->send('1143524493@qq.com','十载北林','This Test Mail From MlTreeForum','This Test Mail From MlTreeForum'.time());
        return '邮件已经发送';
    }
}
