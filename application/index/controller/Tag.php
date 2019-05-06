<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 22:15
 */

namespace app\index\controller;

class Tag extends Base
{
    public function index($id=0)
    {
        if($id == 0){
            $this->error('非法访问！');
        }
        //通过子查询将标签id作为条件查询出所有关联的文章数据
        $articles = model('Article')->where('id', 'In', function($query)use($id){
            $query->table('blog_article_and_tag')->where(['tag_id'=>$id])->cache('article_id')->field('article_id');
        })->paginate(5);
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