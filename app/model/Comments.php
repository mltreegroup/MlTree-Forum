<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin think\Model
 */
class Comments extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $pk = 'coid';

    public function user()
    {
        return $this->hasOne(Users::class, 'uid', 'uid');
    }
}
