<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User;
use think\Db;
use app\index\model\Option;

class Index extends Base
{
    public function index()
    {
        if (!empty(session('uid'))) {
            $user = model('user');
            $this->assign('userData', user::get(session('uid')));
        }
        //输出置顶帖子
        $topic = Db::name('topic')->where('tops', 'in', '1')->order('create_time DESC')->select();

        foreach ($topic as $key => $value) {
            $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
            $value['time_format'] = time_format($value['create_time']);
            $value['userData'] = Db::name('user')->where('uid', $value['uid'])->field('username,avatar')->find();
            $value['forumName'] = Db::name('forum')->where('fid', $value['fid'])->field('name')->find()['name'];
            $topic[$key] = $value;
        }
        $this->assign('tops', $topic);
        $this->assign('option', $this->siteOption());
        return view();
    }

    public function Search($keyword = '', $type='topic')
    {
        $this->assign('option', $this->siteOption());

        switch ($type) {
            case 'topic':
                $data = Db::name('topic')->where('subject|content', 'like', '%'.$keyword.'%')->select();
                foreach ($data as $key => $value) {
                    $value['content'] = strip_tags(htmlspecialchars_decode($value['content']));
                    $value['time_format'] = time_format($value['create_time']);
                    $value['userData'] =Db::name('user')->where('uid', $value['uid'])->field('username,avatar')->find();
                    $data[$key] = $value;
                }
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
