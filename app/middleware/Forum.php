<?php
declare (strict_types = 1);

namespace app\middleware;

class Forum
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //
        $response = $next($request);

        $response->header([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
        ]);

        return $response;
    }
}
