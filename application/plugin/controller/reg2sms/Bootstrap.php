<?php
namespace app\plugin\reg2sms;

use app\common\hook\Plugin;
use app\plugin\controller\Base;

/**
 * # 插件主文件说明
 * 各位二次开发者能在这里对系统进行监听，完成函数的拓展
 * 首先，我们需要定义一个public function [钩子名]
 * 每一个function 对应一个钩子，传递的参数为各个主要的参数
 * 钩子的主要作用在于
 * 例如
 */
class Bootstrap extends Base
{
    //这个方法将绑定在'application\forum\index.php'的index方法上，他将在模板的首页进行调用
    public function index($Example)
    {
        //可以使用$Example进行渲染，赋值等操作
        //$Example->assign('index','Helloworld');
        return true; //返回true允许向下执行，false不允许向下执行
    }

    public function reg()
    {
        return true;
    }

    public function search($kw)
    {
        return true;
    }

    public function topicIndex($tid)
    {
        return true;
    }
}
