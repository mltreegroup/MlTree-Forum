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

<<<<<<< HEAD
use think\Container;
use think\Exception;
=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
use think\Route;

class RuleItem extends Rule
{
    /**
     * 路由规则
     * @var string
     */
<<<<<<< HEAD
    protected $rule;
=======
    protected $name;
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416

    /**
     * 路由地址
     * @var string|\Closure
     */
    protected $route;

    /**
     * 请求类型
     * @var string
     */
    protected $method;

    /**
     * 架构函数
     * @access public
     * @param  Route             $router 路由实例
<<<<<<< HEAD
     * @param  RuleGroup         $parent 上级对象
     * @param  string            $name 路由标识
     * @param  string|array      $rule 路由规则
=======
     * @param  RuleGroup         $group 路由所属分组对象
     * @param  string|array      $name 路由规则
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
     * @param  string            $method 请求类型
     * @param  string|\Closure   $route 路由地址
     * @param  array             $option 路由参数
     * @param  array             $pattern 变量规则
     */
<<<<<<< HEAD
    public function __construct(Route $router, RuleGroup $parent, $name, $rule, $route, $method = '*', $option = [], $pattern = [])
    {
        $this->router  = $router;
        $this->parent  = $parent;
        $this->name    = $name;
=======
    public function __construct(Route $router, RuleGroup $group, $name, $route, $method = '*', $option = [], $pattern = [])
    {
        $this->router  = $router;
        $this->parent  = $group;
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $this->route   = $route;
        $this->method  = $method;
        $this->option  = $option;
        $this->pattern = $pattern;

<<<<<<< HEAD
        $this->setRule($rule);

        if (!empty($option['cross_domain'])) {
            $this->router->setCrossDomainRule($this, $method);
        }
=======
        $this->setRule($name);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    }

    /**
     * 路由规则预处理
     * @access public
     * @param  string      $rule     路由规则
     * @return void
     */
    public function setRule($rule)
    {
        if ('$' == substr($rule, -1, 1)) {
            // 是否完整匹配
            $rule = substr($rule, 0, -1);

            $this->option['complete_match'] = true;
        }

<<<<<<< HEAD
        $rule = '/' != $rule ? ltrim($rule, '/') : '';

        if ($this->parent && $prefix = $this->parent->getFullName()) {
            $rule = $prefix . ($rule ? '/' . ltrim($rule, '/') : '');
        }

        if (false !== strpos($rule, ':')) {
            $this->rule = preg_replace(['/\[\:(\w+)\]/', '/\:(\w+)/'], ['<\1?>', '<\1>'], $rule);
        } else {
            $this->rule = $rule;
        }

        // 生成路由标识的快捷访问
        $this->setRuleName();
    }

    /**
     * 获取当前路由规则
     * @access public
     * @return string
     */
    public function getRule()
    {
        return $this->rule;
=======
        $this->name($rule);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    }

    /**
     * 获取当前路由地址
     * @access public
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
<<<<<<< HEAD
     * 获取当前路由的请求类型
     * @access public
     * @return string
     */
    public function getMethod()
    {
        return strtolower($this->method);
    }

    /**
     * 检查后缀
     * @access public
     * @param  string     $ext
     * @return $this
     */
    public function ext($ext = '')
    {
        $this->option('ext', $ext);
        $this->setRuleName(true);

=======
     * 设置为自动路由
     * @access public
     * @return $this
     */
    public function isAuto()
    {
        $this->parent->setAutoRule($this);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        return $this;
    }

    /**
<<<<<<< HEAD
     * 设置别名
     * @access public
     * @param  string     $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        $this->setRuleName(true);

=======
     * 设置为MISS路由
     * @access public
     * @return $this
     */
    public function isMiss()
    {
        $this->parent->setMissRule($this);
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        return $this;
    }

    /**
<<<<<<< HEAD
     * 设置路由标识 用于URL反解生成
     * @access protected
     * @param  bool     $first   是否插入开头
     * @return void
     */
    protected function setRuleName($first = false)
    {
        if ($this->name) {
            $vars = $this->parseVar($this->rule);
            $name = strtolower($this->name);

            if (isset($this->option['ext'])) {
                $suffix = $this->option['ext'];
            } elseif ($this->parent->getOption('ext')) {
                $suffix = $this->parent->getOption('ext');
            } else {
                $suffix = null;
            }

            $value = [$this->rule, $vars, $this->parent->getDomain(), $suffix];

            Container::get('rule_name')->set($name, $value, $first);
        }
    }

    /**
=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
     * 检测路由
     * @access public
     * @param  Request      $request  请求对象
     * @param  string       $url      访问地址
<<<<<<< HEAD
     * @param  array        $match    匹配路由变量
     * @param  string       $depr     路径分隔符
     * @param  bool         $completeMatch   路由是否完全匹配
     * @return Dispatch|false
     */
    public function checkRule($request, $url, $match = null, $depr = '/', $completeMatch = false)
    {
=======
     * @param  string       $depr     路径分隔符
     * @param  bool         $completeMatch   路由是否完全匹配
     * @return Dispatch
     */
    public function check($request, $url, $depr = '/', $completeMatch = false)
    {
        if ($this->parent && $prefix = $this->parent->getFullName()) {
            $this->name = $prefix . ($this->name ? '/' . ltrim($this->name, '/') : '');
        }

>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        if ($dispatch = $this->checkCrossDomain($request)) {
            // 允许跨域
            return $dispatch;
        }

        // 检查参数有效性
        if (!$this->checkOption($this->option, $request)) {
            return false;
        }

        // 合并分组参数
<<<<<<< HEAD
        $option = $this->mergeGroupOptions();

        // 检查前置行为
        if (isset($option['before']) && false === $this->checkBefore($option['before'])) {
            return false;
        }

        $url = $this->urlSuffixCheck($request, $url, $option);

        if (is_null($match)) {
            $match = $this->match($url, $option, $depr, $completeMatch);
        }

        if (false !== $match) {
            return $this->parseRule($request, $this->rule, $this->route, $url, $option, $match);
        }

        return false;
    }

    /**
     * 检测路由（含路由匹配）
     * @access public
     * @param  Request      $request  请求对象
     * @param  string       $url      访问地址
     * @param  string       $depr     路径分隔符
     * @param  bool         $completeMatch   路由是否完全匹配
     * @return Dispatch|false
     */
    public function check($request, $url, $depr = '/', $completeMatch = false)
    {
        return $this->checkRule($request, $url, null, $depr, $completeMatch);
    }

    /**
     * URL后缀及Slash检查
     * @access protected
     * @param  Request      $request  请求对象
     * @param  string       $url      访问地址
     * @param  array        $option   路由参数
     * @return string
     */
    protected function urlSuffixCheck($request, $url, $option = [])
    {
        // 是否区分 / 地址访问
        if (!empty($option['remove_slash']) && '/' != $this->rule) {
            $this->rule = rtrim($this->rule, '/');
            $url        = rtrim($url, '|');
        }

=======
        $this->mergeGroupOptions();
        $option = $this->option;

        if (!empty($option['append'])) {
            $request->route($option['append']);
        }

        // 是否区分 / 地址访问
        if (!empty($option['remove_slash']) && '/' != $this->name) {
            $this->name = rtrim($this->name, '/');
            $url        = rtrim($url, '|');
        }

        // 检查前置行为
        if (isset($option['before']) && false === $this->checkBefore($option['before'])) {
            return false;
        }

>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        if (isset($option['ext'])) {
            // 路由ext参数 优先于系统配置的URL伪静态后缀参数
            $url = preg_replace('/\.(' . $request->ext() . ')$/i', '', $url);
        }

<<<<<<< HEAD
        return $url;
    }

    /**
     * 检测URL和规则路由是否匹配
     * @access private
     * @param  string    $url URL地址
     * @param  array     $option    路由参数
     * @param  string    $depr URL分隔符（全局）
     * @param  bool      $completeMatch   路由是否完全匹配
     * @return array|false
     */
    private function match($url, $option, $depr, $completeMatch)
    {
        if (isset($option['complete_match'])) {
            $completeMatch = $option['complete_match'];
        }

        $pattern = array_merge($this->parent->getPattern(), $this->pattern);

        // 检查完整规则定义
        if (isset($pattern['__url__']) && !preg_match(0 === strpos($pattern['__url__'], '/') ? $pattern['__url__'] : '/^' . $pattern['__url__'] . '/', str_replace('|', $depr, $url))) {
            return false;
        }

        $var  = [];
        $url  = $depr . str_replace('|', $depr, $url);
        $rule = $depr . str_replace('/', $depr, $this->rule);

        if ($depr == $rule && $depr != $url) {
            return false;
        }

        if (false === strpos($rule, '<')) {
            if (0 === strcasecmp($rule, $url) || (!$completeMatch && 0 === strncasecmp($rule, $url, strlen($rule)))) {
                return $var;
            }
            return false;
        }

        $slash = preg_quote('/-' . $depr, '/');

        if ($matchRule = preg_split('/[' . $slash . ']?<\w+\??>/', $rule, 2)) {
            if ($matchRule[0] && 0 !== strncasecmp($rule, $url, strlen($matchRule[0]))) {
                return false;
            }
        }

        if (preg_match_all('/[' . $slash . ']?<?\w+\??>?/', $rule, $matches)) {
            $regex = $this->buildRuleRegex($rule, $matches[0], $pattern, $option, $completeMatch);

            try {
                if (!preg_match('/^' . $regex . ($completeMatch ? '$' : '') . '/u', $url, $match)) {
                    return false;
                }
            } catch (\Exception $e) {
                throw new Exception('route pattern error');
            }

            foreach ($match as $key => $val) {
                if (is_string($key)) {
                    $var[$key] = $val;
                }
=======
        return $this->checkRule($request, $url, $depr, $completeMatch, $option);
    }

    /**
     * 检测路由规则
     * @access private
     * @param  Request   $request 请求对象
     * @param  string    $url URL地址
     * @param  string    $depr URL分隔符（全局）
     * @param  bool      $completeMatch   路由是否完全匹配
     * @param  array     $option   路由参数
     * @return array|false
     */
    private function checkRule($request, $url, $depr, $completeMatch = false, $option = [])
    {
        // 检查完整规则定义
        if (isset($this->pattern['__url__']) && !preg_match(0 === strpos($this->pattern['__url__'], '/') ? $this->pattern['__url__'] : '/^' . $this->pattern['__url__'] . '/', str_replace('|', $depr, $url))) {
            return false;
        }

        // 检查路由的参数分隔符
        if (isset($option['param_depr'])) {
            $url = str_replace(['|', $option['param_depr']], [$depr, '|'], $url);
        }

        $len1 = substr_count($url, '|');
        $len2 = substr_count($this->name, '/');

        // 多余参数是否合并
        $merge = !empty($option['merge_extra_vars']) ? true : false;

        if ($merge && $len1 > $len2) {
            $url = str_replace('|', $depr, $url);
            $url = implode('|', explode($depr, $url, $len2 + 1));
        }

        if (isset($option['complete_match'])) {
            $completeMatch = $option['complete_match'];
        }

        if ($len1 >= $len2 || strpos($this->name, '[')) {
            // 完整匹配
            if ($completeMatch && (!$merge && $len1 != $len2 && (false === strpos($this->name, '[') || $len1 > $len2 || $len1 < $len2 - substr_count($this->name, '[')))) {
                return false;
            }

            $pattern = array_merge($this->parent->getPattern(), $this->pattern);

            if (false !== $match = $this->match($url, $pattern)) {
                // 匹配到路由规则
                return $this->parseRule($request, $this->name, $this->route, $url, $option, $match);
            }
        }

        return false;
    }

    /**
     * 检测URL和规则路由是否匹配
     * @access private
     * @param  string    $url URL地址
     * @param  array     $pattern 变量规则
     * @return array|false
     */
    private function match($url, $pattern)
    {
        $m2 = explode('/', $this->name);
        $m1 = explode('|', $url);

        $var = [];

        foreach ($m2 as $key => $val) {
            // val中定义了多个变量 <id><name>
            if (false !== strpos($val, '<') && preg_match_all('/<(\w+(\??))>/', $val, $matches)) {
                $value   = [];
                $replace = [];

                foreach ($matches[1] as $name) {
                    if (strpos($name, '?')) {
                        $name      = substr($name, 0, -1);
                        $replace[] = '(' . (isset($pattern[$name]) ? $pattern[$name] : '\w+') . ')?';
                    } else {
                        $replace[] = '(' . (isset($pattern[$name]) ? $pattern[$name] : '\w+') . ')';
                    }
                    $value[] = $name;
                }

                $val = str_replace($matches[0], $replace, $val);

                if (preg_match('/^' . $val . '$/', isset($m1[$key]) ? $m1[$key] : '', $match)) {
                    array_shift($match);
                    foreach ($value as $k => $name) {
                        if (isset($match[$k])) {
                            $var[$name] = $match[$k];
                        }
                    }
                    continue;
                } else {
                    return false;
                }
            }

            if (0 === strpos($val, '[:')) {
                // 可选参数
                $val      = substr($val, 1, -1);
                $optional = true;
            } else {
                $optional = false;
            }

            if (0 === strpos($val, ':')) {
                // URL变量
                $name = substr($val, 1);

                if (!$optional && !isset($m1[$key])) {
                    return false;
                }

                if (isset($m1[$key]) && isset($pattern[$name])) {
                    // 检查变量规则
                    if ($pattern[$name] instanceof \Closure) {
                        $result = call_user_func_array($pattern[$name], [$m1[$key]]);
                        if (false === $result) {
                            return false;
                        }
                    } elseif (!preg_match(0 === strpos($pattern[$name], '/') ? $pattern[$name] : '/^' . $pattern[$name] . '$/', $m1[$key])) {
                        return false;
                    }
                }

                $var[$name] = isset($m1[$key]) ? $m1[$key] : '';
            } elseif (!isset($m1[$key]) || 0 !== strcasecmp($val, $m1[$key])) {
                return false;
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
            }
        }

        // 成功匹配后返回URL中的动态变量数组
        return $var;
    }

}
