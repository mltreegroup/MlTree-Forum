<?php
namespace app\admin\model;

use think\Model;

class Update extends Model
{
    public $ver;
    public $url;
    public $postUrl;

    public function __construct()
    {
        $this->ver = config('app.MltreeForum.ver');
        $this->url = config('app.MltreeForum.url');
        $this->postUrl = '';
    }

    public static function getUpdateList()
    {
        $list = curlGet('https://app.kingsr.cc/Api/getVersion/name/1', false);
        $list = json_decode($list);
        return $list;
    }

    public function verUpdate()
    {
        $list = slef::getUpdateList();
    }
}
