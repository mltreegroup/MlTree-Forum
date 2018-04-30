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

namespace think\db\builder;

use think\db\Builder;
<<<<<<< HEAD
use think\db\Expression;
=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
use think\db\Query;

/**
 * mysql数据库驱动
 */
class Mysql extends Builder
{
    // 查询表达式解析
    protected $parser = [
        'parseCompare'     => ['=', '<>', '>', '>=', '<', '<='],
        'parseLike'        => ['LIKE', 'NOT LIKE'],
        'parseBetween'     => ['NOT BETWEEN', 'BETWEEN'],
        'parseIn'          => ['NOT IN', 'IN'],
        'parseExp'         => ['EXP'],
        'parseRegexp'      => ['REGEXP', 'NOT REGEXP'],
        'parseNull'        => ['NOT NULL', 'NULL'],
        'parseBetweenTime' => ['BETWEEN TIME', 'NOT BETWEEN TIME'],
        'parseTime'        => ['< TIME', '> TIME', '<= TIME', '>= TIME'],
        'parseExists'      => ['NOT EXISTS', 'EXISTS'],
<<<<<<< HEAD
        'parseColumn'      => ['COLUMN'],
=======
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    ];

    protected $insertAllSql = '%INSERT% INTO %TABLE% (%FIELD%) VALUES %DATA% %COMMENT%';
    protected $updateSql    = 'UPDATE %TABLE% %JOIN% SET %SET% %WHERE% %ORDER%%LIMIT% %LOCK%%COMMENT%';

    /**
     * 生成insertall SQL
     * @access public
     * @param  Query     $query   查询对象
     * @param  array     $dataSet 数据集
     * @param  bool      $replace 是否replace
     * @return string
     */
    public function insertAll(Query $query, $dataSet, $replace = false)
    {
        $options = $query->getOptions();

        // 获取合法的字段
        if ('*' == $options['field']) {
            $allowFields = $this->connection->getTableFields($options['table']);
        } else {
            $allowFields = $options['field'];
        }

        // 获取绑定信息
        $bind = $this->connection->getFieldsBind($options['table']);

        foreach ($dataSet as $k => $data) {
            $data = $this->parseData($query, $data, $allowFields, $bind, '_' . $k);

            $values[] = '( ' . implode(',', array_values($data)) . ' )';

            if (!isset($insertFields)) {
                $insertFields = array_keys($data);
            }
        }

        $fields = [];
        foreach ($insertFields as $field) {
            $fields[] = $this->parseKey($query, $field);
        }

        return str_replace(
            ['%INSERT%', '%TABLE%', '%FIELD%', '%DATA%', '%COMMENT%'],
            [
                $replace ? 'REPLACE' : 'INSERT',
                $this->parseTable($query, $options['table']),
                implode(' , ', $fields),
                implode(' , ', $values),
                $this->parseComment($query, $options['comment']),
            ],
            $this->insertAllSql);
    }

    /**
     * 正则查询
     * @access protected
<<<<<<< HEAD
     * @param  Query        $query        查询对象
     * @param  string       $key
     * @param  string       $exp
     * @param  Expression   $value
     * @param  string       $field
     * @return string
     */
    protected function parseRegexp(Query $query, $key, $exp, Expression $value, $field)
    {
        return $key . ' ' . $exp . ' ' . $value->getValue();
=======
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @return string
     */
    protected function parseRegexp(Query $query, $key, $exp, $value, $field)
    {
        return $key . ' ' . $exp . ' ' . $value;
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
    }

    /**
     * 字段和表名处理
     * @access public
     * @param  Query     $query 查询对象
<<<<<<< HEAD
     * @param  mixed     $key   字段名
     * @param  bool      $strict   严格检测
     * @return string
     */
    public function parseKey(Query $query, $key, $strict = false)
    {
        if (is_int($key)) {
            return $key;
        } elseif ($key instanceof Expression) {
            return $key->getValue();
        }

=======
     * @param  string    $key   字段名
     * @return string
     */
    public function parseKey(Query $query, $key)
    {
        if (is_int($key)) {
            return $key;
        }
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        $key = trim($key);

        if (strpos($key, '->') && false === strpos($key, '(')) {
            // JSON字段支持
<<<<<<< HEAD
            list($field, $name) = explode('->', $key, 2);

            return 'json_extract(' . $this->parseKey($query, $field) . ', \'$.' . str_replace('->', '.', $name) . '\')';
=======
            list($field, $name) = explode('->', $key);

            $key = 'json_extract(' . $this->parseKey($query, $field) . ', \'$.' . $name . '\')';
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
        } elseif (strpos($key, '.') && !preg_match('/[,\'\"\(\)`\s]/', $key)) {
            list($table, $key) = explode('.', $key, 2);

            $alias = $query->getOptions('alias');

            if ('__TABLE__' == $table) {
                $table = $query->getOptions('table');
                $table = is_array($table) ? array_shift($table) : $table;
            }

            if (isset($alias[$table])) {
                $table = $alias[$table];
            }
        }

<<<<<<< HEAD
        if ('*' != $key && ($strict || !preg_match('/[,\'\"\*\(\)`.\s]/', $key))) {
=======
        if (!preg_match('/[,\'\"\*\(\)`.\s]/', $key)) {
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
            $key = '`' . $key . '`';
        }

        if (isset($table)) {
            if (strpos($table, '.')) {
                $table = str_replace('.', '`.`', $table);
            }

            $key = '`' . $table . '`.' . $key;
        }

        return $key;
    }

    /**
<<<<<<< HEAD
=======
     * field分析
     * @access protected
     * @param  Query     $query     查询对象
     * @param  mixed     $fields    字段名
     * @return string
     */
    protected function parseField(Query $query, $fields)
    {
        $fieldsStr = parent::parseField($query, $fields);
        $options   = $query->getOptions();

        if (!empty($options['point'])) {
            $array = [];
            foreach ($options['point'] as $key => $field) {
                $key     = !is_numeric($key) ? $key : $field;
                $array[] = 'AsText(' . $this->parseKey($query, $key) . ') AS ' . $this->parseKey($query, $field);
            }
            $fieldsStr .= ',' . implode(',', $array);
        }

        return $fieldsStr;
    }

    /**
     * 数组数据解析
     * @access protected
     * @param  array  $data
     * @return mixed
     */
    protected function parseArrayData($data)
    {
        list($type, $value) = $data;

        switch (strtolower($type)) {
            case 'exp':
                $result = $value;
                break;
            case 'point':
                $fun   = isset($data[2]) ? $data[2] : 'GeomFromText';
                $point = isset($data[3]) ? $data[3] : 'POINT';
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                $result = $fun . '(\'' . $point . '(' . $value . ')\')';
                break;
            default:
                $result = false;
        }

        return $result;
    }

    /**
>>>>>>> 6928a1dd3b68a0566efc3d1ca688202d4372c416
     * 随机排序
     * @access protected
     * @param  Query     $query        查询对象
     * @return string
     */
    protected function parseRand(Query $query)
    {
        return 'rand()';
    }

}
