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

Route::rule('/', 'index/index'); // 首页访问路由
Route::rule('topic/:tid', 'index/topic/index');
Route::rule('forum/[:fid]', 'index/forum/index');

Route::rule('register', 'user/reg');
Route::rule('forget', 'user/forgetpas');
Route::rule('logout', 'user/logout');
Route::rule('login', 'user/login');
Route::rule('qqlogin', 'user/qqlogin');

Route::rule('user/[:uid]', 'index/user/index');

Route::rule('error', 'index/index/_error');
Route::rule('golink/:url', 'index/expand/golink');
Route::rule('callback/[:code]/[:state]', 'index/user/callback');
Route::rule('create', 'index/topic/create');
Route::rule('update/[:tid]', 'index/topic/update');

Route::rule('settings','index/settings');

return [

];
