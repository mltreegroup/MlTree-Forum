<?php

namespace app\http\middleware;
use app\common\model\Plugin as PluginModel;
use app\plugin\controller\Base;

class Plugin
{
    public function handle($request, \Closure $next)
    {
        $name = explode('.', request()->controller());
        $base = new Base();
        if (!PluginModel::isStart($name[0]) && !PluginModel::isArrow($name[0])) {
            return json(['code' => 105001, 'msg' => 'Not registered yet, access has been blocked by the system.', 'time' => time()]);
        }

        return $next($request);
    }
}
