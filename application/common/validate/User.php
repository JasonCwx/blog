<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 16:01
 */

namespace app\common\validate;
use think\Validate;

class User extends Validate
{
    protected $rule = [
        'name' => 'require|max:20',
        'username' => 'require|max:50',
        'old_password' => 'require|min:8',
        'password' => 'require|min:8',
        're_password' => 'require|min:8',
        'email' => 'require|email',
        'phone' => 'require|length:11',
        'id' => 'require|integer',
        'captcha' => 'require',
        'status' => 'require|integer|in:0,1'
    ];

    protected $scene = [
        'add' => ['name', 'username', 'password', 're_password', 'email', 'phone'],
        'login' => ['username', 'password', 'captcha'],
        'edit' => ['password', 'old_password', 're_password', 'id', 'status'],
        'status' => ['id', 'status']
    ];
}