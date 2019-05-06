<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/3 0003
 * Time: 17:58
 */

namespace app\admin\controller;


class Article extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('Article');
    }

    public function index()
    {
        $articles = $this->obj->order(['create_time'=>'desc'])->paginate();
        $count = model('Article')->count();
        return $this->fetch('', [
            'articles' => $articles,
            'count' => $count
        ]);
    }

    public function add()
    {
        $this->isSuperAdmin();
        //获取所有分类数据
        $categorys = model('Category')->select();

        //获取所有标签数据
        $tags = model('Tag')->select();

        if(request()->isPost()){
            $data = input('post.');
            //校验数据
            $validate = validate('Article');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //数据入库操作

            if(!$data['excerpt']){
                $data['excerpt'] = '该文章没有摘要，请点击查看文章内容详情。。。';
            }
            $articleData = [
                'title' => $data['title'],
                'content' => $data['content'],
                'excerpt' => $data['excerpt'],
                'category_id' => $data['category_id']
            ];
            $result = $this->obj->save($articleData);
            if($result){
                //获取当前插入的数据ID
                $articleId = $this->obj->getLastInsId();
                //循环将文章标签和文章id存在一起，形成多对多的关系
                if(!empty($data['tag_id'])){
                    $datas = [];
                    foreach($data['tag_id'] as $key => $tagId){
                        $datas[$key]['tag_id'] = $tagId;
                        $datas[$key]['article_id'] = $articleId;
                    }
                    model('ArticleAndTag')->saveAll($datas);
                    unset($datas);
                }
                return $this->success('添加文章成功！', url('article/index'));
            }else{
                return $this->error('添加文章失败！');
            }
        }else{
            return $this->fetch('', [
                'categorys' => $categorys,
                'tags' => $tags
            ]);
        }
    }

    public function edit($id)
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            $data = input('post.');
            $data['content'] = htmlentities($data['content']);
            //校验数据
            $validate = validate('Article');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            //数据入库操作
            if(!$data['excerpt']){
                $data['excerpt'] = '该文章没有摘要，请点击查看文章内容详情。。。';
            }
            $articleData = [
                'title' => $data['title'],
                'content' => $data['content'],
                'excerpt' => $data['excerpt'],
                'category_id' => $data['category_id']
            ];
            $articleId = $this->obj->save($articleData, ['id'=>$data['id']]);
            if($articleId){
                return $this->success('编辑文章成功！', url('article/index'));
            }else{
                return $this->error('编辑文章失败！');
            }
        }else{
            //根据id获取对应文章数据
            $article = $this->obj->get(['id'=>$id]);
            //获取所有分类数据
            $categorys = model('Category')->select();
            //获取所有标签数据
            $tags = model('Tag')->select();
            return $this->fetch('', [
                'article' => $article,
                'tags' => $tags,
                'categorys' => $categorys
            ]);
        }
    }

    public function delete($id)
    {
        $this->isSuperAdmin();
        //根据id查询文章数据
        if(!$id){
            $this->error('非法访问页面！');
        }
        $article = $this->obj->get(['id'=>$id]);
        if(!$article){
            $this->error('非法参数！');
        }
        //将关联表中的数据也一并删除
        model('ArticleAndTag')->where(['article_id'=>$id])->delete();
        //将关联的评论一并删除
        model('Comment')->where(['article_id'=>$id])->delete();
        $del_id = $this->obj->where(['id'=>$id])->delete();
        if($del_id){
            return $this->success('删除文章成功！', url('article/index'));
        }else{
            return $this->error('删除文章失败！');
        }
    }
}