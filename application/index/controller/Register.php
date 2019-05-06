<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 14:59
 */

namespace app\index\controller;
use think\Controller;

class Register extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function add()
    {
        if(request()->isPost()){
            $data = input('post.');
            //判断两次密码输入是否一致
            if($data['password'] != $data['re_password']){
                $this->error('两次密码输入不一致！');
            }
            //校验数据
            $validate = validate('User');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //判断用户名、邮箱、手机是否已经存在
            $usernameInfo = model('User')->get(['username'=>$data['username']]);
            if($usernameInfo){
                $this->error('该用户名已被注册！');
            }
            $emailInfo = model('User')->get(['email'=>$data['email']]);
            if($emailInfo){
                $this->error('该邮箱已被注册！');
            }
            $phoneInfo = model('User')->get(['phone'=>$data['phone']]);
            if($phoneInfo){
                $this->error('该手机已被注册！');
            }
            //数据入库操作
            $data['status'] = 0;
            $data['code'] = mt_rand(1000, 100000);
            $userData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'password' => md5($data['password']),
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => $data['status'],
                'code' => $data['code']
            ];
            $result = model('User')->save($userData);
            if($result){
                $active_url = 'my.blog.com/index/register/active/code/'.$data['code'].'.html';
                $content = '点击链接跳转至激活页面'.$active_url;
                if(sendMail($data['email'], '激活您的账号', $content)){
                    $this->success('注册成功！已经发送邮件，请注意查收邮件激活账号', url('login/index'));
                }
            }else{
                $this->error('注册成功！');
            }
        }else{
            $this->redirect(url('register/index'));
        }
    }

    public function active($code)
    {
        if(!$code){
            $this->error('非法访问！');
        }
        $user = model('User')->get(['code'=>$code]);
        if(!$user){
            $this->error('激活码出错！');
        }
        if($user->status != 0){
            $this->error('账号已经激活！', url('login/index'));
        }
        $result = model('User')->where(['id'=>$user->id])->update(['status'=>1]);
        if($result){
            $this->success('激活成功！', url('login/index'));
        }else{
            $this->success('激活失败！');
        }
    }
}