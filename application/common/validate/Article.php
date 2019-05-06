<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 17:32
 */

namespace app\common\validate;
use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title' => 'require|max:50',
        'content' => 'require',
        'excerpt' => 'max:50',
        'category_id' => 'require|integer',
        'id' => 'require|integer'
    ];

    protected $scene = [
        'add' => ['title', 'content', 'category_id'],
        'edit' => ['title', 'content', 'category_id', 'id']
    ];
}