<?php
namespace app\common\model;

require_once 'vendor/autoload.php';
use app\common\model\Option;
use think\Model;
use Upyun\Config;

class Upload extends Model
{
    public $uploadType;
    public $serverUrl;
    /**
     * 生成上传鉴权数据，需要拓展的上传方法请尽量遵循此规则
     */
    public static function getClient()
    {
        $option = Option::getValues('upload');
        $uploadType = $option['uploadType'];
        switch ($option['uploadType']) {
            case 'upyun':
                $serverUrl = $option['upyunUrl'];
                $config = new Config($option['upyunServiceName'], $option['upyunUser'], $option['upyunPwd']);
                $client = new Upyun($config);
                return $client;
                break;

            default:
                return true;
                break;
        }

    }

    /**
     * 执行上传方法，需要拓展的上传方法请尽量遵循此规则
     * @param [str] $path [服务端文件保存路径]
     * @param [str] $fileUri [本地文件保存路径]
     * @return [array] [逻辑值,远程文件地址(错误信息),服务端返回信息(可选)]
     */
    public function upload($fileName, $fileUri)
    {
        switch ($uploadType) {
            case 'upyun':
                $file = fopen($fileUri);
                if (empty($file)) {
                    return [false, '文件不存在，URI:' . $fileUri];
                }
                $client = self::getClient();
                $path = date('Ymd', time()) . $fileName;
                $res = $client->write($path, $file);
                return [true, $serverUrl . $path, $res];
                break;
            //case ''  //其他上传方式
            default:
                return [true, $fileUri, '本地文件'];
                break;
        }
    }
}
