<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\User;
use app\common\model\Topic;
use app\common\model\Atta;

class Forum extends Model
{
    protected $pk = 'fid';
}
?>