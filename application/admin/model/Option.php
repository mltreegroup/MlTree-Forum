<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Option extends Model{
	static function getValues($groups = ['base']){
		$t =  Db::name('options')->where('type','in',$groups)->column('value','name');
		return $t;
	}
	static function getValue($optionName){
		return Db::name('options')->where('name',$optionName)->value('value');
	}
	static function setValues($data)
	{
		foreach ($data as $key => $value) {
			Db::name('options')->where('name',$key)->strict(false)->setField('value',$value);
		}
	}
	static function setValue($name,$data)
	{
		Db::name('options')->where('name',$name)->setField('value',$data);
	}
}
?>