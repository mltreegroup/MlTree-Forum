<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin think\Model
 */
class Forums extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $pk = 'fid';

    public function getStatusTextAttr($val, $data)
    {
        $text = [0 => '关闭', 1 => '正常'];
        return $text[$data['status']];
    }

    public function topics()
    {
        return $this->hasMany(Topics::class, 'fid');
    }
}
