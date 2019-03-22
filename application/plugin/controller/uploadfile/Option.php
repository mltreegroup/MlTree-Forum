<?php
namespace app\plugin\controller\uploadfile;

use app\plugin\controller\Base;

class Option extends Base
{
    public function index()
    {
        if (\request()->isPost()) {
            $data = input('post.');
            if ($data['type'] == 'local') {
                Base::setValues('uploadfile', ['type' => $data['type']]);
                return \outRes(0, '更新成功');
            } else {
                
                if (empty($data['url']) || empty($data['service_name']) || empty($data['operato_name']) || empty($data['operato_pwd'])) {
                    return \outRes(1000, '参数错误或缺失');
                }
                Base::setValues('uploadfile', $data);
                return \outRes(0, '更新成功');
            }
        }
        return ($this->pluginView('uploadfile', 'index', ['msg' => 'Hello World!我是渲染的']));
    }
}
