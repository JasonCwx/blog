<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 14:34
 */

namespace app\common\validate;
use think\Validate;

class Tag extends Validate
{
    protected $rule = [
        'name' => 'require|max:30',
        'id' => 'require|integer'
    ];

    protected $message = [
        'name|require' => '标签名必须填写'
    ];

    protected $scene = [
        'add' => ['name'],
        'edit' => ['name', 'id']
    ];
}