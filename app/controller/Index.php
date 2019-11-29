<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        //dump(password_hash('admin',PASSWORD_DEFAULT));
        //dump(\think\facade\Cache::get('user_1'));
        dump($this->request->domain());
    }

    public function site()
    {
        $site = \app\model\Options::getValues();
        return $this->out('success', $site);
    }

    public function upload()
    {
        if ($this->request->isPost()) {
            $file = $this->request->file('file');
            $savename = \think\facade\Filesystem::disk('public')->putFile('files', $file);
            $file_url = $this->request->domain() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $savename;
            return $this->out('success', $file_url);
        }
    }

    public function checkJWT()
    {
        $jwt = $this->request->header('Authorization');
        if (empty($jwt)) {
            return $this->out('jwt error', [], -1005);
        }
        $check = \app\model\JsonToken::checkJWT($jwt);
        if (!$check[0]) {
            return $this->out('jwt error', [], -1005);
        }
        return $this->out('pass');
    }
}
