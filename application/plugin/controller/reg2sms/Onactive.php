<?php
namespace app\plugin\controller\reg2sms;

use app\plugin\controller\Base;

class Onactive extends Base
{
    /**
     * 插件注册方法，当用户启用插件时。系统将会调用此函数
     * 请确保此函数进行了$this->appInit($sign)方法。
     */
    public function run()
    {
        $this->appInit('reg2sms', [
            'name' => 'reg2sms',
            'sign' => 'reg2sms',
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
        return $this->appUninit('reg2sms');
    }

}
