<?php
use think\facade\Env;
/**
 * 获取指定目录下的子目录
 * @param string $path 相对于根目录的目录路径
 * @param bool $abs 是否生成绝对路径
 * @return array $files 子目录数组
 */
function get_dir($path, $abs = false)
{
    $path = Env::get('root_path') .  $path;
    $dir = scandir($path);
    foreach ($dir as $key => $value) {
        if ($value !== "." && $value !== "..") {
            if ($abs) {
                $files[] = [
                    "abs" => $path . $value . "/",
                    "rel" => $value . "/",
                ];
            } else {
                $files[] = $value . "/";
            }

        }
    }
    return $files;
}
