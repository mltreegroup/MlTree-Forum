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

namespace think\route;

use think\Container;
use think\Loader;
<<<<<<< HEAD
=======
use think\Response;
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
use think\Route;
use think\route\dispatch\Callback as CallbackDispatch;
use think\route\dispatch\Controller as ControllerDispatch;
use think\route\dispatch\Module as ModuleDispatch;
<<<<<<< HEAD

class Domain extends RuleGroup
{
    protected $bind;

=======
use think\route\dispatch\Response as ResponseDispatch;

class Domain extends RuleGroup
{
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    /**
     * 架构函数
     * @access public
     * @param  Route       $router   路由对象
<<<<<<< HEAD
     * @param  string      $name     路由域名
=======
     * @param  string      $name     分组名称
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
     * @param  mixed       $rule     域名路由
     * @param  array       $option   路由参数
     * @param  array       $pattern  变量规则
     */
    public function __construct(Route $router, $name = '', $rule = null, $option = [], $pattern = [])
    {
        $this->router  = $router;
<<<<<<< HEAD
        $this->domain  = $name;
=======
        $this->name    = trim($name, '/');
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $this->option  = $option;
        $this->rule    = $rule;
        $this->pattern = $pattern;
    }

    /**
     * 检测域名路由
     * @access public
     * @param  Request      $request  请求对象
     * @param  string       $url      访问地址
     * @param  string       $depr     路径分隔符
     * @param  bool         $completeMatch   路由是否完全匹配
     * @return Dispatch|false
     */
    public function check($request, $url, $depr = '/', $completeMatch = false)
    {
<<<<<<< HEAD
        // 检测别名路由
        $result = $this->checkRouteAlias($request, $url, $depr);

        if (false !== $result) {
            return $result;
=======
        if ($this->rule) {
            // 延迟解析域名路由
            if ($this->rule instanceof Response) {
                return new ResponseDispatch($this->rule);
            }

            $group = new RuleGroup($this->router);

            $this->addRule($group);

            $this->router->setGroup($group);

            $this->router->parseGroupRule($this, $this->rule);

            $this->rule = null;
        }

        // 检测别名路由
        if ($this->router->getAlias($url) || $this->router->getAlias(strstr($url, '|', true))) {
            // 检测路由别名
            $result = $this->checkRouteAlias($request, $url, $depr);
            if (false !== $result) {
                return $result;
            }
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        }

        // 检测URL绑定
        $result = $this->checkUrlBind($url, $depr);

<<<<<<< HEAD
        if (!empty($this->option['append'])) {
            $request->route($this->option['append']);
            unset($this->option['append']);
        }

=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        if (false !== $result) {
            return $result;
        }

<<<<<<< HEAD
        // 添加域名中间件
        if (!empty($this->option['middleware'])) {
            Container::get('middleware')->import($this->option['middleware']);
            unset($this->option['middleware']);
        }

=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        return parent::check($request, $url, $depr, $completeMatch);
    }

    /**
<<<<<<< HEAD
     * 设置路由绑定
     * @access public
     * @param  string     $bind 绑定信息
     * @return $this
     */
    public function bind($bind)
    {
        $this->bind = $bind;
        $this->router->bind($bind, $this->domain);

        return $this;
    }

    /**
=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
     * 检测路由别名
     * @access private
     * @param  Request   $request
     * @param  string    $url URL地址
     * @param  string    $depr URL分隔符
     * @return Dispatch|false
     */
    private function checkRouteAlias($request, $url, $depr)
    {
<<<<<<< HEAD
        $alias = strpos($url, '|') ? strstr($url, '|', true) : $url;

        $item = $this->router->getAlias($alias);

        return $item ? $item->check($request, $url, $depr) : false;
=======
        $array = explode('|', $url);
        $alias = array_shift($array);
        $item  = $this->router->getAlias($alias);

        if (is_array($item)) {
            list($rule, $option) = $item;
            $action              = $array[0];

            if (isset($option['allow']) && !in_array($action, explode(',', $option['allow']))) {
                // 允许操作
                return false;
            } elseif (isset($option['except']) && in_array($action, explode(',', $option['except']))) {
                // 排除操作
                return false;
            }

            if (isset($option['method'][$action])) {
                $option['method'] = $option['method'][$action];
            }
        } else {
            $rule = $item;
        }

        $bind = implode('|', $array);

        // 参数有效性检查
        if (isset($option) && !$this->checkOption($option, $request)) {
            // 路由不匹配
            return false;
        } elseif (0 === strpos($rule, '\\')) {
            // 路由到类
            return $this->bindToClass($bind, substr($rule, 1), $depr);
        } elseif (0 === strpos($rule, '@')) {
            // 路由到控制器类
            return $this->bindToController($bind, substr($rule, 1), $depr);
        } else {
            // 路由到模块/控制器
            return $this->bindToModule($bind, $rule, $depr);
        }
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    }

    /**
     * 检测URL绑定
     * @access private
     * @param  string    $url URL地址
     * @param  string    $depr URL分隔符
     * @return Dispatch|false
     */
<<<<<<< HEAD
    private function checkUrlBind($url, $depr = '/')
    {
        if (!empty($this->bind)) {
            $bind = $this->bind;
            $this->parseBindAppendParam($bind);

=======
    private function checkUrlBind(&$url, $depr = '/')
    {
        $bind = $this->router->getBind($this->name);

        if (!empty($bind)) {
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
            // 记录绑定信息
            Container::get('app')->log('[ BIND ] ' . var_export($bind, true));

            // 如果有URL绑定 则进行绑定检测
<<<<<<< HEAD
            $type = substr($bind, 0, 1);
            $bind = substr($bind, 1);

            $bindTo = [
                '\\' => 'bindToClass',
                '@'  => 'bindToController',
                ':'  => 'bindToNamespace',
            ];

            if (isset($bindTo[$type])) {
                return $this->{$bindTo[$type]}($url, $bind, $depr);
=======
            if (0 === strpos($bind, '\\')) {
                // 绑定到类
                return $this->bindToClass($url, substr($bind, 1), $depr);
            } elseif (0 === strpos($bind, '@')) {
                // 绑定到控制器类
                return $this->bindToController($url, substr($bind, 1), $depr);
            } elseif (0 === strpos($bind, ':')) {
                // 绑定到命名空间
                return $this->bindToNamespace($url, substr($bind, 1), $depr);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
            }
        }

        return false;
    }

<<<<<<< HEAD
    protected function parseBindAppendParam(&$bind)
    {
        if (false !== strpos($bind, '?')) {
            list($bind, $query) = explode('?', $bind);
            parse_str($query, $vars);
            $this->append($vars);
        }
    }

    /**
     * 绑定到类
     * @access protected
     * @param  string    $url URL地址
     * @param  string    $class 类名（带命名空间）
     * @return CallbackDispatch
     */
    protected function bindToClass($url, $class)
    {
=======
    /**
     * 绑定到类
     * @access public
     * @param  string    $url URL地址
     * @param  string    $class 类名（带命名空间）
     * @param  string    $depr URL分隔符
     * @return CallbackDispatch
     */
    public function bindToClass($url, $class, $depr = '/')
    {
        $url    = str_replace($depr, '|', $url);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $array  = explode('|', $url, 2);
        $action = !empty($array[0]) ? $array[0] : Container::get('config')->get('default_action');

        if (!empty($array[1])) {
            $this->parseUrlParams($array[1]);
        }

        return new CallbackDispatch([$class, $action]);
    }

    /**
     * 绑定到命名空间
<<<<<<< HEAD
     * @access protected
     * @param  string    $url URL地址
     * @param  string    $namespace 命名空间
     * @return CallbackDispatch
     */
    protected function bindToNamespace($url, $namespace)
    {
=======
     * @access public
     * @param  string    $url URL地址
     * @param  string    $namespace 命名空间
     * @param  string    $depr URL分隔符
     * @return CallbackDispatch
     */
    public function bindToNamespace($url, $namespace, $depr = '/')
    {
        $url    = str_replace($depr, '|', $url);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $array  = explode('|', $url, 3);
        $class  = !empty($array[0]) ? $array[0] : Container::get('config')->get('default_controller');
        $method = !empty($array[1]) ? $array[1] : Container::get('config')->get('default_action');

        if (!empty($array[2])) {
            $this->parseUrlParams($array[2]);
        }

        return new CallbackDispatch([$namespace . '\\' . Loader::parseName($class, 1), $method]);
    }

    /**
     * 绑定到控制器类
<<<<<<< HEAD
     * @access protected
     * @param  string    $url URL地址
     * @param  string    $controller 控制器名 （支持带模块名 index/user ）
     * @return ControllerDispatch
     */
    protected function bindToController($url, $controller)
    {
=======
     * @access public
     * @param  string    $url URL地址
     * @param  string    $controller 控制器名 （支持带模块名 index/user ）
     * @param  string    $depr URL分隔符
     * @return ControllerDispatch
     */
    public function bindToController($url, $controller, $depr = '/')
    {
        $url    = str_replace($depr, '|', $url);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $array  = explode('|', $url, 2);
        $action = !empty($array[0]) ? $array[0] : Container::get('config')->get('default_action');

        if (!empty($array[1])) {
            $this->parseUrlParams($array[1]);
        }

        return new ControllerDispatch($controller . '/' . $action);
    }

    /**
     * 绑定到模块/控制器
<<<<<<< HEAD
     * @access protected
     * @param  string    $url URL地址
     * @param  string    $controller 控制器类名（带命名空间）
     * @return ModuleDispatch
     */
    protected function bindToModule($url, $controller)
    {
=======
     * @access public
     * @param  string    $url URL地址
     * @param  string    $controller 控制器类名（带命名空间）
     * @param  string    $depr URL分隔符
     * @return ModuleDispatch
     */
    public function bindToModule($url, $controller, $depr = '/')
    {
        $url    = str_replace($depr, '|', $url);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $array  = explode('|', $url, 2);
        $action = !empty($array[0]) ? $array[0] : Container::get('config')->get('default_action');

        if (!empty($array[1])) {
            $this->parseUrlParams($array[1]);
        }

        return new ModuleDispatch($controller . '/' . $action);
    }

}
