<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;

class Api extends Base
{
    public function index()
    {
        return ;
    }

    public function del()
    {
        if (!empty(input('post.'))) {
            if (input('post.type') == 'link') {
                $res = Db::transaction(function () {
                    Db::name('links')->find(input('post.id'));
                    Db::name('links')->delete(input('post.id'));
                });
                if (empty($res)) {
                    return json(['code'=>0,'message'=>'删除成功！','time'=>time()]);
                } else {
                    return json(['code'=>'4033','message'=>'删除失败！','time'=>time()]);
                }
            } elseif (input('post.type') == 'forum') {
                $res = Db::transaction(function () {
                    Db::name('forum')->find(input('post.id'));
                    Db::name('forum')->delete(input('post.id'));
                });
                if (empty($res)) {
                    return json(['code'=>0,'message'=>'删除成功！','time'=>time()]);
                } else {
                    return json(['code'=>'4033','message'=>'删除失败！','time'=>time()]);
                }
            }
        }

        return json(['code'=>0,'message'=>'AdminDeleteApi','time'=>time()]);
    }
}
