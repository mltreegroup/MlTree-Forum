<?php
// +----------------------------------------------------------------------
// | MLTree Forum
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://forum.kingsr.cc All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: kingsr <admin@kingsr.cc 1143524493@qq.com>
// +----------------------------------------------------------------------

namespace Auth;
use think\facade\Session;
use think\Db;
use think\facade\Config;
/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and')
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(think_auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(think_auth_group 定义了用户组权限)
 *
 * 4，支持规则表达式。
 *      在think_auth_rule 表中定义一条规则时，如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100  表示用户的分数在5-100之间时这条规则才会通过。
 */
//数据库
/*
-- ----------------------------
-- mf_auth_rule，规则表，
-- id:主键，name：规则唯一标识, title：规则中文名称 status 状态：为1正常，为0禁用，condition：规则表达式，为空表示存在就验证，不为空表示按照条件验证
-- ----------------------------
DROP TABLE IF EXISTS `mf_auth_rule`;
CREATE TABLE `mf_auth_rule` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` char(80) NOT NULL DEFAULT '',
    `title` char(20) NOT NULL DEFAULT '',
    `type` tinyint(1) NOT NULL DEFAULT '1',
    `status` tinyint(1) NOT NULL DEFAULT '1',
    `condition` char(100) NOT NULL DEFAULT '',  # 规则附件条件,满足附加条件的规则,才认为是有效的规则
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- mf_auth_group 用户组表，
-- id：主键， groupName:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用
-- ----------------------------
DROP TABLE IF EXISTS `mf_auth_group`;
    CREATE TABLE `mf_auth_group` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `groupName` char(100) NOT NULL DEFAULT '',
    `status` tinyint(1) NOT NULL DEFAULT '1',
    `rules` char(80) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- mf_auth_group_access 用户组明细表
-- uid:用户id，gid：用户组id
-- ----------------------------
DROP TABLE IF EXISTS `mf_auth_group_access`;
    CREATE TABLE `mf_auth_group_access` (
    `uid` mediumint(8) unsigned NOT NULL,
    `gid` mediumint(8) unsigned NOT NULL,
    UNIQUE KEY `uid_gid` (`uid`,`gid`),
    KEY `uid` (`uid`),
    KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 */
class Auth
{
    protected $_config = [
        'auth_on'           =>  true,                // 认证开关
        'auth_type'         =>  1,                   // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        =>  'group',        // 用户组数据表名
        'auth_group_access' =>  'user', // 用户-用户组关系表
        'auth_rule'         =>  'auth_rule',         // 权限规则表
        'auth_user'         =>  'user',            // 用户信息表
        'auth_user_id_field'=>  'uid',                // 用户表ID字段名
    ];
    public function __construct()
    {
        if (Config::pull('auth')) {
            $this->_config = array_merge($this->_config, Config::pull('auth'));
        }
    }
    /**
     * 检查权限
     * @param  string|array  $name     需要验证的规则列表，支持逗号分隔的权限规则或索引数组
     * @param  integer  $uid      认证用户ID
     * @param  string   $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and' 则表示需满足所有规则才能通过验证
     * @param  string   $mode     执行check的模式
     * @param  integer  $type     规则类型
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $relation = 'or', $mode = 'url', $type = 1)
    {
        if (!$this->_config['auth_on']) {
            return true;
        }
        $authList = $this->getAuthList($uid, $type);
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = [];
        if ($mode === 'url') {
            $REQUEST = unserialize(strtolower(serialize($_REQUEST)));
        }
        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ($mode === 'url' && $query != $auth) {
                parse_str($query, $param); // 解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {
                    $list[] = $auth;
                }
            } elseif (in_array($auth, $name)) {
                $list[] = $auth;
            }
        }
        if ($relation === 'or' && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation === 'and' && empty($diff)) {
            return true;
        }
        return false;
    }
    /**
     * 根据用户ID获取用户组，返回值为数组
     * @param  integer $uid 用户ID
     * @return array      用户所属用户组 ['uid'=>'用户ID', 'gid'=>'用户组ID', 'groupName'=>'用户组名', 'rules'=>'用户组拥有的规则ID，多个用英文,隔开']
     */
    public function getGroups($uid)
    {
        static $groups = [];
        if (isset($groups[$uid])) {
            return $groups[$uid];
        }
        $user_groups = Db::name($this->_config['auth_group_access'])
                        ->alias('a')
                        ->where('a.uid', $uid)
                        ->where('g.status', 1)
                        ->join($this->_config['auth_group'].' g', "a.gid = g.gid")
                        //->field('uid,gid,groupName,rules')
                        ->select();
        $groups[$uid] = $user_groups ?: [];
        return $groups[$uid];
    }
    /**
     * 获得权限列表
     * @param  integer $uid  用户ID
     * @param  integer $type 规则类型
     * @return array       权限列表
     */
    protected function getAuthList($uid, $type)
    {
        static $_authList = [];
        
        $t = implode(',', (array)$type);
        if (isset($_authList[$uid.$t])) {
           return $_authList[$uid.$t];
        }
        if ($this->_config['auth_type'] == 2 && Session::has('_AUTH_LIST_'.$uid.$t)) {
            return Session::get('_AUTH_LIST_'.$uid.$t);
        }
        // 读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = []; // 保存用户所属用户组设置的所有权限规则ID
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid.$t] = [];
            return [];
        }
        $map = [
            ['id', 'in', $ids],
            ['type', '=', $type],
            ['status', '=', 1]
        ];
        // 读取用户组所有权限规则
        $rules = Db::name($this->_config['auth_rule'])->where($map)->field('condition,name')->select();
        // 循环规则，判断结果。
        $authList = [];
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) { // 根据condition进行验证
                $user = $this->getUserInfo($uid); // 获取用户信息,一维数组
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                // dump($command); // debug
                @(eval('$condition=('.$command.');'));
                if ($condition) {
                    $authList[] = strtolower($rule['name']);
                }
            } else {
                // 只要存在就记录
                $authList[] = strtolower($rule['name']);
            }
        }
        $_authList[$uid.$t] = $authList;
        if ($this->_config['auth_type'] == 2) {
            Session::set('_AUTH_LIST_'.$uid.$t, $authList);
        }
        return array_unique($authList);
    }
    /**
     * 获得用户资料,根据自己的情况读取数据库
     */
    protected function getUserInfo($uid) {
        static $userinfo = [];
        if (!isset($userinfo[$uid])) {
            $userinfo[$uid] = Db::name($this->_config['auth_user'])->where((string)$this->_config('auth_user_id_field'), $uid)->find();
        }
        return $userinfo[$uid];
    }
}