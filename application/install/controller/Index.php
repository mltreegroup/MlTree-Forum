<?php
namespace app\install\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function Index()
    {
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
            return redirect('index/index/index');
        }
        
        return view('install@index');
    }
}
