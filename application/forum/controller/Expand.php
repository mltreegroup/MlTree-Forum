<?php
namespace app\forum\controller;

use app\common\model\User;
use app\common\model\Mail;
use app\common\model\Upload;
use app\forum\controller\Base;

class Expand extends Base
{
    public function index()
    {
        return json(['code' => 0, 'apilist' => '', 'time' => time()]);
    }

    public function uploadFile()
    {
        $file = \request()->file('file');
        $info = $file->move('../uploads');
        if ($info) {
            $upload = new Upload;
            $upload->upload($info->getFilename());
        }
    }

    public function uploadAvatar()
    {

    }

    public function rss()
    {

    }

    public function getFotgetMailCode($mail)
    {
        $user = User::getByEmail($mail);
        if (empty($user)) {
            return outRes(-1, '用户不存在', url('forum/index/index'));
        }
        $code = createStr(32);
        $mail = new Mail;
        $mail->SendForgetCode($user, $code);
        session('forget.Email',$mail);
        session('forget.Time', time() + 30);
        session('forget.Code', $code);
        return outRes(0, '验证码已发送');

    }
}
