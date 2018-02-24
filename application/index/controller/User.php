<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User as userModel;
use app\index\model\Option;
use think\Db;

class User extends Base
{
    public $userInfo;

    protected $beforeActionList = [
        'userInfo' ,
    ];

    public function userInfo()
    {
        if(!empty(session('uid')))
        {
            $user = new userModel();
            $userInfo = $user->userInfo();
        }
    }

    public function index()
    {
        return view('index',[
            'userInfo' => $userInfo,
        ]);
    }

    public function set()
    {
        if(!empty(input('post.')))
        {
            if(input('post.type') == 'pass')
            {

            }else if(input('post.type') == 'avatar'){
                $user = UserModel::where('uid',session(''));
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
                'password' => md5(input('password','','htmlspecialchars').$userSalt.input('post.email')),
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

                \app\index\model\Mail::send(input('post.mail'),input('post.username'),Option::getValue('reg_mail_title'),Option::getValue('reg_mail_content'));
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
}
