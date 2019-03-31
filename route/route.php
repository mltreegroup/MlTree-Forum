<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rule('/', 'forum/index/index'); // 首页访问路由
Route::alias('Api','forum/api');
Route::rule('Forum/:fid','forum/index/forum');
Route::rule('Topic/:tid', 'forum/topic/index');
Route::rule('Editor/:tid','forum/topic/editor');
Route::rule('Login', 'forum/user/login');
Route::rule('Reg', 'forum/user/reg');
Route::rule('Logout', 'forum/user/logout');
Route::rule('ForgetPwd', 'forum/user/forgetPwd');
Route::rule('Member/[:uid]', 'forum/user/index');
Route::rule('User/Active/[:uid]/[:code]/[:time]','forum/user/Active');
Route::rule('User/ReActive/[:email]','forum/user/reActive');
Route::rule('Search','forum/index/Search');
Route::rule('Plugin/Assets/:appSign/:url','forum/api/pluginAssets');
Route::rule('qqlogin','forum/user/qqLogin');
Route::rule('callback/qqlogin','forum/user/callback');
return [

];
