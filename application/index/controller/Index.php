<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Topic;
use app\common\model\Message;
use app\index\model\User;

class Index extends Base
{
    public function index()
    {
        // return json([
        //     'MlTreeForum' => [
        //         'ver'=>'1.0.1',
        //         'list'=>[   
        //             [
        //                 'ver'=>'1.0.1',
        //                 'time'=>'2018.06.01',
        //                 'url'=>'https://github.com/mltreegroup/MlTree-Fourm/archive/1.0.1.zip',
        //                 'illustrate'=>'此版本适合Ver1.0.0+等程序的升级更新，更新评级：必须',
        //                 'assess'=>'必须',
        //             ],
        //             [
        //                 'ver'=>'1.0.0',
        //                 'time'=>'2018.06.01',
        //                 'url'=>'https://github.com/mltreegroup/MlTree-Fourm/archive/1.0.0.zip',
        //                 'illustrate'=>'基础版本，无需下载',
        //                 'assess'=>'必须',
        //             ]
        //         ],
        //     ],
        // ]);
        $topic = new Topic();
        $tops = $topic->getTops();
        $this->assign('tops', $tops);
        return view();
    }

    public function Search($keyword = '', $type='topic')
    {
        $this->assign('option', $this->siteOption('搜索 - '.$keyword));
        switch ($type) {
            case 'topic':
                $topic = new Topic();
                $data = $topic->Search($keyword);
                break;
            
            default:
                return ;
                break;
        }

        return view('search', [
            'data' => $data,
            'count' => count($data),
            'keyword' => $keyword,
        ]);
    }

    public function _error()
    {
        $data = [
                    'title' => '站点正在进行闭站维护……',
                    'content' => Option::getValue('closeContent'),
                ];
        $this->assign('information', $data);
        $this->assign('option', $this->siteOption('出现错误'));
        return view('error');
    }
}
