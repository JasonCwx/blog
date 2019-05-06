<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5 0005
 * Time: 23:21
 */

namespace app\index\controller;
use think\Controller;

class Comment extends Controller
{
    public function add()
    {
        if(request()->isPost()){
            $user = session('user', '', 'index');
            if(!$user){
                $this->redirect(url('login/index'));
            }
            $data = input('post.');
            //校验数据
            $validate = validate('Comment');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //数据入库
            $commentData = [
                'user_id' => $data['user_id'],
                'parent_id' => $data['parent_id'],
                'content' => $data['content'],
                'article_id' => $data['article_id']
            ];
            $result = model('Comment')->save($commentData);
            if($result){
                $this->success('评论成功！');
            }else{
                $this->error('评论失败！');
            }
        }else{
            $this->redirect(url('index/index'));
        }
    }
}