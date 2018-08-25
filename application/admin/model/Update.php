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

    static function getUpdateList()
    {
        //$list = curlGet('https://forum.kingsr.cc/api/getUpdateList');
        $list = '{"MlTreeForum":{"ver":"1.0.1","list":[{"ver":"1.0.1","time":"2018.06.01","url":"https:\/\/github.com\/mltreegroup\/MlTree-Fourm\/archive\/1.0.1.zip","illustrate":"此版本适合Ver1.0.0+等程序的升级更新，更新评级：必须","assess":"必须"},{"ver":"1.0.0","time":"2018.06.01","url":"https:\/\/github.com\/mltreegroup\/MlTree-Fourm\/archive\/1.0.0.zip","illustrate":"基础版本，无需下载","assess":"必须"}]}}';
        $list = \json_decode($list);
        return $list->MlTreeForum;
    }
    
    public function verUpdate()
    {
        $list = slef::getUpdateList();
    }
}

