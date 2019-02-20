<?php
namespace app\common\controller;

use app\common\model\Option;
use app\common\model\Plugin;
use think\Controller;

class Base extends Controller
{
    /**
     * 初始化
     */
    protected function initialize()
    {
        //判断程序是否安装
        if (!isInstall()) {
            return $this->redirect('install\index\index');
        }
    }

    /**
     * 改装模板输出方式，使其适用于模板切换，以及一些模板的赋值
     * @param string $tpl 模板路径，从'template/'开始定位
     * @param string $plate 要输出的topbar标题
     * @param array $option 要附加输出的数据
     * @return view 返回模板输出函数，直接返回给方法上层即可
     */
    public function mtfView($tpl = '', $plate = null, $option = [])
    {
        $siteData = Option::getValues(['base']);
        !empty($plate) ? $siteData['mainTitle'] = $plate . ' - ' . $siteData['siteTitle'] : $siteData['mainTitle'] = $siteData['siteTitle'];
        $siteData['palte'] = $plate;
        $tpl = $siteData['template'] . $tpl;

        $pList = Plugin::getPluginList(true, true);
        $this->assign('plugin', $pList);
        
        $this->assign('site', $siteData);
        $this->assign($option);
        return $this->fetch($tpl);
    }
}
