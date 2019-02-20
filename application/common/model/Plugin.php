<?php

namespace app\common\model;

use think\Model;

class Plugin extends Model
{
    protected $pk = 'pid';

    /**
     * 获取插件启用状态
     */
    public static function isStart($appSign)
    {
        $res = Plugin::getBySign($appSign);
        if ($res['status'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取插件所在目录
     */
    public static function getAppPath($appSign)
    {
        return \getRootPath() . 'application/plugin/controller/' . $appSign . '/';
    }

    /**
     * 获取数据库中的插件列表
     */
    public static function getPluginList($start = true, $runJs = false)
    {
        if ($start) {
            $res = Plugin::where('status', 1)->select();
        } else {
            $res = Plugin::select();
        }
        if ($runJs) {
            foreach ($res as $key => $value) {
                $value['runJs'] = \pluginAssetsUrl($value['sign'], 'run_js');
            }
        }
        return $res;
    }

//     /**
    //      * 获取插件RunJS
    //      * @param bool $file
    //      */
    //     public static function getPluginRunJs($file = true)
    //     {
    //         $list = self::getPluginList(true, true);
    //         $jsContent = '';
    //         foreach ($list as $key => $value) {
    //             $jsContent .= PHP_EOL . \file_get_contents($value['runJs']) . ';;' . PHP_EOL;
    //         }
    //         $jsContent = strtr($jsContent, [
    //             '$(document).ready' => '',
    //             'Window.onload' => '',
    //         ]);
    //         if (empty(\cache('MlTreeForum-Plugin-runJs'))) {
    //             $runJs = <<<EOT
    //             var runList = {
    //                 Run:[],
    //                 RunEnd:[],
    //             };
    //             function addRunEvent(func){
    //                 runList.Run.push(func);
    //             };
    //             function addRunEndEvent(func){
    //                 runList.RunEnd.push(func)
    //             };

//             $jsContent

//             $(document).ready(function (){
    //                 runList.Run.forEach((item, index) => {
    //                     item();
    //                 });
    //             });
    //             Window.onload = function (){
    //                 runList.RunEnd.forEach((item, index) => {
    //                     item();
    //                 });
    //             }

// EOT;
    //             \cache('MlTreeForum-Plugin-runJs', $runJs, 3600);
    //         } else {
    //             $runJs = \cache('MlTreeForum-Plugin-runJs');
    //         }

//         if ($file) {
    //             file_put_contents(\getRootPath() . 'public/static/js/run.js', $runJs);

//         }

//         return [
    //             'runJS' => $runJs,
    //             'filePath' => '__JS__run.js',
    //         ];
    //     }

    public static function runPluginStart($appSign)
    {
        \action('plugin/' . $appSign . '/Onactive/run');
    }

    public static function runPluginCancel($appSign)
    {
        \action('plugin/' . $appSign . '/Onactive/cancel');
    }

    public static function Uninit($appSign)
    {
        return self::appCancel($appSign);
    }

    /**
     * 插件注册
     */
    public static function appInit($appSign, $info = [])
    {
        $res = self::getBySign($appSign);
        if (empty($res)) {
            $info['init'] = 1;
            $info['status'] = 1;
            self::insert($info);
            return true;
        }
        $res->init = 1;
        $res->save();
        return true;
    }

    /**
     * 插件注销
     */
    public static function appCancel($appSign)
    {
        $res = self::getBySign($appSign);
        $res->init = 0;
        $res->save();
        return true;
    }

    /**
     * 插件是否注册
     */
    public static function isArrow($appSign)
    {
        $res = self::getBySign($appSign);
        if (empty($res)) {
            return false;
        }
        if ($res->init == 1) {
            return true;
        }
        return false;
    }
}
