<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 15:21
 */

namespace app\api\controller;
use think\Controller;

class User extends Controller
{
    public function checkUserName()
    {
        $username = input('post.value');
        $info = model('User')->get(['username'=>$username]);
        if($info){
            return show(0, '该用户名已被注册!');
        }else{
            return show(1);
        }
    }

    public function checkEmail()
    {
        $email = input('post.value');
        $info = model('User')->get(['email'=>$email]);
        if($info){
            return show(0, '该邮箱已被注册!');
        }else{
            return show(1);
        }
    }

    public function checkPhone()
    {
        $phone = input('post.value');
        $info = model('User')->get(['phone'=>$phone]);
        if($info){
            return show(0, '该手机已被注册!');
        }else{
            return show(1);
        }
    }
}