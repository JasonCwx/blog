<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 14:08
 */

namespace app\admin\controller;


use app\common\model\ArticleAndTag;
use think\Db;

class Category extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('Category');
    }

    public function index()
    {
        //获取所有分类数据
        $categorys = $this->obj->order(['create_time'=>'desc'])->paginate();
        $count = $this->obj->count();
        return $this->fetch('', [
            'categorys' => $categorys,
            'count' => $count
        ]);
    }

    public function add()
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            $data = input('post.');
            //校验数据
            $validate = validate('Category');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //数据入库操作
            $categoryId = $this->obj->save(['name'=>$data['name']]);
            if($categoryId){
                return $this->success('添加分类成功！', url('category/index'));
            }else{
                return $this->success('添加分类失败！');
            }
        }else{
            return $this->fetch();
        }
    }

    public function edit($id)
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            $data = input('post.');
            //校验数据
            $validate = validate('Category');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            //数据更新操作
            $categoryId = $this->obj->save(['name'=>$data['name']], ['id'=>$data['id']]);
            if($categoryId){
                return $this->success('更新分类成功！', url('category/index'));
            }else{
                return $this->success('更新分类失败！');
            }
        }else{
            //根据ID查询数据
            $category = $this->obj->get(['id'=>$id]);
            return $this->fetch('', [
                'category' => $category
            ]);
        }
    }

    public function delete($id)
    {
        $this->isSuperAdmin();
        //根据id是否能查询到数据
        if(!$id){
            $this->error('非法访问页面！');
        }
        $data = $this->obj->get(['id'=>$id]);
        if(empty($data)){
            $this->error('参数非法!');
        }
        //文章删除之后对应的标签关联表也需要清除
        model('ArticleAndTag')->where('article_id', 'IN', function($query)use($id){
            $query->table('blog_article')->where(['category_id'=>$id])->field('id');
        })->delete();
        //找出关联的文章数据并将文章数据删除
        model('Article')->where(['category_id'=>$id])->delete();
        //删除数据操作
        $del_id = $this->obj->where(['id'=>$id])->delete();
        if($del_id){
            return $this->success('删除成功！', url('category/index'));
        }else{
            return $this->error('删除失败！');
        }
    }
}