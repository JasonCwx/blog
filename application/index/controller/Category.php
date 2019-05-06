<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 22:15
 */

namespace app\index\controller;

class Category extends Base
{
    public function index($id=0)
    {
        if($id == 0){
            $this->error('非法访问！');
        }
        $articles = model('Article')->where(['category_id'=>$id])->order(['create_time'=>'desc'])->paginate(5);
        if(!$articles){
            $this->error('访问出错！');
        }
        return $this->fetch('', [
            'articles' => $articles,
            'categorys' => $this->categorys,
            'tags' => $this->tags,
            'newArticles' => $this->newArticles
        ]);
    }
}