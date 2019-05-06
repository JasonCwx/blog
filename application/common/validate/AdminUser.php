<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/3 0003
 * Time: 18:39
 */

namespace app\common\validate;
use think\Validate;

class AdminUser extends Validate
{
    protected $rule = [
        'username' => 'require|max:30',
        'password' => 'require',
        're_password' => 'require',
        'old_password' => 'require',
        'name' => 'require|max:10',
        'email' => 'require|email',
        'captcha_code' => 'require',
        'phone' => 'require|length:11',
        'is_super' => 'require|in:1, 0',
        'id' => 'require|integer'
    ];

    protected $message = [
        'captcha_code.require' => '验证码不能为空!',
    ];

    protected $scene = [
        'login' => ['username', 'password', 'captcha_code'],
        'add' => ['name', 'username', 'password', 're_password', 'email', 'phone', 'is_super'],
        'edit' => ['old_password', 'password', 're_password', 'is_super', 'id']
    ];
}