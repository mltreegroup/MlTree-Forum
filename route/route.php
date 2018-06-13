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

Route::any('/', 'index');
Route::any('topic/:tid', 'index/topic/index');
Route::any('forum/[:fid]', 'index/forum/index');
Route::any('user/[:uid]', 'index/user/index');
Route::any('error', 'index/index/_error');
Route::any('golink/:url', 'index/expand/golink');
Route::any('callback/[:code]/[:state]', 'index/user/callback');

return [

];
