<?php
namespace app\common\model;

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
}
?>