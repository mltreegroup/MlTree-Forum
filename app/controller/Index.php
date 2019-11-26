<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        //dump(password_hash('admin',PASSWORD_DEFAULT));
        //dump(\think\facade\Cache::get('user_1'));
    }

    public function site()
    {
        $site = \app\model\Options::getValues();
        return $this->out('success', $site);
    }

    public function upload()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

        }
    }
}
