<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6 0006
 * Time: 20:40
 */

namespace app\index\controller;
use think\Controller;

class Contact extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}