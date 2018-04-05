<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Comment extends Model
{
    protected $pk = 'cid';
    protected $autoWriteTimestamp = true;
}
?>