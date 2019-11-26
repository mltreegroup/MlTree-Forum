<?php
declare (strict_types = 1);

namespace app\middleware;

class Check
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
        $jwt = $request->header('Authorization');
        $checkList = \config('mtf.checklist');

        $controller = \request()->controller();
        $action = \request()->action();

        if ($request->isOptions() && \config('mtf.passOptionsRequest')) {
            return \response("OK");
        }

        if (empty($jwt)) {
            if (!empty($checkList[$controller]['pass'])) { // 如果验证规则中pass不为空
                if (!\in_array($action, $checkList[$controller]['pass'])) { // 则验证action是否在通过列表中，否则报错
                    return response(['code' => 1000, 'msg' => \lang('Permission error'), 'data' => 'In Check Middleware'], 401, [], 'json');
                }
            } else {
                if (\in_array($action, $checkList[$controller]['check'])) {
                    return response(['code' => 1000, 'msg' => \lang('Permission error'), 'data' => 'In Check Middleware'], 401, [], 'json');
                }
            }

            return $next($request);
        }

        $check_jwt = \app\model\JsonToken::checkJWT($jwt);
        if ($check_jwt) {
            if (\cache('user_' . $check_jwt[1]->uid . '_jwt') !== $jwt) {
                if (!empty($checkList[$controller]['pass'])) { // 如果验证规则中pass不为空
                    if (!\in_array($action, $checkList[$controller]['pass'])) { // 则验证action是否在通过列表中，否则报错
                        return response(['code' => 1001, 'msg' => \lang('JsonToken expired'), 'data' => 'In JsonToken Checker'], 401, [], 'json');
                    }
                } else {
                    if (\in_array($action, $checkList[$controller]['check'])) {
                        return response(['code' => 1001, 'msg' => \lang('JsonToken expired'), 'data' => 'In JsonToken Checker'], 401, [], 'json');
                    }
                }

            }

            // 验证用户是否拥有权限
            if (isset($checkList[$controller]['auth'])) {
                if (isset($checkList[$controller]['auth'][$action])) {
                    $_auth = $checkList[$controller]['auth'][$action];
                    $auth = new \app\Auth;
                    if ($_auth) { // 如果规则为true
                        if (!$auth->check([\ucwords($action) . $controller, 'admin'])) {
                            return \response(['code' => 1002, 'msg' => \lang('Insufficient authority'), 'data' => 'In JsonToken Checker'], 401, [], 'json');
                        }
                    } else {
                        $_auth[] = 'admin';
                        if (!$auth->check($_auth)) {
                            return \response(['code' => 1002, 'msg' => \lang('Insufficient authority'), 'data' => 'In JsonToken Checker'], 401, [], 'json');
                        }
                    }
                }
            }

            $request->jwt = $check_jwt[1];
            return $next($request);
        } else {
            return response(['code' => 1001, 'msg' => \lang($check_jwt[1]), 'data' => 'In JsonToken Checker'], 401, [], 'json');
        }

    }
}
