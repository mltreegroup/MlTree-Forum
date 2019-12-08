<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use app\model\Users;

class User extends BaseController
{
    /**
     * 获取User自身信息
     */
    public function index()
    {
        $user = Users::find($this->request->jwt->uid);
        $user->topics;
        $user->password = "secrecy";
        return $this->out('success', $user);
    }

    /**
     * 读取指定用户信息
     * @param int $uid 用户ID
     * @return \think\Response
     */
    public function read()
    {
        $user = Users::find($this->request->param('uid'));
        if ($user->isEmpty()) {
            return $this->out(('user does not exist'), [], -11);
        }
        $user->password = 'exist';
        $user->topics;
        $user->group;
        return $this->out('success', $user);
    }

    /**
     * 修改用户信息
     */
    public function update()
    {
        if ($this->request->isPost()) {
            $user = Users::find($this->request->jwt->uid);
            $data = $this->request->post(['nick', 'motto', 'avatar']);
            $user->save($data);
            $user->password = 'secrecy';
            return $this->out('success', $user);
        }
    }

    /**
     * 用户登录
     * @param string $user
     * @param string $pwd
     * @return \think\Response
     */
    public function login()
    {
        if ($this->request->isPost()) {
            try {
                $this->validate($this->request->post(), 'app\validate\User.login');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }

            $user = $this->request->post('user');
            $pwd = $this->request->post('pwd');

            $res = Users::Login($user, $pwd);

            if ($res[0]) {
                return $this->out('Login successfully', ['JsonToken' => $res[1]]);
            } else {
                return $this->out($res[1], [], -12);
            }
        }
    }

    public function logout()
    {
        if ($this->request->isOptions()) {
            return 'ok';
        }
        \think\facade\Cache::delete('user_' . $this->request->jwt->uid . '_jwt');
        return $this->out('success');
    }

    public function register()
    {
        if ($this->request->isPost()) {

            try {
                $this->validate($this->request->post(), 'app\validate\User.register');
            } catch (\Throwable $th) {
                return $this->out(($th->getError()), [], 101);
            }

            $email = $this->request->post('email');
            $nick = $this->request->post('nick');
            $pwd = $this->request->post('pwd');

            $res = Users::Register($email, $nick, $pwd);

            if ($res[0]) {
                return $this->out($res[1]);
            } else {
                return $this->out($res[1], [], -13);
            }
        }
    }

    public function changePwd()
    {
        if ($this->request->isPost()) {
            $old = $this->request->post('old');
            $new = $this->request->post('new');

            if (empty($old) || empty($new)) {
                return $this->out('Old Or New Password is required', [], 101);
            }

            $res = Users::changePwd($old, $new);

            if ($res[0]) {
                return $this->out($res[1]);
            } else {
                return $this->out($res[1], [], -14);
            }
        }
    }

    public function openForget()
    {
        if ($this->request->isPost()) {
            $email = $this->request->post('email');
            $user = Users::find($this->request->jwt->uid);
            if ($email !== $user->email) {
                return $this->out('Account error', [], -15);
            }
            $code = \createStr(64);
            $time = time() + (int) \app\model\Options::getValue('reg_active_time');

            $user->handle_code = $code;
            $user->overdue_time = $time;
            $user->save();

            $mail = new \app\common\Mail;
            $mail->SendForgetCode($user, $code);
            return $this->out('success');
        }
    }

    public function forget($uid, $code, $time)
    {
        if ($this->request->isPost()) {
            $user = Users::find($uid);
            if ($user->handle_code === $code && (int) $user->overdue_time > (int) $time) {
                $new = $this->request->post('pwd');
                $user->password = password_hash($new, PASSWORD_DEFAULT);
                $user->handle_code = null;
                $user->overdue_time = null;
                $user->save();

                return $this->out('success');
            }
            return $this->out('success');
        }
    }

    public function activation($uid, $code, $time)
    {
        $user = Users::find($uid);
        if (empty($user)) {
            return $this->out('user does not exist', [], -16);
        } elseif ($user->overdue_time < time()) {
            return $this->out('Expired', [], -17);
        } elseif ($user->handle_code === $code) {
            $user->handle_code = null;
            $user->overdue_time = null;
            $user->status = 1;
            $user->save();
            return $this->out('Successful activation');
        } else {
            return $this->out('Code error', [], -18);
        }

    }

    public function reactivation($uid)
    {
        $user = Users::find($uid);
        if (empty($user)) {
            return $this->out('user does not exist', [], -16);
        }
        if ($user->status == 0) {
            $code = \createStr(64);
            $time = time() + (int) \app\model\Options::getValue('reg_active_time');

            $user->handle_code = $code;
            $user->overdue_time = $time;
            $user->save();

            $mail = new \app\common\Mail;
            $mail->SendActiveLink($user, $code, $time);
            return $this->out('success');
        }
    }
}
