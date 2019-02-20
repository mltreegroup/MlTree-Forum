<?php
namespace taglib;

use think\template\TagLib;

class Tag extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'pluginJs',
    ];

    public function tagPluginJs()
    {
        $parse = '{volist name="plugin" id="vo"}';
        $parse .= '<script src="{$vo.runJs}"></script>';
        $parse .= '{/volist}';
        return $parse;
    }
}
