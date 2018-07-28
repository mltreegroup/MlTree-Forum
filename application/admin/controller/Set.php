<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;
use app\admin\controller\Base;
use app\index\model\Option;
use app\index\model\Group;
use app\index\model\Mail;
use app\index\model\Forum;
use app\index\model\Topic;
use app\common\model\Message;

class Set extends Base
{
    public function base()
    {
        
        $base = Option::getValues();
        if (!empty(input('post.'))) {
            $data = input('post.','','htmlspecialchars');
            $res = $this->validate($data,'app\admin\validate\Set.base');
            if(!$res){
                return json(['code'=>-1,'message'=>$res]);
            }
            unset($data['__token__']);
            $data['siteFooterJs'] = \htmlspecialchars_decode($data['siteFooterJs']);
            $data['notice'] = \htmlspecialchars_decode($data['notice']);
            Option::setValues($data);
            return json(['code'=>0,'message'=>'更新成功！']);
        }
        return view('admin@set/base', [
            'base' => $base,
        ]);
    }

    public function baseReg()
    {
        $reg = Option::getValues('reg');
        $base = Option::getValues('base');
        if (Request::method() == 'POST') {
            input('post.siteStatus') == '1' ? $data['siteStatus'] = 1 : $data['siteStatus'] = 0;
            input('post.regStatus') == '1' ? $data['regStatus'] = 1 : $data['regStatus'] = 0;
            input('post.full') == '1' ? $data['full'] = 1 : $data['full'] = 0;
            input('post.regMail') == '1' ? $data['regMail'] = 1 : $data['regMail'] = 0;
            input('post.allowQQreg') == '1' ? $data['allowQQreg'] = 1 : $data['allowQQreg'] = 0;

            $data['defaulegroup'] = input('post.defaulegroup');
            $data['closeContent'] = input('post.closeContent');
            $data['__token__'] = input('post.__token__');

            $res = $this->validate($data,'app\admin\validate\Set.baseReg');
            if(true !== $res){
                return json(['code'=>-1,'message'=>$res]);
            }

            Option::setValues($data);
            return json(['code'=>0,'message'=>'更新成功！']);
        }

        return view('admin@set/baseReg', [
            'reg' => $reg,
            'base' => $base,
            'userGroup' => Group::all(),
        ]);
    }

    public function baseTheme()
    {
        $primaryList= [ // 允许使用的主题色
        '姨妈红'=> 'red',
        '少女粉'=> 'pink',
        '基佬紫'=> 'purple',
        '胖次蓝'=> 'blue',
        '早苗绿'=> 'green',
        '伊藤橙'=> 'orange',
        '呆毛黄'=> 'yellow',
        '远坂棕'=> 'brown',
        '靛'=> 'indigo',
        '青'=> 'cyan',
        '水鸭'=> 'teal',
        '性冷淡'=> 'grey',
        ];
        $accentList=[ // 允许使用的强调色
        '姨妈红'=> 'red',
        '少女粉'=> 'pink',
        '基佬紫'=> 'purple',
        '胖次蓝'=> 'blue',
        '早苗绿'=> 'green',
        '伊藤橙'=> 'orange',
        '呆毛黄'=> 'yellow',
        '靛'=> 'indigo',
        '青'=> 'cyan',
        ];
        $layoutList=[ // 允许使用的模式
        '日间模式'=> 'light',
        '夜间模式'=> 'black',
        ];
        if (request()->isPost()) {
            $data = input('post.','','htmlspecialchars');
            $data = input('post.', '', 'htmlentities');
            if (!input('?post.discolour')) {
                $data['discolour'] = 'false';
            }
            $res = $this->validate($data,'app\admin\validate\Set.baseTheme');
            if(true !== $res){
                return json(['code'=>-1,'message'=>$res]);
            }
            Option::setValues($data);
            return json(['code'=>'0','message'=>'更新成功','time'=>time()]);
        }
        return view('admin@set/baseTheme', [
            'primaryList' => $primaryList,
            'accentList' => $accentList,
            'layoutList' => $layoutList,
        ]);
    }

    public function baseMail()
    {
        $mail = Option::getValues('email');
        $template = Option::getValues('mailTemplate');

        if (!empty(input('post.'))) {
            $type = input('post.type');
            $data = input('post.','','htmlspecialchars');
            if ($type == 'mailBase') {
                $res = $this->validate($data,'app\admin\validate\Set.baseMail');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
                }
                unset($data['__token__']);
                Option::setValues(input('post.', 'htmlspecialchars'));
                return json(['code'=>0,'message'=>'更新成功！']);
            } elseif ($type == 'sendTest') {
                $mail = new Mail;
                $res = $mail->send(input('post.sendTo'), input('post.sendTo'), input('post.title'), input('post.content'));

                if ($res) {
                    return json(['code'=>0,'message'=>'发送成功，请查收']);
                } else {
                    return json(['code'=>1,'message'=>$mail->errorMsg]);
                }
            } elseif ($type == 'Template') {
                Option::setValues(input('post.'));
                return json(['code'=>0,'message'=>'更新成功！']);
            }
        }

        return view('admin@set/baseMail', [
            'mail' => $mail,
            'template' => $template,
        ]);
    }

    public function forum()
    {
        $fourm = Db::name('forum')->select();

        if (!empty(input('post.'))) {
            if (empty(input('post.fid'))) {
                $res = $this->validate(input('post.'),'app\admin\validate\Set.forum');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
                }
                Forum::create(input('post.'));
                return json(['code'=>0,'message'=>'添加板块成功']);
            } else {
                $res = $this->validate(input('post.'),'app\admin\validate\Set.forum');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
                }
                $res = Db::name('forum')->where('fid', input('post.fid'))->find();
                if (empty($res)) {
                    return json(['code'=>'2041','message'=>'该板块不存在！']);
                }
                Db::name('forum')->update(input('post.'));
                return json(['code'=>0,'message'=>'修改板块成功']);
            }
        }

        return view('admin@set/forum', [
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
                return view('admin@set/topic', [
                    'topicData' => $res,
                    'forumData' => $forum,
                ]);
            }
            $topic = new Topic;
            $res = $topic->setTopic(input('post.type'), input('post.tid'), input('post.fid'));
            if ($res[0]) {
                return json(\outResult(0, '移动成功'));
            } else {
                return json(\outResult(-1, $res[1]));
            }
        }

        return view('admin@set/topic', [
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
                 $res = $this->validate(input('post.'),'app\admin\validate\Set.link');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
                }
                Db::name('links')->strict(false)->insert(input('post.'));
                return json(['code'=>0,'message'=>'添加成功','time'=>time()]);
            } else {
                $res = $this->validate(input('post.'),'app\admin\validate\Set.link');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
                }
                $res = Db::name('links')->where('Id', input('post.Id'))->find();
                if (empty($res)) {
                    return json(['code'=>'2050','message'=>'该Link不存在！']);
                }
                Db::name('links')->strict(false)->update(input('post.'));
                return json(['code'=>0,'message'=>'修改Link成功']);
            }
        }
        return view('admin@set/forumsetting', [
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
                $res = $this->validate(input('post.'),'app\admin\validate\Set.user');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
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
                return json(outResult(0, '修改成功'));
            } elseif (input('?post.type') && input('?post.type')=='search') {
                $res =  Db::name('user')->where('uid|username|email', 'like', '%'.input('post.keyword').'%');
                return view('admin@set/user', [
                    'userData' => $data,
                    'groupList' => $group,
                ]);
            } else {
                $res = $this->validate(input('post.'),'app\admin\validate\Set.user');
                if(true !== $res){
                    return json(['code'=>-1,'message'=>$res]);
                }
                $data = input('post.');
                $data['password'] = \password_encode($data['password']);
                Db::name('user')->strict(false)->insert($data);
                return json(\outResult(0, '添加成功'));
            }
        }
        return view('admin@set/user', [
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
                        $data['rules'] .= $value['id'].',';
                    } else {
                        $data['rules'] .= $value['id'];
                    }
                }
            }
            $res = $this->validate($data,'app\admin\validate\Set.group');
            if(true !== $res){
                return json(['code'=>-1,'message'=>$res]);
            }
            if (!isset($data['ID'])) {
                Db::name('group')->strict(false)->insert($data);
                return json(\outResult(0, '添加成功'));
            } else {
                Db::name('group')->where('gid', $data['Id'])->strict(false)->update($data);
                return json(\outResult(0, '修改成功'));
            }
        }
        return view('admin@set/userGroup', [
            'groupData' => $data,
        ]);
    }

    public function Auth()
    {
        $data = Db::name('auth_rule')->select();

        if (request()->isPost()) {
            if (input('?post.type')) {
                if (input('post.type') == 'set') {
                    input('post.value') == 'true' ? $data = [input('post.sign', '', 'strtolower')=>'1'] : $data = [input('post.sign', '', 'strtolower')=>'0'];
                    $name = input('post.name', '', 'strtolower');
                    if($name == 'admin')
                    {
                        return json(outResult(-1, '拒绝修改Admin权限'));
                    }
                    $res = Db::name('auth_rule')->where('name', $name)->update($data);
                    return json(outResult(0, 'Success'));
                }
            }
            
            $res = Db::name('auth_rule')
            ->where('title', input('post.title'))
            ->find();
            if (!empty($res)) {
                return json(outResult(-1, '权限重复'));
            }
            $res = $this->validate(input('post.'),'app\admin\validate\Set.auth');
            if(true !== $res){
                return json(['code'=>-1,'message'=>$res]);
            }
            Db::name('auth_rule')->strict(false)->insert(input('post.'));
            return json(\outResult(0, '添加成功'));
        }
        return view('admin@set/Auth', [
            'Auth' => $data,
        ]);
    }

    public function Expand()
    {
        if (request()->isPost()) {
            $uid = explode(',', input('post.uid'));
            $res = $this->validate(input('post.'), 'app\admin\validate\Set.message');
            if (true !== $res) {
                return json(['code' => -1, 'message' => $res]);
            }
            if (count($uid) == 1) {
                $msg = new Message;
                $res = $msg->addMessage($uid[0], session('uid'), input('post.title'), input('post.content'));
                return json(\outResult(0, '发送成功'));
            }
            $msg = new Message;
            $msg->addAllMessage($uid, session('uid'), input('post.title'), input('post.content'));
            return json(\outResult(0, '发送成功'));
        }
        return view('admin@set/expand');
    }
}
