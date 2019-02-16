<?php
namespace app\forum\controller;

use app\common\model\Mail;
use app\common\model\Upload;
use app\common\model\User;
use app\forum\controller\Base;

class Expand extends Base
{
    public function index()
    {
        return json(['code' => 0, 'apilist' => '', 'time' => time()]);
    }

    public function uploadFile()
    {
        $file = request()->file('file');
        $info = $file->move('../uploads');
        if ($info) {
            $upload = new Upload;
            $upload->upload($info->getFilename());
        }
    }

    public function uploadAvatar()
    {
        $file = request()->file('avatar');
        if (empty($file)) {
            return outRes(-1, '上传失败,文件为空.');
        }

        $info = $file->move(getRootPath() . 'public/avatar/');

        if ($info) {
            $path = $info->getSaveName();
            $user = new User;
            $user->save(['avatar' => '/avatar/' . $path], ['uid' => input('post.uid')]);
            return json(array('code' => 0, 'url' => '/avatar/' . $path, 'msg' => '上传成功！', 'file' => $file->getinfo()['name']));
        } else {
            return json(array('code' => 1, 'msg' => '上传失败'));
        }
    }

    public function rss()
    {

    }

    public function getForgetMailCode($mail)
    {
        $user = User::getByEmail($mail);
        if (empty($user)) {
            return outRes(-1, '用户不存在', url('forum/index/index'));
        }
        $code = createStr(32);
        $mail = new Mail;
        $mail->SendForgetCode($user, $code);
        session('forget.Email', $mail);
        session('forget.Time', time() + 30);
        session('forget.Code', $code);
        return outRes(0, '验证码已发送');

    }
}
