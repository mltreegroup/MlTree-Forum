<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function createStr($length){
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
    $strlen = 62;
    while($length > $strlen){
        $str .= $str;
        $strlen += 62;
    }
    $str = str_shuffle($str);
    return substr($str,0,$length);
}

function time_format($time)//输出人性化时间
{
    if(gettype($time) === 'integer')
    {
        $time = date("Y-m-d H:i:s",$time);
    }
    $publish_timestamp=strtotime($time);
    $now=date("Y-m-d H:i:s");
    $now_timestamp=strtotime($now);
    $lag = ceil(($now_timestamp-$publish_timestamp)/60);
    $format_time=$lag."分钟前";
    if ($lag>=30) {
        switch ($lag) {
            case 30:
                $format_time="半小时前";
                break;
            case $lag>30&&$lag<60:
                $format_time=$lag."分钟前";
                break;
            case $lag>=60&&$lag<120:
                $format_time="一小时前";
                break;
            case ceil($lag/60)<24:
                $format_time=(ceil($lag/60)-1)."小时前";
                break;
            case ceil($lag/60)>24&&ceil($lag/60)<48:
                $format_time="昨天".date("H:i", $publish_timestamp);
                break;
            case ceil($lag/60)>48:
                $format_time=date("Y-m-d H:i", $publish_timestamp);
                break;
        }
    }
    return $format_time;
}

function password_encode($password,$salt)
{
    $hash = password_hash($password.$salt, PASSWORD_BCRYPT);
    return $encode;
}

function password_decode($password,$hash)
{
    return password_verify($password,$hash);
}