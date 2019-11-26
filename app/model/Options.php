<?php
declare (strict_types = 1);

namespace app\model;

use think\facade\Db;

class Options
{
    public static function getValues($groups = ['base'])
    {
        $t = Db::name('options')->where('type', 'in', $groups)->column('value', 'name');
        return $t;
    }

    public static function getValue($optionName)
    {
        return Db::name('options')->where('name', $optionName)->value('value');
    }

    public static function setValues($data)
    {
        foreach ($data as $key => $value) {
            Db::name('options')->where('name', $key)->setField('value', $value);
        }
    }

    public static function setValue($name, $data)
    {
        Db::name('options')->where('name', $name)->setField('value', $data);
    }
}
