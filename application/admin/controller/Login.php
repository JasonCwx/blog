<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/3 0003
 * Time: 18:08
 */

namespace app\admin\controller;
use think\Controller;
date_default_timezone_set('Asia/Shanghai');

class Login extends Controller
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('AdminUser');
    }

    public function index()
    {
        if(request()->isPost())
        {
            //获取用户提交的数据
            $data = input('post.');
            //校验数据
            $validate = validate('AdminUser');
            if(!$validate->scene('login')->check($data))
            {
                $this->error($validate->getError());
            }
            //判断验证码输入是否正确
            if(!captcha_check($data['captcha_code']))
            {
                $this->error('验证码输入错误！');
            }
            $user = $this->obj->get(['username'=>$data['username']]);
            if(!$user){
                $this->error('该用户不存在！');
            }
            $password = md5($data['password']);
            if($password != $user->password){
                $this->error('密码不正确！');
            }
            //更新最后登录时间
            $time = date('Y-m-d H:i:s');
            $this->obj->save(['last_login'=>$time], ['id'=>$user->id]);
            //写入session
            session('user', $user, 'admin');
            $this->success('登录成功', url('index/index'));
        }
        return $this->fetch();
    }

    //切换账号跟退出逻辑实际上都是清空session并且跳转页面
    public function logout()
    {
        session('user', null, 'admin');
        $this->redirect(url('login/index'));
    }
}