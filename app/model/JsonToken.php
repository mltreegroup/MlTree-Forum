<?php
declare (strict_types = 1);

namespace app\model;

use think\facade\Config;

class JsonToken
{
    /**
     * 创建一个JWT
     * @param string $iss 签发人
     * @param string $sub 主题
     * @param int $nbf 生效时间
     * @param array $param 私有字段定义
     * @param mixed $payload 载荷返回
     * @return string $jwt 生成的jsontoken
     */
    public static function createJWT(string $iss, $sub = '', $nbf = 0, $param = [])
    {
        $config = Config::get('jsontoken');
        $jwtHeader = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = [
            'iss' => $iss,
            'exp' => time() + $config['jwtExpTime'],
            'sub' => $sub,
            'nbf' => $nbf == 0 ? time() : $nbf,
            'iat' => time(),
        ];
        // 合并两个数组
        $payload = array_merge($payload, $param);
        $payload = json_encode($payload);
        // 处理sign
        $sign = base64url_encode($jwtHeader) . "." . base64url_encode($payload) . "." . $config['jwtSecret'];
        // hash加密
        $sign = \hash_hmac('sha256', $sign, $config['jwtSecret']);

        // 生成jwt
        $jwt = base64url_encode($jwtHeader) . "." . base64url_encode($payload) . "." . $sign;

        return $jwt;
    }

    public static function checkJWT(string $jwt)
    {
        // 将JWT分割
        $data = explode(".", $jwt);
        // 指定部分取出
        $header = $data[0];
        $payload = $data[1];
        $sign = $data[2];
        // 部分编码
        $_sign = $header . "." . $payload . "." . Config::get('jsontoken.jwtSecret');
        // hash加密
        $_sign = \hash_hmac('sha256', $_sign, Config::get('jsontoken.jwtSecret'));
        //对比hash
        if ($_sign === $sign) {
            $payload = json_decode(base64url_decode($payload));
            if ($payload->iat < time() && $payload->nbf < time() && $payload->exp > time()) {
                return [true, $payload];
            } else {
                return [false,'JsonToken Expired'];
            }
        } else {
            return [false,'sign validation error'];
        }
    }

}
