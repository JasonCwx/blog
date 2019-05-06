<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 13:49
 */

namespace app\common\validate;
use think\Validate;

class Comment extends Validate
{
    protected $rule = [
        'user_id' => 'require|integer',
        'content' => 'require',
        'parent_id' => 'require|integer',
        'article_id' => 'require|integer'
    ];

    protected $scene = [
        'add' => ['user_id', 'content', 'parent_id', 'article_id']
    ];
}