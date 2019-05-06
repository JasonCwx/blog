<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 14:33
 */

namespace app\index\controller;
use think\Controller;

date_default_timezone_set('Asia/Shanghai');
class Login extends Controller
{
    public function index()
    {
        if (session('user', '', 'index')) {
            return $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }

    public function login()
    {
        if (request()->isPost()) {
            $data = input('post.');
            //判断验证码是否输入正确
            if (!captcha_check($data['captcha'])) {
                $this->error('验证码输入错误！');
            }
            //校验数据
            $validate = validate('User');
            if (!$validate->scene('login')->check($data)) {
                $this->error($validate->getError());
            }
            //根据username查询对应数据
            $user = model('User')->get(['username' => $data['username']]);
            if (!$user && $user->status != 1) {
                $this->error('用户名不存在或账号未激活！');
            }
            //对比密码是否正确
            $password = $user->password;
            if (md5($data['password']) != $password) {
                $this->error('密码不正确！');
            }
            //写入session，并更新最后登录时间
            $time = date('Y-m-d H:i:s');
            model('User')->save(['last_login' => $time], ['id' => $user->id]);
            session('user', $user, 'index');
            return $this->success('登录成功！', url('index/index'));
        } else {
            return $this->redirect(url('login/index'));
        }
    }

    public function logout()
    {
        session('user', null, 'index');
        return $this->redirect(url('login/index'));
    }
}