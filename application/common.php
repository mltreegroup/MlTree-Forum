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
use think\Db;
use Auth\Auth;
use Markdown\Parser;
use League\CommonMark\CommonMarkConverter;

function createStr($length)
{
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
    $strlen = 62;
    while ($length > $strlen) {
        $str .= $str;
        $strlen += 62;
    }
    $str = str_shuffle($str);
    return substr($str, 0, $length);
}

function time_format($time)//输出人性化时间
{
    if (gettype($time) === 'integer') {
        $time = date("Y-m-d H:i:s", $time);
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

function password_encode($password)
{
    $hash = password_hash($password, PASSWORD_BCRYPT);
    return $hash;
}

//计算执行耗费时间
function get_runtime()
{
    $ntime=microtime(true);
    $total=$ntime-$GLOBALS['_beginTime'];
    return round($total, 4);
}

function outTopbar()
{
    $data = Db::name('forum')->select();
    $html = '';
    foreach ($data as $key => $value) {
        $html .= '<a href="'.url('index/forum/index', ['fid'=>$value['fid']]).'" class="mdui-hidden-xs" title="'.$value['name'].'">'.$value['name'].'</a>';
    }
    return $html;
}

function outBadge($data)
{
    $value = '';
    if ($data['tops'] == 1) {
        $value = '<span class="mf-badge mf-badge-danger">置顶</span>';
    }
    if ($data['essence'] == 1) {
        $value = $value.'<span class="mf-badge mf-badge-warning">精华</span>';
    }
    if ($data['closed'] == 1) {
        $value = $value.'<span class="mf-badge">关闭</span>';
    }

    return $value;
}

function authCheck($name, $uid = 0, $relation = 'or')
{
    $auth = new Auth();
    if ($uid == 0) {
        $uid = session('uid');
    }
    return $auth->check($name, $uid, $relation);
}

function outResult($code=0, $msg, $url='')
{
    if (empty($url)) {
        return ['code'=>$code,'message'=>$msg,'time'=>time()];
    } else {
        return ['code'=>$code,'message'=>$msg,'url'=>$url,'time'=>time()];
    }
}

function markdownEncode($text)
{
    $parser = new Parser;
    $html = $parser->makeHtml($text);
    return $html;
}

// function markdownEncode($text)
// {
//     $converter = new CommonMarkConverter(['html_input' => 'escape']);
//     $html = $converter->convertToHtml($text);
//     return $html;
// }

function replyRegular($str)
{
    $pre = '{@(\d+)/(\d+)}';
    if (preg_match($pre, $str, $arr)) {
        $user = Db::name('user')->where('uid', $arr[1])->find();
        $html = '回复 <a href="' . url('index/user/index', ['uid'=>$arr[1]]) . '">@' . $user['username'] . '</a>';
        $html .= '：<a href="#replu-content-' . $arr[2] . '">#' . $arr[2] . '</a>';
        return [$arr[0],$arr[1],$arr[2],$html];
    }
    return 'error';
}

    /**
     * @param $url
     * @return mixed
     */

   function curlGet($url)
    {
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        if ($output === FALSE) {
            echo "CURL Error:" . curl_error($ch);
        }
        // 4. 释放curl句柄
        curl_close($ch);
        return $output;
    }

    /**
     * @param $url
     * @param $postData
     * @return mixed
     */
    function curlPost($url, $postData = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $ch_arr = array(CURLOPT_TIMEOUT => 3, CURLOPT_RETURNTRANSFER => 1);
        curl_setopt_array($ch, $ch_arr);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    /**
     * @param $URL
     * @param $type
     * @param $params
     * @param null $headers
     * @return mixed
     */
    function curlRequest($URL,$type,$params=null,$headers=null)
    {
        $ch = curl_init($URL);
        $timeout = 5;
        if(isset($headers)){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        }else {
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        switch ($type){
            case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, true);break;
            case "POST": curl_setopt($ch, CURLOPT_POST,true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PATCH": curl_setopt($ch, CULROPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);break;
            case "DELETE":curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
        }
        $file_contents = curl_exec($ch);//获得返回值
        return $file_contents;
        curl_close($ch);
    }

    /**
     * 用于检测是否已安装 由pulic/install/install.lock决定
     * @return true|false
     */
    function isInstall()
    {
        if (file_exists('install/install.lock')) {
            return true;
        }else{
            return false;
        }
    }