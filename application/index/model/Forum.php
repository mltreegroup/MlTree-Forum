<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Forum extends Model
{
    protected $pk = 'fid';
    protected $autoWriteTimestamp = true;
}
?>