<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Option extends Model
{
    public static function getValues($groups = ['base'])
    {
        $t =  Db::name('options')->where('type', 'in', $groups)->column('value', 'name');
        return $t;
    }
    public static function getValue($optionName)
    {
        return Db::name('options')->where('name', $optionName)->value('value');
    }
    public static function siteStatus()
    {
		//$data = self::getValue('siteStatus');
		$data = Db::name('options')->where('name','siteStatus');
        if ($data != 1) {
            return false;
        }
        return true;
    }
    static function setValues($data)
	{
		foreach ($data as $key => $value) {
			Db::name('options')->where('name',$key)->setField('value',$value);
		}
	}
	static function setValue($name,$data)
	{
		Db::name('options')->where('name',$name)->setField('value',$data);
	}
}
