<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Atta extends Model
{
    static function setCreate($tid = 0,$files = null)//设置附件信息
    {
        if(!empty($files) && $tid != 0)
        {
            $list = \json_decode($files,true);
            foreach ($list as $key => $value) {
                Db::name('atta')->where('fileName',$value)->update(['tid'=>$tid]);
            }
            return true;
        }else {
            return false;
        }
    }
}
?>