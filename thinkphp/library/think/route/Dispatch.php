<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\route;

use think\Container;

abstract class Dispatch
{
    // 应用实例
    protected $app;
    // 调度信息
    protected $dispatch;
    // 调度参数
    protected $param;
    // 状态码
    protected $code;
    // 是否进行大小写转换
    protected $convert;

    public function __construct($dispatch, $param = [], $code = null)
    {
        $this->app      = Container::get('app');
        $this->dispatch = $dispatch;
        $this->param    = $param;
        $this->code     = $code;
<<<<<<< HEAD
        $this->init();
    }

    protected function init()
    {}

=======
    }

>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    public function convert($convert)
    {
        $this->convert = $convert;

        return $this;
    }

    public function getDispatch()
    {
        return $this->dispatch;
    }

    public function getParam()
    {
        return $this->param;
    }

    abstract public function run();

}
