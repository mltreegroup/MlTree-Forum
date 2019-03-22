<?php
namespace app\plugin\controller\uploadfile;

use app\plugin\controller\Base;

class Onactive extends Base
{
    /**
     * 插件注册方法，当用户启用插件时。系统将会调用此函数
     * 请确保此函数进行了$this->appInit($sign)方法。
     */
    public function run()
    {
        $this->appInit('uploadfile', [
            'name' => 'uploadfile',
            'sign' => 'uploadfile',
        ]);
        // 写入初始设置
        Base::setValues('uploadfile', [
            'type' => 'local', // 定义上传方式，目前支持两种方式local(本地储存)、Upyun(又拍云)
            'url' => '', //又拍云服务空间地址
            'service_name' => '', // 又拍云服务地址
            'operato_name' => '', // 又拍云操作员账号
            'operato_pwd' => '', // 又拍云操作员密码
            'save_local' => true, // 是否保存本地文件副本
        ]);
        return 'IsRun';
    }

    /**
     * 插件注册方法，当用户启用插件时。系统将会调用此函数
     * 请确保此函数进行了$this->appUninit($sign)方法。
     * 同时系统也会对其进行卸载确认
     */
    public function Cancel()
    {
        return $this->appUninit('uploadfile');
    }

}
