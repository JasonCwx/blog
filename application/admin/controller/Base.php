<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/3 0003
 * Time: 18:01
 */

namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        $is_Login = $this->isLogin();
        if(!$is_Login){
            $this->redirect(url('login/index'));
        }
    }

    public function isLogin()
    {
        $user = session('user', '', 'admin');
        if($user){
            return true;
        }
        return false;
    }

    public function getCurrentAdmin()
    {
        return session('user', '', 'admin');
    }

    //检测是否有相关管理权限
    public function isSuperAdmin()
    {
        $current_admin = $this->getCurrentAdmin();
        if($current_admin->is_super != 1){
            $this->redirect(url('user/index'));
        }
    }
}