<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6 0006
 * Time: 20:27
 */

namespace app\index\controller;

class Search extends Base
{
    public function index()
    {
        $key = input('get.key');
        if(!$key){
            $this->redirect('index/index');
        }
        $articles = model('Article')->order(['create_time'=>'desc'])->where('title', 'like', '%'.$key.'%')->paginate();
        return $this->fetch('', [
            'articles' => $articles,
            'categorys' => $this->categorys,
            'tags' => $this->tags,
            'newArticles' => $this->newArticles
        ]);
    }
}