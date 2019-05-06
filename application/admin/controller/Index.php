<?php
namespace app\admin\controller;

class Index extends Base
{
    public function index()
    {
        $user = session('user', '', 'admin');
        return $this->fetch('', [
            'user' => $user
        ]);
    }

    public function welcome()
    {
        return '欢迎来到后台管理页面！';
    }
}
