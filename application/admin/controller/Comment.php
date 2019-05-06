<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6 0006
 * Time: 13:04
 */

namespace app\admin\controller;


class Comment extends Base
{
    public function index()
    {
        //获取所有评论数据
        $comments = model('Comment')->order(['create_time'=>'desc'])->paginate(5);
        $count = model('Comment')->order(['create_time'=>'desc'])->count();
        return $this->fetch('', [
            'comments' => $comments,
            'count' => $count
        ]);
    }

    public function delete($id)
    {
        $this->isSuperAdmin();
        //根据id是否能查询到数据
        if(!$id){
            $this->error('非法访问页面！');
        }
        $data = model('Comment')->get(['id'=>$id]);
        if(empty($data)){
            $this->error('参数非法!');
        }
        //删除数据操作
        $del_id = model('Comment')->where(['id'=>$id])->delete();
        if($del_id){
            return $this->success('删除成功！', url('comment/index'));
        }else{
            return $this->error('删除失败！');
        }
    }
}