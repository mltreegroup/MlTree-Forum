<?php
namespace app\index\controller;

use app\index\controller\Base;
use \app\index\model\User;
use \app\index\model\Atta;

class Expand extends Base
{
    public function upload()//普通上传类
    {

       $file = request()->file('file');

        if (empty($file)) {
            return json(array('code'=>1,'errmsg'=>'上传失败,文件为空.'));
        }

        $info = $file->move('./uploads');
        
        if ($info) {
            $path = $info->getSaveName();
            $fileData = [
                'uid'=>input('post.uid'),
                'fileName'=>$file->getinfo()['name'],
                'url'=>'/uploads/'.$path,
            ];
            Atta::create($fileData);
            return json(array('code'=>0,'url'=>'/uploads/'.$path,'msg'=>'上传成功！','file'=>$file->getinfo()['name']));
        } else {
            return json(array('code'=>1,'errmsg'=>'上传失败'));
        }
    }

    public function avatarUpload()//用户头像上传方法
    {
        $file = request()->file('avatar');
        
        if (empty($file)) {
            return json(array('code'=>1,'errmsg'=>'上传失败,文件为空.'));
        }
        
        $info = $file->move('./avatar');
                
        if ($info) {
            $path = $info->getSaveName();

            $user = new User;
            $user->save(['avatar'=>'/avatar/'.$path],['uid'=>input('post.uid')]);

            return json(array('code'=>0,'url'=>'/avatar/'.$path,'msg'=>'上传成功！','file'=>$file->getinfo()['name']));
        } else {
            return json(array('code'=>1,'errmsg'=>'上传失败'));
        }
    }

    public function picUpload()
    {
        $file = request()->file('file');

        if (empty($file)) {
            return json(array('code'=>1,'errmsg'=>'上传失败,文件为空.'));
        }

        $info = $file->move('./uploads');

        $path = $info->getSaveName();
        $data[] = '/uploads/'.$path;

        if ($info) {
            return json(array('errno'=>0,'data'=>$data,'msg'=>'上传成功！','file'=>$file->getinfo()['name']));
        } else {
            return json(array('code'=>1,'errmsg'=>'上传失败'));
        }
    }

}
