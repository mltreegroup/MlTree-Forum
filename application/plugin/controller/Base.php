<?php
namespace app\plugin\controller;

use app\common\model\Plugin;
use think\Controller;
use think\Db;

class Base extends Controller
{
    protected $middleware = ['Plugin'];

    public function initialize()
    {
        $this->view = new \think\View();
    }

    /**
     * 插件是否启用
     */
    public function isArrow($appSign)
    {
        return Plugin::isArrow($appSign);
    }

    /**
     * 插件注册接口
     * @param string $appSign 插件标识，等同于插件目录
     */
    public function appInit($appSign, $info = [])
    {
        if ($this->isArrow($appSign)) {
            return true;
        } else {
            return Plugin::appInit($appSign, $info);
        }
    }

    /**
     * 插件注销接口
     * @param string $appSign 插件标识，等同于插件目录
     */
    public function appUninit($appSign)
    {
        if (!$this->isArrow($appSign)) {
            return true;
        } else {
            return Plugin::appCancel($appSign);
        }
    }

    /**
     * 插件视图渲染
     */
    public function pluginView($appSign, $tpl, $data = [])
    {
        return view($appSign . '\\view\\' . $tpl, $data);
    }

    /**
     * 获取插件配置内容
     */
    protected static function getValue($appSign = '', $name)
    {
        $res = Db::name('plugin_options')->where('sign', $appSign)->where('name', $name)->select();
        return $res;
    }

    /**
     * 获取指定插件的所有配置
     */
    protected static function getValues($appSign = '')
    {
        $res = Db::name('plugin_options')->where('sign', $appSign)->select();
        return $res;
    }

    /**
     * 设置插件配置
     */
    protected static function setValues($appSign = '', $value = [])
    {
        $res = Db::name('plugin_options')->where('sign', $appSign)->update($value);
        return $res;
    }
}
