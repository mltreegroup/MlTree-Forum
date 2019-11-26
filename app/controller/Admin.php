<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use app\model\Options;
use app\model\Users;

class Admin extends BaseController
{
    public function updateOptons()
    {
        if ($this->request->isPost()) {
            $update = $this->request->post();
            Options::setValues($update);
        }
    }

    public function userSearch($userKey = '')
    {
        $user = Users::where('uid|nick|email', $userKey)->select();
        $user->topics;
        $user->comments;
        $user->group;

        return $this->out('success', $user);
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
}
