<?php
namespace app\plugin\controller\reg2sms;

use app\plugin\controller\Base;

class Option extends Base
{
    public function index()
    {
        return ($this->pluginView('reg2sms', 'index', ['msg' => 'Hello World!我是渲染的']));
    }
}
