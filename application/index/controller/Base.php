<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 22:19
 */

namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    public $categorys;
    public $tags;
    public $newArticles;

    public function _initialize()
    {
        $this->categorys = model('Category')->order(['create_time'=>'desc'])->select();
        $this->tags = model('Tag')->order(['create_time'=>'desc'])->select();
        $this->newArticles = model('Article')->order(['create_time'=>'desc'])->limit(5)->select();
    }
}