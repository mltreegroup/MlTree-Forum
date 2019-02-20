<?php
namespace app\plugin\controller\helloworld;

use app\plugin\controller\Base;

class Option extends Base
{
    public function index()
    {
        return($this->pluginView('helloworld', 'index', ['msg' => 'Hello World!我是渲染的']));
    }
}
