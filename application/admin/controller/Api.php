<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;

class Api extends Base
{
    public function index()
    {
        return 'Welcome to MlTree Forum';
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
                if (input('post.id') == 1) {
                    return json(\outResult(-1, '不能删除fid为1的板块'));
                }
                $res = Db::transaction(function () {
                    Db::name('topic')->where('fid', input('post.id'))->setField('fid', '1');
                    Db::name('forum')->find(input('post.id'));
                    Db::name('forum')->delete(input('post.id'));
                });
                if (empty($res)) {
                    return json(['code'=>0,'message'=>'删除成功！','time'=>time()]);
                } else {
                    return json(['code'=>'4033','message'=>'删除失败！','time'=>time()]);
                }
            } elseif (input('post.type') == 'topic') {
                $res = Db::transaction(function () {
                    $data = Db::name('topic')->where('tid',input('post.id'))->find();
                    Db::name('user')->where('uid',$data['uid'])->setDec('topics');
                    Db::name('topic')->delete(input('post.id'));
                });
                if (empty($res)) {
                    return json(['code'=>0,'message'=>'删除成功！','time'=>time()]);
                } else {
                    return json(['code'=>'4033','message'=>'删除失败！','time'=>time()]);
                }
            } elseif (input('post.type') == 'group') {
                if (input('post.id') == 1 || input('post.id') == 2) {
                    return json(\outResult(-1, '不能删除Gid为1或2的用户组'));
                }
                $res = Db::transaction(function () {
                    Db::name('user')->where('gid', input('post.id'))->update(['gid'=>2]);
                    Db::name('group')->delete(input('post.id'));
                });
                if (empty($res)) {
                    return json(['code'=>0,'message'=>'删除成功！','time'=>time()]);
                } else {
                    return json(['code'=>'4033','message'=>'删除失败！','time'=>time()]);
                }
            } elseif (input('post.type') == 'auth') {
                if (input('post.id') <= 11) {
                    return json(\outResult(-1, '不能删除必须的权限(序号小于11)'));
                }
                $res = Db::transaction(function () {
                    Db::name('auth_rule')->delete(input('post.id'));
                });
                if (empty($res)) {
                    return json(['code'=>0,'message'=>'删除成功！','time'=>time()]);
                } else {
                    return json(['code'=>'4033','message'=>'删除失败！','time'=>time()]);
                }
            } elseif (input('post.type') == 'user') {
                if (input('post.id') == 1) {
                    return json(\outResult(-1, '禁止删除创始人！'));
                }
                $res = Db::transaction(function () {
                    Db::name('user')->delete(input('post.id'));
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
