<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin think\Model
 */
class Topics extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $pk = 'tid';

    public function user()
    {
        return $this->hasOne(Users::class, 'uid', 'uid');
    }

    public function forum()
    {
        return $this->hasOne(Forums::class, 'fid', 'fid');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'tid', 'tid');
    }

    public function searchTopicAttr($query, $value, $data)
    {
        $query->where('title|content', 'like', '%' . $value . '%');
        if (isset($data['sort'])) {
            $query->order($data['sort']);
        }
    }
}
