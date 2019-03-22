<?php
namespace app\plugin\controller\uploadfile;

use app\common\model\User;
use app\plugin\controller\Base;
use Upyun\Config;
use Upyun\Upyun;

class Index extends Base
{
    public function index()
    {
        return '来，上传一下~';
    }

    /**
     * 定义一个上传方法
     */
    public function upfile()
    {
        if (!User::isLogin()) {
            return;
        }
        if (request()->isPost()) {
            $file = request()->file('file');
            $info = $file->move(\getRootPath() . '/public/uploads');
            if ($info) {
                $url = \getRootPath() . '/public/uploads/' . str_replace("\\", "/", $info->getSaveName());
                $option = Base::getValues('uploadfile');
                switch ($option['type']) {
                    case 'Upyun':
                        $serviceConfig = new Config($option['service_name'], $option['operato_name'], $option['operato_pwd']);
                        $client = new Upyun($serviceConfig);
                        $res = $client->write('uploads/' . str_replace("\\", "/", $info->getSaveName()), fopen($url, 'r'));
                        if (!empty($res)) {
                            if ($option['save_local'] != 'true') {
                                unlink($url);
                            }
                            $url = $option['url'] . '/uploads/' . str_replace("\\", "/", $info->getSaveName());
                            return \outRes(0, $url);
                        }
                        break;

                    default:
                        return \outRes(0, '/public/uploads/' . str_replace("\\", "/", $info->getSaveName()));
                        break;
                }
            }

        }

        return $this->pluginView('uploadfile', 'upload');
    }
}
