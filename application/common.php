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
require __DIR__ . '/../vendor/autoload.php';

function createStr($length)
{
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //62个字符
    $strlen = 62;
    while ($length > $strlen) {
        $str .= $str;
        $strlen += 62;
    }
    $str = str_shuffle($str);
    return substr($str, 0, $length);
}

//输出人性化时间
function time_format($time)
{
    if (gettype($time) === 'integer') {
        $time = date("Y-m-d H:i:s", $time);
    }
    $publish_timestamp = strtotime($time);
    $now = date("Y-m-d H:i:s");
    $now_timestamp = strtotime($now);
    $lag = ceil(($now_timestamp - $publish_timestamp) / 60);
    $format_time = $lag . "分钟前";
    if ($lag >= 30) {
        switch ($lag) {
            case 30:
                $format_time = "半小时前";
                break;
            case $lag > 30 && $lag < 60:
                $format_time = $lag . "分钟前";
                break;
            case $lag >= 60 && $lag < 120:
                $format_time = "一小时前";
                break;
            case ceil($lag / 60) < 24:
                $format_time = (ceil($lag / 60) - 1) . "小时前";
                break;
            case ceil($lag / 60) > 24 && ceil($lag / 60) < 48:
                $format_time = "昨天" . date("H:i", $publish_timestamp);
                break;
            case ceil($lag / 60) > 48:
                $format_time = date("Y-m-d H:i", $publish_timestamp);
                break;
        }
    }
    return $format_time;
}

/**
 * 用于返回格式化json信息
 * @param [all] $data [require|返回信息、数据]
 * @param [int] $code [返回信息码]
 * @param [srt] $url  [跳转Url]
 * @param [str] $sign [事件标识]
 */
function outRes($code = 0, $data, $url = null, $sign = 'msg')
{
    $time = time();
    if (!empty($url)) {
        return json(['code' => $code, 'url' => $url, $sign => $data, 'time' => $time]);
    }
    return json(['code' => $code, $sign => $data, 'time' => $time]);
}

// function markdownEncode($text)
// {
//     $parser = new Parsedown;
//     $html = $parser->text($text);
//     return $html;
// }

/**
 * 获取当前URL
 */
function CurrentURL()
{
    $url = $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
    if (!empty($_SERVER["QUERY_STRING"])) {
        $url = $url . '?' . $_SERVER["QUERY_STRING"];
    }
    return $url;
}

/**
 * 获取来路URL
 */
function IncomingURL()
{
    $url = null;
    if (!empty($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    }
    return $url;
}

/**
 * 密码加密函数封装
 */
function password_encode($password)
{
    $hash = password_hash($password, PASSWORD_BCRYPT);
    return $hash;
}

/**
 * 取指定时间间隔后的时间
 */
function toTime($number, $part, $type = '+')
{
    if ($type == '+') {
        switch ($variable) {
            case 'value':
                # code...
                break;

            default:
                # code...
                break;
        }
        strtotime('+5 minutes');
    }

}

/**
 * 回复格式解析
 */
function replyRegular($str)
{
    $pre = '{@(\d+)/(\d+)}';
    if (preg_match($pre, $str, $arr)) {
        $user = Db::name('user')->where('uid', $arr[1])->find();
        $html = '回复 <a href="' . url('index/user/index', ['uid' => $arr[1]]) . '">@' . $user['username'] . '</a>';
        $html .= '：<a href="#replu-content-' . $arr[2] . '">#' . $arr[2] . '</a>';
        return [$arr[0], $arr[1], $arr[2], $html];
    }
    return 'error';
}