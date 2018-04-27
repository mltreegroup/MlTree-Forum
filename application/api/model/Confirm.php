<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Confirm extends Model{
	static function getValues($groups = ['basic']){
		$t =  Db::name('confirm')->where('type','in',$groups)->column('code','time');
		return $t;
	}
	static function getValue($uid){
		return Db::name('confirm')->where('uid',$uid)->value('code','time');
	}
	static function setConfirm($uid){
		Db::name('confirm')->where('uid',$uid)->delete();
	}
}
?>