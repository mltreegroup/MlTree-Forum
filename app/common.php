<?php
// 应用公共文件

/**
 * Base64URL解码
 * @param string $data 欲解码数据
 * @return string $decode 解码后数据
 */
function base64url_decode($data)
{
    $data = str_replace(["", "-", "_"], ["=", "+", "/"], $data);
    $decode = base64_decode($data);
    return $decode;
}

/**
 * Base64URL编码
 * @param string $data 欲编码数据
 * @return string $encode 编码后数据
 */
function base64url_encode($data)
{
    $encode = base64_encode($data);
    $encode = str_replace(["=", "+", "/"], ["", "-", "_"], $encode);
    return $encode;
}

/**
 * 创建一串随机字符串
 * @param int $length 字符串长度
 */
function createStr($length = 64)
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
function time_format($time, $disable_relative_time = false)
{
    if (gettype($time) !== 'number' && gettype($time) !== 'integer') { //增加一个对整形的判断，防止错误
        $time = strtotime($time);
    }
    //输入的时间竟然不一定是数字
    $now = time();
    $result = "";
    $units = [
        //时间单位
        'now' => '刚刚',
        'sec' => '秒', //因为是中文所以不用区分单复数
        'min' => '分',
        'hrs' => '时',
        'day' => '天',
        'mon' => '月',
        'ago_later' => ['前', '后'],
    ];
    $difference = abs($now - $time);
    //太久远或者强制禁用则返回绝对日期
    if ($disable_relative_time || $difference > 2678400) {
        return date('Y/m/d H:i', $time);
    }
    if ($difference <= 10) {
        return $units['now'];
    }
    //误差不可避免
    else if ($difference < 60) {
        $result .= $difference . $units['sec'];
    } else if ($difference < 3600) {
        $result .= (int) ($difference / 60) . $units['min'];
    } else if ($difference < 86400) {
        $result .= (int) ($difference / 3600) . $units['hrs'];
    } else {
        $result .= (int) ($difference / 86400) . $units['day'];
    }

    return $result . $units['ago_later'][(bool) ($now < $time)];
}