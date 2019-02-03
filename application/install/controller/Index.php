<?php
namespace app\install\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function Index()
    {
        if (isInstall()) {
            return $this->error('你已经安装过了，如需重新安装，请删除./install/install.lock文件', 'forum/index/index');
        }
        if (request()->isPost()) {
            $data = file_get_contents(getRootPath() . "public/install/database.php");
            //替换database
            $dataarr = [
                '{prefix}' => input('post.prefix'),
                '{hostname}' => input('hostname'),
                '{database}' => input('database'),
                '{datausername}' => input('datausername'),
                '{datapassword}' => input('datapassword'),
                '{hostport}' => input('hostport'),
            ];
            $data = strtr($data, $dataarr);

            file_put_contents(getRootPath() . 'config/database.php', $data);

            $sql = file_get_contents(getRootPath() . "public/install/mtf_install.sql");
            //替换sql
            $sqlarr = [
                'mf_' => input('post.prefix'),
                '{email}' => input('post.email'),
                '{username}' => input('post.username'),
                '{password}' => password_encode(input('post.password')),
                '{prefix}' => input('post.prefix'),
                '{hostname}' => input('hostname'),
                '{database}' => input('database'),
                '{datausername}' => input('datausername'),
                '{datapassword}' => input('datapassword'),
                '{hostport}' => input('hostport'),
            ];
            $sql = strtr($sql, $sqlarr);

            //****导入SQL文件
            $sqlarr = explode(';', $sql);
            foreach ($sqlarr as $key => $value) {
                $res = Db::failException()->query($value);
            }

            file_put_contents(getRootPath() . "public/install/install.lock", time());

            curlPost('https://app.kingsr.cc/Api/initApp', [
                'ip' => gethostbyname($_SERVER['SERVER_NAME']),
                'url' => $_SERVER['SERVER_NAME'],
            ], false);

            return redirect('forum/index/index');
        }

        return view('install@index');
    }
}
