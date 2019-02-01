<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\Forum;
use app\common\model\Group;
use app\common\model\Mail;
use app\common\model\msg;
use app\common\model\Option;
use app\common\model\Topic;
use think\Db;
use think\facade\Request;

class Set extends Base
{
    public function base()
    {

        $base = Option::getValues();
        if (!empty(input('post.'))) {
            $data = input('post.', '', 'htmlspecialchars');
            $res = $this->validate($data, 'app\admin\validate\Set.base');
            if (!$res) {
                return json(['code' => -1, 'msg' => $res]);
            }
            unset($data['__token__']);
            $data['siteFooterJs'] = \htmlspecialchars_decode($data['siteFooterJs']);
            $data['notice'] = \htmlspecialchars_decode($data['notice']);
            Option::setValues($data);
            return json(['code' => 0, 'msg' => '更新成功！']);
        }
        return $this->adminView('set/base', [
            'base' => $base,
        ]);
    }

    public function baseReg()
    {
        $reg = Option::getValues('reg');
        $base = Option::getValues('base');
        if (request()->isPost()) {
            input('post.siteStatus') == '1' ? $data['siteStatus'] = 1 : $data['siteStatus'] = 0;
            input('post.regStatus') == '1' ? $data['regStatus'] = 1 : $data['regStatus'] = 0;
            input('post.full') == '1' ? $data['full'] = 1 : $data['full'] = 0;
            input('post.emailActive') == '1' ? $data['emailActive'] = 1 : $data['emailActive'] = 0;
            input('post.allowQQreg') == '1' ? $data['allowQQreg'] = 1 : $data['allowQQreg'] = 0;

            $data['defaulegroup'] = input('post.defaulegroup');
            $data['closeContent'] = input('post.closeContent');
            $data['__token__'] = input('post.__token__');

            $res = $this->validate($data, 'app\admin\validate\Set.baseReg');
            if (true !== $res) {
                return json(['code' => -1, 'msg' => $res]);
            }

            Option::setValues($data);
            return json(['code' => 0, 'msg' => '更新成功！']);
        }

        return $this->adminView('set/baseReg', [
            'reg' => $reg,
            'base' => $base,
            'userGroup' => Group::all(),
        ]);
    }

    public function baseTheme()
    {
        $dir = get_dir("public/template/", true); //获取模板文件下的数组信息
        foreach ($dir as $key => $value) {
            $tplInfo = json_decode(file_get_contents($value['abs'] . "info.json"), true);
            if ($value['rel'] == Option::getValue("template")) {
                $start = true;
            } else {
                $start = false;
            }

            $templateList[] = [
                'info' => $tplInfo,
                'start' => $start,
                'img' => $value['abs'] . "breviary.png",
            ];
        }

        if (request()->isPost()) {
            $data = input('post.');
            $data['template'] = $data['sign'] . "/";
            $res = $this->validate($data, 'app\admin\validate\Set.baseTheme');
            if (true !== $res) {
                return json(['code' => -1, 'msg' => $res]);
            }
            Option::setValues($data);
            return json(['code' => '0', 'msg' => '更新成功', 'time' => time()]);
        }
        return $this->adminView('set/baseTheme', [
            'templateList' => $templateList,
        ]);
    }

    public function baseMail()
    {
        $mail = Option::getValues('email');
        $template = Option::getValues('mailTemplate');

        if (!empty(input('post.'))) {
            $type = input('post.type');
            $data = input('post.', '', 'htmlspecialchars');
            if ($type == 'mailBase') {
                $res = $this->validate($data, 'app\admin\validate\Set.baseMail');
                if (true !== $res) {
                    return json(['code' => -1, 'msg' => $res]);
                }
                unset($data['__token__']);
                Option::setValues(input('post.', 'htmlspecialchars'));
                return json(['code' => 0, 'msg' => '更新成功！']);
            } elseif ($type == 'sendTest') {
                $mail = new Mail;
                $res = $mail->send(input('post.sendTo'), input('post.sendTo'), input('post.title'), input('post.content'));

                if ($res) {
                    return json(['code' => 0, 'msg' => '发送成功，请查收']);
                } else {
                    return json(['code' => 1, 'msg' => $mail->errorMsg]);
                }
            } elseif ($type == 'Template') {
                Option::setValues(input('post.'));
                return json(['code' => 0, 'msg' => '更新成功！']);
            }
        }

        return $this->adminView('set/baseMail', [
            'mail' => $mail,
            'template' => $template,
        ]);
    }

    public function forum()
    {
        $fourm = Db::name('forum')->select();

        if (!empty(input('post.'))) {
            if (empty(input('post.fid'))) {
                $res = $this->validate(input('post.'), 'app\admin\validate\Set.forum');
                if (true !== $res) {
                    return json(['code' => -1, 'msg' => $res]);
                }
                Forum::create(input('post.'));
                return json(['code' => 0, 'msg' => '添加板块成功']);
            } else {
                $res = $this->validate(input('post.'), 'app\admin\validate\Set.forum');
                if (true !== $res) {
                    return json(['code' => -1, 'msg' => $res]);
                }
                $res = Db::name('forum')->where('fid', input('post.fid'))->find();
                if (empty($res)) {
                    return json(['code' => '2041', 'msg' => '该板块不存在！']);
                }
                Db::name('forum')->update(input('post.'));
                return json(['code' => 0, 'msg' => '修改板块成功']);
            }
        }

        return $this->adminView('set/forum', [
            'forumData' => $fourm,
        ]);
    }

    public function topic()
    {
        $topic = Db::name('topic')->limit(10)->select();
        $forum = Db::name('forum')->field('fid,name,cgroup')->select();
        foreach ($topic as $key => $value) {
            $value['content'] = htmlspecialchars_decode(strip_tags($value['content']));
            $topic[$key] = $value;
        }

        if (request()->isPost()) {
            if (input('post.type') == 'search') {
                $topic = new Topic;
                $res = $topic->Search(input('keyword'));
                return $this->adminView('set/topic', [
                    'topicData' => $res,
                    'forumData' => $forum,
                ]);
            }
            $topic = new Topic;
            $res = $topic->setTopic(input('post.type'), input('post.tid'), input('post.fid'));
            if ($res[0]) {
                return (outRes('移动成功', 0));
            } else {
                return (outRes($res[1], -1));
            }
        }

        return $this->adminView('set/topic', [
            'topicData' => $topic,
            'forumData' => $forum,
        ]);
    }

    public function forumsetting()
    {
        $link = Db::name('links')->order('sold')->select();
        $fourm = Option::getValues('forum');

        if (!empty(input('post.'))) {
            if (empty(input('post.Id'))) {
                $res = $this->validate(input('post.'), 'app\admin\validate\Set.link');
                if (true !== $res) {
                    return (outRes($res, -1));
                }
                Db::name('links')->strict(false)->insert(input('post.'));
                return (outRes('添加成功', 0));
            } else {
                $res = $this->validate(input('post.'), 'app\admin\validate\Set.link');
                if (true !== $res) {
                    return (outRes($res, -1));
                }
                $res = Db::name('links')->where('Id', input('post.Id'))->find();
                if (empty($res)) {
                    return json(['code' => '2050', 'msg' => '该Link不存在！']);
                }
                Db::name('links')->strict(false)->update(input('post.'));
                return json(['code' => 0, 'msg' => '修改Link成功']);
            }
        }
        return $this->adminView('set/forumsetting', [
            'links' => $link,
            'forum' => $fourm,
        ]);
    }

    public function user()
    {
        $data = Db::name('user')->limit(10)->select();
        $group = Db::name('group')->field('gid,groupName')->select();

        if (request()->isPost()) {
            if (!empty(input('post.uid'))) {
                $data = input('post.');
                $data['status'] == null ? $data['status'] = 1 : $data['status'];
                $res = $this->validate($data, 'app\admin\validate\Set.user');
                if (true !== $res) {
                    return json(['code' => -1, 'msg' => $res]);
                }
                input('?post.status') ? $status = '1' : $status = '0';
                $data = [
                    'username' => input('post.username'),
                    'gid' => input('post.gid'),
                    'email' => input('post.email'),
                    'status' => $status,
                ];
                if (!empty(input('post.password'))) {
                    $data['password'] = password_encode(input('passowrd'));
                }
                Db::name('user')->where('uid', input('post.uid'))->update($data);
                return (outRes('修改成功'));
            } elseif (input('?post.type') && input('?post.type') == 'search') {
                $res = Db::name('user')->where('uid|username|email', 'like', '%' . input('post.keyword') . '%');
                return $this->adminView('set/user', [
                    'userData' => $data,
                    'groupList' => $group,
                ]);
            } else {
                $res = $this->validate(input('post.'), 'app\admin\validate\Set.user');
                if (true !== $res) {
                    return json(['code' => -1, 'msg' => $res]);
                }
                $data = input('post.');
                $data['password'] = \password_encode($data['password']);
                Db::name('user')->strict(false)->insert($data);
                return (outRes('添加成功'));
            }
        }
        return $this->adminView('set/user', [
            'userData' => $data,
            'groupList' => $group,
        ]);
    }

    public function userGroup()
    {
        $data = Db::name('group')->select();

        if (request()->isPost()) {
            $data = input('post.');
            $authList = Db::name('auth_rule')->select();
            if (empty($data['rules'])) {
                $data['rules'] = '';
                foreach ($authList as $key => $value) {
                    if ($key != count($authList)) {
                        $data['rules'] .= $value['id'] . ',';
                    } else {
                        $data['rules'] .= $value['id'];
                    }
                }
            }
            $res = $this->validate($data, 'app\admin\validate\Set.group');
            if (true !== $res) {
                return outRes($res, -1);
            }
            if (!isset($data['ID'])) {
                Db::name('group')->strict(false)->insert($data);
                return (outRes('添加成功'));
            } else {
                Db::name('group')->where('gid', $data['ID'])->strict(false)->update($data);
                return (outRes('修改成功'));
            }
        }
        return $this->adminView('set/userGroup', [
            'groupData' => $data,
        ]);
    }

    public function Auth()
    {
        $data = Db::name('auth_rule')->select();

        if (request()->isPost()) {
            if (input('?post.type')) {
                if (input('post.type') == 'set') {
                    input('post.value') == 'true' ? $data = [input('post.sign', '', 'strtolower') => '1'] : $data = [input('post.sign', '', 'strtolower') => '0'];
                    $name = input('post.name', '', 'strtolower');
                    if ($name == 'admin') {
                        return (outRes(-1, '拒绝修改Admin权限'));
                    }
                    $res = Db::name('auth_rule')->where('name', $name)->update($data);
                    return (outRes(0, 'Success'));
                }
            }

            $res = Db::name('auth_rule')
                ->where('title', input('post.title'))
                ->find();
            if (!empty($res)) {
                return (outRes('权限重复', -1));
            }
            $res = $this->validate(input('post.'), 'app\admin\validate\Set.auth');
            if (true !== $res) {
                return json(['code' => -1, 'msg' => $res]);
            }
            Db::name('auth_rule')->strict(false)->insert(input('post.'));
            return (outRes('添加成功'));
        }
        return $this->adminView('set/Auth', [
            'Auth' => $data,
        ]);
    }

    public function Expand()
    {
        if (request()->isPost()) {
            $uid = explode(',', input('post.uid'));
            $res = $this->validate(input('post.'), 'app\admin\validate\Set.msg');
            if (true !== $res) {
                return json(['code' => -1, 'msg' => $res]);
            }
            if (count($uid) == 1) {
                $msg = new msg;
                $res = $msg->addmsg($uid[0], session('uid'), input('post.title'), input('post.content'));
                return (outRes('发送成功'));
            }
            $msg = new msg;
            $msg->addAllmsg($uid, session('uid'), input('post.title'), input('post.content'));
            return (outRes('发送成功'));
        }
        return $this->adminView('set/expand');
    }
}
