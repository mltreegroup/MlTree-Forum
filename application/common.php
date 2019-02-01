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
use Auth\Auth;
use think\facade\Env;

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

/**
 * 快速检查权限，无需实例化
 * @param  string|array  $name     需要验证的规则列表，支持逗号分隔的权限规则或索引数组
 * @param  integer  $uid      认证用户ID
 * @param  string   $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and' 则表示需满足所有规则才能通过验证
 * @param  string   $mode     执行check的模式
 * @param  integer  $type     规则类型
 * @return boolean           通过验证返回true;失败返回false
 */
function fastAuth($name, $uid, $relation = 'or', $mode = 'url', $type = 1)
{
    $auth = new Auth;
    return $auth->check($name, $uid, $relation, $mode, $type);
}

/**
 * 输出帖子的徽章标识
 * @param array $data 帖子查询出的结果，包含top,essence,closed等信息
 * @return string $value 徽章字符串
 */
function outBadge($data)
{
    $value = '';
    if ($data['tops'] == 1) {
        $value = '<i class="mdui-icon iconfont icon-zhiding mdui-color-indigo-accent mtf-icon-size" title="置顶"></i> ';
    }
    if ($data['essence'] == 1) {
        $value = $value . '<i class="mdui-icon iconfont icon-jinghua1 mdui-color-orange-accent mtf-icon-size" title="精华"></i> ';
    }
    if ($data['closed'] == 1) {
        $value = $value . '<i class="mdui-icon iconfont icon-hebingxingzhuang mdui-color-red-a700 mtf-icon-size" title="关闭"></i> ';
    }

    return $value;
}

/**
 * 取运行根目录
 */
function getRootPath()
{
    return Env::get('root_path');
}