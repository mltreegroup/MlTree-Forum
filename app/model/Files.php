<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Files extends Model
{
    //
    protected $pk = 'file_id';

    public function user()
    {
        return $this->hasOne(Users::class, 'uid', 'uid');
    }

    public function createDownload($file_id, $expire = 300)
    {
        $file = Files::find($file_id);
        if (empty($file)) {
            return [false, 'File does not exist'];
        }
        return [true, \download($file->file_url, md5(time()), false, $expire)];
    }
}
