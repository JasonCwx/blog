<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 14:08
 */

namespace app\admin\controller;


use app\common\model\ArticleAndTag;

class Tag extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('Tag');
    }

    public function index()
    {
        //获取所有标签数据
        $tags = $this->obj->order(['create_time'=>'desc'])->paginate();
        //获取所有标签的总数
        $count = $this->obj->count();
        return $this->fetch('', [
            'tags' => $tags,
            'count' => $count
        ]);
    }

    public function add()
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            $data = input('post.');
            //校验数据
            $validate = validate('Tag');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //数据入库操作
            $tagId = $this->obj->save(['name'=>$data['name']]);
            if($tagId){
                return $this->success('添加标签成功！', url('tag/index'));
            }else{
                return $this->success('添加标签失败！');
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
            $validate = validate('Tag');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            //数据更新操作
            $tagId = $this->obj->save(['name'=>$data['name']], ['id'=>$data['id']]);
            if($tagId){
                return $this->success('更新标签成功！', url('tag/index'));
            }else{
                return $this->success('更新标签失败！');
            }
        }else{
            //根据ID查询数据
            $tag = $this->obj->get(['id'=>$id]);
            return $this->fetch('', [
                'tag' => $tag
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
        //将关联表中的数据删除
        model('ArticleAndTag')->where(['tag_id'=>$id])->delete();
        //删除数据操作
        $del_id = $this->obj->where(['id'=>$id])->delete();
        if($del_id){
            return $this->success('删除成功！', url('tag/index'));
        }else{
            return $this->error('删除失败！');
        }
    }
}