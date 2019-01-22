<?php
namespace app\common\model;

use app\common\model\Option;
use think\Db;
use think\Model;

class Comment extends Model
{
    protected $pk = 'cid';
    protected $autoWriteTimestamp = true;

    public function setContentAttr($val)
    {
        $res = replyRegular($val);
        $val = str_replace('{' . $res[0] . '}', $res[3], $val);
        return $val;
    }

    /**
     * 获取指定tid下的评论列表
     * @param [int] $tid [require|主题id]
     * @param [int] $page [require|页码]
     * @param [int] $number [require|每页显示条数]
     * @return [array] [$comment,$pages] [评论内容及总页数]
     */
    public function ListPage($tid, $page, $number = 0)
    {
        $number == 0 ? $number = Option::getValue('commentNum') : $number;
        $comment = Comment::page('comment')->where('tid', $tid)->page($page, $number)->select();
        if (empty($comment)) {
            return [false, 'NULL'];
        }
        foreach ($comment as $key => $value) {
            $data = Db::name('user')->where('uid', $value['uid'])->find();
            $value['username'] = $data['username'];
            $value['avatar'] = $data['avatar'];
            !empty($data['motto']) ? $value['motto'] = $data['motto'] : $value;
            $value['time_format'] = time_format($value['create_time']);
        }
        $count = Comment::page('comment')->where('tid', $tid)->count('cid');
        $pages = ceil($count / $number);
        return [true, $comment, $pages];
    }
}
