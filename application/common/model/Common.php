<?php
namespace app\common\model;

use think\Model;
use think\facade\Cache;
use app\common\model\Topic;

class Common extends Model
{
    static function Serach($kw,$tp='topic')
    {
        switch ($tp) {
            case 'topic':
                $topic = new Topic;
                if (empty(Cache::get($kw))) {
                    $data = $topic->Search($kw);
                    Cache::tag('serach')->set($kw,$data);
                    return $data;
                }
                return Cache::get($kw);
                
                break;
            
            default:
                # code...
                break;
        }
    }
}
