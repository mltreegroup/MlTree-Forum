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
        $html = '回复 <a href="' . url('forum/user/index', ['uid' => $arr[1]]) . '">@' . $user['username'] . '</a>';
        $html .= '：<a href="#reply-content-' . $arr[2] . '">#' . $arr[2] . '</a>';
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

/**
 * CURL_GET
 * @param $url
 * @return mixed
 */

function curlGet($url, $ssl = true)
{
    // 1. 初始化
    $ch = curl_init();
    // 2. 设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if (!$ssl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    // 3. 执行并获取HTML文档内容
    $output = curl_exec($ch);
    if ($output === false) {
        echo "CURL Error:" . curl_error($ch);
    }
    // 4. 释放curl句柄
    curl_close($ch);
    return $output;
}

/**
 * CURL_POST
 * @param $url
 * @param $postData
 * @return mixed
 */
function curlPost($url, $postData = null, $ssl = true)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    if (!$ssl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    $ch_arr = array(CURLOPT_TIMEOUT => 3, CURLOPT_RETURNTRANSFER => 1);
    curl_setopt_array($ch, $ch_arr);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

/**
 * 用于检测是否已安装 由pulic/install/install.lock决定
 * @return bool
 */
function isInstall()
{
    if (file_exists(getRootPath() . 'public/install/install.lock')) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取插件访问URL
 */
function pluginUrl($appSign, $path, $data = [])
{
    \think\facade\Route::any($appSign . '/' . $path, 'plugin/' . $appSign . '.' . $path);
    return url('/plugin/' . $appSign . '.' . $path, $data);
}

/**
 * 获取插件静态资源URL
 */
function pluginAssetsUrl($appSign, $url)
{
    return url('forum/api/pluginAssets', ['appSign' => $appSign, 'url' => $url]);
}

/**
 * 文件类型检测
 * @param string $url 文件绝对路径
 */
function getFileMime($url = '')
{
    $finfo = finfo_open(FILEINFO_MIME);
    $mime = finfo_file($finfo, $url);
    finfo_close($finfo);
    return $mime;
}

/**
 * 获取指定目录下的子目录
 * @param string $path 相对于根目录的目录路径
 * @param bool $abs 是否生成绝对路径
 * @param bool $dir 是否只包含目录
 * @return array $files 子目录数组
 */
function get_dir($path, $abs = false, $dir = true)
{
    $path = getRootPath() . $path;
    $dirs = scandir($path);
    foreach ($dirs as $key => $value) {
        if ($value !== "." && $value !== "..") {
            if ($dir) {
                if (is_dir($path . $value)) {
                    if ($abs) {
                        $files[] = [
                            "abs" => $path . $value . DIRECTORY_SEPARATOR,
                            "rel" => $value . DIRECTORY_SEPARATOR,
                        ];
                    } else {
                        $files[] = $value . DIRECTORY_SEPARATOR;
                    }
                }
            } else {
                if ($abs) {
                    $files[] = [
                        "abs" => $path . $value . DIRECTORY_SEPARATOR,
                        "rel" => $value . DIRECTORY_SEPARATOR,
                    ];
                } else {
                    $files[] = $value . DIRECTORY_SEPARATOR;
                }
            }

        }
    }
    return $files;
}
