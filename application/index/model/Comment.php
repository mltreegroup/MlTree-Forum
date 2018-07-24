<?php
namespace app\index\model;

use think\Model;
use think\Db;
use app\index\model\Option;

class Comment extends Model
{
    protected $pk = 'cid';
    protected $autoWriteTimestamp = true;

    public function getContentAttr($val)
    {
        return \markdownEncode($val);
    }
    public function setContentAttr($val)
    {
        $res = replyRegular($val);
        $val = str_replace('{'.$res[0].'}', $res[3], $val);
        return $val;
    }

    /**
     * 获取指定tid下的评论列表
     * @param [int] $tid [require|主题id]
     * @param [int] $min [require|最小页数]
     * @param [int] $max [require|最大页数]
     * @return [array] [$comment,$pages] [评论内容及剩余页数]
     */
    public function ListPage($tid, $min, $max=0)
    {
        $max == 0 ? $max = Option::getValue('commentNum') : $max;
        $comment = Comment::page('comment')->where('tid', $tid)->page($min, $max)->select();
        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid', $value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];
            !empty($data['motto']) ?  $value['motto'] = $data['motto'] : $value;
            $value['time_format'] = time_format($value['create_time']);
        }
        $count = Comment::page('comment')->where('tid', $tid)->count('cid');
        $pages = ceil($count / $max);
        return [$comment,$pages];
    }
}
