<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Topic extends Model
{
    protected $pk = 'tid';
    
    public function getTopicAttr($val,$data)
    {
        $comm = Db::name('topic')->where('tid','in',$data['tid'])->max('create_time');
        $data['comment_time'] = $comm;
        return $data;
    }

    public function getViewsAttr($val,$data)
    {
        $this::update(['tid'=>$data['tid'],'views'=>$val+1]);
        return $val;
    }
    public function getContentAttr($val)
    {
        return htmlspecialchars_decode($val);
    }
    public function setTitleAttr($val)
    {
        $val = strip_tags($val);
        return $val;
    }
    public function setUseripAttr($val)
    {
        $val = \request()->ip();
        return $val;
    }
}
?>