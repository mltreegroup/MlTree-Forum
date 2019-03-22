<?php
namespace app\common\taglib;

use think\template\TagLib;

class Tag extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags = [
        'pluginjs' => ['attr' => '', 'close' => 0],
        'runjs' => ['attr' => '', 'close' => 0],
        'runjs' => ['attr' => 'src', 'close' => 0],
    ];

    /**
     * 启动插件runJs
     */
    public function tagPluginJs()
    {
        $parse = '<script src="__MOD__plugin/run.js"></script>';
        $parse .= '{volist name="plugin" id="vo"}';
        $parse .= '<script src="{$vo.runJs}"></script>';
        $parse .= '{/volist}';
        return $parse;
    }

    public function tagRunJs()
    {
        $parse = '<script src="__JS__run.js"></script>';
        return $parse;
    }
}
