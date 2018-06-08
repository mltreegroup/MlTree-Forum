<?php

use Auth\Auth;

function outBadge($data)
{
    $value = '';
    if ($data['tops'] == 1) {
        $value = '<span class="mf-badge mf-badge-danger mf-radius mdui-m-r-1">置顶</span>';
    }
    if ($data['essence'] == 1) {
        $value = $value.'<span class="mf-badge mf-badge-warning mf-radius mdui-m-r-1">精华</span>';
    }

    return $value;
}

function authCheck($name, $uid = 0, $relation = 'or')
{
    $auth = new Auth();
    if ($uid == 0) {
        $uid = session('uid');
    }
    return $auth->check($name, $uid, $relation);
}