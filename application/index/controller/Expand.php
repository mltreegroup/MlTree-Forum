<?php
namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\User;
use app\index\model\Atta;
use app\index\model\Option;
use think\Db;

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
                'uid'=>(int)input('post.uid'),
                'fileName'=>$file->getinfo()['name'],
                'url'=>'/uploads/'.$path,
                'sign'=>input('post.sign', '', 'htmlspecialchars')
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
            $user->save(['avatar'=>'/avatar/'.$path], ['uid'=>input('post.uid')]);

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

    public function goLink($url = null)
    {
        if (Option::getValue('golink') == 1) {
            dump($url);
            $url = url('index/expand/golink', ['url'=>$url]);
            return view('golink', [
                'url' => $url
            ]);
        } else {
            return $this->redirect($url);
        }
    }

    // public function downFile($aid = null)
    // {
    //     if (!empty($aid)) {
    //         $atta = Db::name('atta')->where('aid', $aid)->find();

    //         $file_path = $atta['url'];
    //         dump($file_path);
    //         dump(file_exists($file_path));
    //         $buffer = 102400; //一次返回102400个字节
    //         if (!file_exists($file_path)) {
    //             echo "<script type='text/javascript'> alert('对不起！该文件不存在或已被删除！'); </script>";
    //             return;
    //         }
    //         $fp = fopen($file_path, "r");
    //         $file_size = filesize($file_path);
    //         $file_data = '';
    //         while (!feof($fp)) {
    //             $file_data .= fread($fp, $buffer);
    //         }
    //         fclose($fp);
  
    //         //Begin writing headers
    //         header("Pragma: public");
    //         header("Expires: 0");
    //         header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //         header("Cache-Control: public");
    //         header("Content-Description: File Transfer");
    //         header("Content-type:application/octet-stream;");
    //         header("Accept-Ranges:bytes");
    //         header("Accept-Length:{$file_size}");
    //         header("Content-Disposition:attachment; filename={$atta['fileName']}");
    //         header("Content-Transfer-Encoding: binary");
    //         echo $file_data;

    //         Db::name('atta')->where('aid', $aid)->setInc('score');
    //     }
    // }
}
