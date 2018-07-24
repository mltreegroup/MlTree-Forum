<?php
namespace app\install\controller;

use think\Controller;
use app\common\model\Message;
use app\index\model\User;
use think\Db;
use app\index\model\Option;

class Index extends Controller
{
    public function Index()
    {
        $msgObj = new Message;
        $msg = $msgObj->getMessageList(session('uid'),0);
        $this->assign('msg',['unread'=>count($msg['data'])]);
        $data = Db::name('links')->order('sold')->select();
        $this->assign('option', Option::getValues('base'));

        if (isInstall()) {
            return $this->error('你已经安装过了，如需重新安装，请删除./install/install.lock文件','index/index/index');
        }
        if (\request()->isPost()) {
            $data = file_get_contents("./install/database.php");
            //替换database
            $dataarr = [
                '{prefix}' => input('post.prefix'),
                '{hostname}' => input('hostname'),
                '{database}' => input('database'),
                '{datausername}' => input('datausername'),
                '{datapassword}' => input('datapassword'),
                '{hostport}' => input('hostport'),
            ];
            $data = strtr($data,$dataarr);

            file_put_contents('../config/database.php',$data);
            
            $sql = file_get_contents("./install/install.sql");
            //替换sql
            $sqlarr = [
                'mf_' => input('post.prefix'),
                '{email}' => input('post.email'),
                '{username}' => input('post.username'),
                '{password}' => \password_encode(input('post.password')),
            ];
            $sql = strtr($sql,$sqlarr);

            //****导入SQL文件
            $sqlarr = \explode(';',$sql);
            foreach ($sqlarr as $key => $value) {
                Db::failException(false)->query($value);
            }

            file_put_contents('./install/install.lock',time());
            return $this->success('安装完成，请享受MlTreeForum给你带来的快感吧！','index/index/index');
        }
        
        return view('install@index');
    }
}
