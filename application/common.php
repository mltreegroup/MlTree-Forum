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

function createStr($length=64)
{
    $string = '';
    while (($len = strlen($string)) < $length) {
        $size = $length - $len;
        $bytes = random_bytes($size);
        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }
    return $string;
}
/**
 * 输出相对时间函数的改进版本。
 * @param $time 被转换的时间戳
 * @param $disable_relative_time 禁用相对时间
 */
function time_format($time,$disable_relative_time=false){
    if(gettype($time)!=='number') $time = strtotime($time);//输入的时间竟然不一定是数字
    $now = time();
    $result = "";
    $units = [
        //时间单位
        'now'=>'刚刚',
        'sec'=>'秒',//因为是中文所以不用区分单复数
        'min'=>'分',
        'hrs'=>'时',
        'day'=>'天',
        'mon'=>'月',
        'ago_later'=>['前','后']
    ];
    $difference = abs($now-$time);
    //太久远或者强制禁用则返回绝对日期
    if($disable_relative_time||$difference>2678400) return date('Y/m/d H:i',$time);
    if($difference<=10) 
        return $units['now'];//误差不可避免
    else if($difference<60)
        $result .= $difference.$units['sec'];
    else if($difference<3600)
        $result .= (int)($difference/60).$units['min'];
    else if($difference<86400)
        $result .= (int)($difference/3600).$units['hrs'];
    else
        $result .= (int)($difference/86400).$units['day'];
    return $result.$units['ago_later'][(bool)($now<$time)];
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