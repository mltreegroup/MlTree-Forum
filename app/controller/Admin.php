<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use app\model\Options;
use app\model\Users;
use think\facade\Cache;

class Admin extends BaseController
{
    public function updateOptons()
    {
        if ($this->request->isPost()) {
            $update = $this->request->post();
            Options::setValues($update);
        }
    }

    public function UserList()
    {
        $list = Users::field('uid,nick,email,last_time')->select();
        return $this->out('success', $list);
    }

    public function GroupList()
    {
        $list = \app\model\Groups::select();
        return $this->out('success', $list);
    }

    public function ForumList()
    {
        $list = \app\model\Forums::select();
        return $this->out('success', $list);
    }

    public function TopicList()
    {
        $list = \app\model\Topics::with(['user'])->select();
        $list->visible(['user' => ['uid', 'nick', 'email', 'avatar']]);
        return $this->out('success', $list);
    }

    public function updateUser()
    {
        if ($this->request->isPost()) {
            $update = $this->request->post();
            $user = Users::find($update['uid']);
            if (isset($update['pwd'])) {
                $update['pwd'] = password_hash($update['pwd'], PASSWORD_DEFAULT);
            }

            $user->save($update);
            return $this->out('success', $user);
        }
    }

    public function statistics()
    {
        $visit_count = Cache::get('visit_' . date('Ymd', time()));
    }
}
