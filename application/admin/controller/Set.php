<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;
use app\admin\controller\Base;
use app\admin\model\Option;
use app\admin\model\Group;
use app\admin\model\Mail;

class Set extends Base
{
    protected function initialize()
    {
        if (!empty(session('uid'))) {
            $user = model('user');
            $this->assign('userData',$user->getInfo(session('uid')));
        }
    }

    public function index()
    {
        return;
    }

    public function base()
    {
        $base = Option::getValues();
        if(!empty(input('post.')))
        {
            Option::setValues(input('post.','htmlspecialchars'));
            return json(['code'=>0,'message'=>'更新成功！']);
        }
        return view('base',[
            'base' => $base,
        ]);
    }

    public function baseReg()
    {
        $reg = Option::getValues('reg');
        $base = Option::getValue('siteStatus');
        if(Request::method() == 'POST')
        {
            if(empty(input('post.siteStatus')))
            {
                $data['siteStatus'] = 0;
            }else {
                $data['siteStatus'] = 1;
            }

            if(empty(input('post.regStatus')))
            {
                $data['regStatus'] = 0;
            }else {
                $data['regStatus'] = 1;
            }

            if(empty(input('post.regMail')))
            {
                $data['regMail'] = 0;
            }else {
                $data['regMail'] = 1;
            }

            $data['defaulegroup'] = input('post.defaulegroup');

            Option::setValues($data);
            return json(['code'=>0,'message'=>'更新成功！']);
        }

        return view('baseReg',[
            'reg' => $reg,
            'siteStatus' => $base,
            'userGroup' => Group::all(),
        ]);
    }

    public function baseTem()
    {

        return view('baseTem',[

        ]);
    }

    public function baseMail()
    {
        $mail = Option::getValues('email');
        $template = Option::getValues('mailTemplate');

        if(!empty(input('post.')))
        {
            $type = input('post.type');

            if ($type == 'mailBase') {
                Option::setValues(input('post.','htmlspecialchars'));
                return json(['code'=>0,'message'=>'更新成功！']);
            }elseif ($type == 'sendTest') {

                $mail = new Mail;
                $res = $mail->send(input('post.sendTo'),input('post.sendTo'),input('post.title'),input('post.content'));

                if($res)
                {
                    return json(['code'=>0,'message'=>'发送成功，请查收']);
                }else{
                    return json(['code'=>1,'message'=>$mail->errorMsg]);
                }

            }elseif ($type == 'Template') {
                Option::setValues(input('post.'));
                return json(['code'=>0,'message'=>'更新成功！']);
            }
        }

        return view('baseMail',[
            'mail' => $mail,
            'template' => $template,
        ]);
    }

    public function forum()
    {
        $fourm = Db::name('forum')->select();

        if(!empty(input('post.')))
        {
            if(empty(input('post.fid')))
            {
                Db::name('forum')->strict(false)->insert(input('post.'));
                return json(['code'=>0,'message'=>'添加板块成功']);
            }else{
                $res = Db::name('forum')->where('fid',input('post.fid'))->find();
                if(empty($res))
                {
                    return json(['code'=>'2041','message'=>'该板块不存在！']);
                }
                Db::name('forum')->update(input('post.'));
                return json(['code'=>0,'message'=>'修改板块成功']);
            }
            
        }

        return view('forum',[
            'forumData' => $fourm,
        ]);
    }

    public function topic()
    {
        $topic = Db::name('topic')->select();
        foreach ($topic as $key => $value) {
            $value['content'] = strip_tags($value['content']);
            $topic[$key] = $value;
        }
        return view('topic',[
            'topicData' => $topic,
        ]);
    }

    public function forumsetting()
    {
        $link = Db::name('links')->order('sold')->select();

        if (!empty(input('post.'))) {
            if(empty(input('post.Id')))
            {
                Db::name('links')->strict(false)->insert(input('post.'));
                return json(['code'=>0,'message'=>'添加成功','time'=>time()]);
            }else{
                $res = Db::name('links')->where('Id',input('post.Id'))->find();
                if(empty($res))
                {
                    return json(['code'=>'2050','message'=>'该Link不存在！']);
                }
                Db::name('links')->update(input('post.'));
                return json(['code'=>0,'message'=>'修改Link成功']);
            }
        }
        return view('forumsetting',[
            'links' => $link,
        ]);
    }

    public function user()
    {
        $data = Db::name('user')->select();

        return view('user',[
            'userData' => $data,
        ]);
    }

    public function userGroup()
    {
        $data = Db::name('group')->select();

        return view('userGroup',[
            'groupData' => $data,
        ]);
    }

    public function Auth()
    {
        $data = Db::name('auth_rule')->select();

        return view('Auth',[
            'Auth' => $data,
        ]);
    }
}