<?php
namespace app\common\hook;

use app\common\model\Plugin as pluginModel;

class Plugin
{
    public static $pluginList = array();
    public static $noset = [];

    /**
     * 系统入口方法
     */
    public function run($params)
    {
        self::init();
    }

    /**
     * 初始化注册函数
     */
    public static function init()
    {
        $list = \cache('MlTree_Hook_plugin');
        if ($list) {
            self::$pluginList = cache('MlTree_Hook_plugin');
            return;
        }
        self::serachPlugin();
        self::$noset = \cache('MlTree_Hook_plugin_noset');
    }

    /**
     * 对系统位置进行钩子定位
     * @param string $name 钩子位置名称
     * @param &$data 引用参数
     */
    public static function call($name, $Example, &$data = [])
    {
        if (!in_array($name, self::$pluginList) && !in_array($name, self::$noset) ? true : self::$noset[$name]['time'] > time()) { //当缓存不存在或者失效的时候，执行一次搜索插件
            self::serachPlugin();
            if (!isset(self::$pluginList[$name])) { //如果还不存在，则判断尚未拥有需要监听此钩子的插件，则写入不存在缓存，缓存86400秒（一天）
                $no = [
                    'name' => $name,
                    'time' => time() + 86400, //缓存失效时间
                ];
                self::$noset[] = $no;
                \cache('MlTree_Hook_plugin_noset', $no);
                return 'Undefined';
            }
        }

        if (isset(self::$pluginList[$name])) {
            $res = [];
            foreach (self::$pluginList as $key => $val) {
                foreach ($val as $key => $value) {
                    if ($value['func'] == $name) {
                        if (!pluginModel::isStart($value['name']) && !pluginModel::isArrow($value['name'])) { // 尚未注册禁止调用
                            $_res = ['code' => 105002, 'msg' => 'Lock', 'time' => time()];
                        }
                        if (is_callable($value['func'])) {
                            $_res = call_user_func_array($value['func'], array($Example, $data));
                        } else {
                            if (class_exists($value['class'], false)) {
                                $class = new $value['class']();
                                $_res = call_user_func_array(array($class, $value['func']), array($Example, $data));
                            } else {
                                $_res = call_user_func_array(array($value['class'], $value['func']), array($Example, $data));
                            }

                        }
                    }
                    if (isset($_res) ? !$_res : false) {
                        return $_res;
                    }
                }

            }

        }
        return $res;
    }

    /**
     * 搜索插件目录下的插件
     */
    public static function serachPlugin()
    {
        $dirs = get_dir('application\\plugin\\controller\\', true, true);

        foreach ($dirs as $key => $value) {
            if (!(rtrim($value['rel'], '\\')) && !pluginModel::isArrow(rtrim($value['rel'], '\\'))) {
                return;
            }
            require_once $value['abs'] . 'Bootstrap.php';
            //获取方法成员属性
            $classlist = get_declared_classes();
            $savePlugin = [];
            foreach ($classlist as $key => $val) {
                if (preg_match('/app\\\\plugin\\\\([0-9a-zA-Z_]+)\\\\Bootstrap/', $val)) {
                    //满足我们的需要
                    $ec = new \ReflectionClass($val);
                    //dump($ec->getMethods()); //获取方法\
                    foreach ($ec->getMethods() as $key => $func) {
                        $savePlugin[$func->name][] = [
                            'name' => rtrim($value['rel'], '\\'),
                            'fileName' => $value['abs'] . 'Bootstrap.php',
                            'class' => $val,
                            'func' => $func->name,
                        ];
                    }
                }
            }
        }
        self::$pluginList = $savePlugin;
        if (!cache('MlTree_Hook_plugin')) {
            \cache('MlTree_Hook_plugin', $savePlugin, 36000);
        }
        return true;
    }

    /**
     * 清除缓存
     */
    public static function cleanCache()
    {
        cache('MlTree_Hook_plugin', null);
    }
}
