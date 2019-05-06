<?php
namespace app\index\controller;

class Index extends Base
{

    public function index()
    {
        $articles = model('Article')->order(['update_time'=>'desc'])->paginate(5);
        return $this->fetch('', [
            'articles' => $articles,
            'categorys' => $this->categorys,
            'tags' => $this->tags,
            'newArticles' => $this->newArticles
        ]);
    }

    public function detail($id)
    {
        if(!$id){
            $this->error('非法访问！');
        }
        $article = model('Article')->get(['id'=>$id]);
        if(!$article){
            $this->error('参数错误！');
        }
        model('Article')->where(['id'=>$id])->setInc('click');
        $user = session('user', '', 'index');
        //获取该文章下的评论数据
        $comments = model('Comment')->where(['article_id'=>$id])->paginate(5);
        return $this->fetch('', [
            'article' => $article,
            'categorys' => $this->categorys,
            'tags' => $this->tags,
            'newArticles' => $this->newArticles,
            'user' => $user,
            'comments' => $comments
        ]);
    }
}
