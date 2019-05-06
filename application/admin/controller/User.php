<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6 0006
 * Time: 12:14
 */

namespace app\admin\controller;


class User extends Base
{
    public function index()
    {
        //获取所有前台用户数据
        $users = model('User')->order(['last_login'=>'desc'])->paginate(5);
        $count = model('User')->order(['last_login'=>'desc'])->count();
        return $this->fetch('', [
            'users' => $users,
            'count' => $count
        ]);
    }

    public function add()
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            $data = input('post.');
            //判断两次密码输入是否一致
            if($data['password'] != $data['re_password']){
                $this->error('两次密码输入不一致');
            }
            //校验数据
            $validate = validate('User');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //判断用户名、邮箱、手机是否存在
            $usernameInfo = model('User')->where(['username'=>$data['username']])->find();
            if($usernameInfo){
                $this->error('该用户名已存在！');
            }
            $emailInfo = model('User')->where(['email'=>$data['email']])->find();
            if($emailInfo){
                $this->error('该邮箱已存在！');
            }
            $phoneInfo = model('User')->where(['phone'=>$data['phone']])->find();
            if($phoneInfo){
                $this->error('该手机已存在！');
            }
            $data['code'] = mt_rand(1000, 100000);
            //数据入库操作
            $userData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'password' => md5($data['password']),
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => $data['status'],
                'code' => $data['code']
            ];
            $result = model('User')->save($userData);
            if($result){
                if($data['status'] == 0){
                    $active_url = 'my.blog.com/index/register/active/code/'.$data['code'].'.html';
                    $content = '点击链接跳转至激活页面'.$active_url;
                    sendMail($data['email'], '激活账号', $content);
                }
                return $this->success('添加成功！', url('user/index'));
            }else{
                return $this->success('添加失败！');
            }
        }else{
            return $this->fetch();
        }
    }

    public function edit($id)
    {
        $this->isSuperAdmin();
        if(!$id){
            $this->error('非法访问！');
        }
        //根据id查询对应的用户数据
        $user = model('User')->get(['id'=>$id]);
        if(!$user){
            $this->error('参数错误！');
        }
        if(request()->isPost()){
            $data = input('post.');
            //检查两次新密码输入是否一致
            if($data['password'] != $data['re_password']){
                $this->error('两次密码输入不一致');
            }
            //校验数据
            $validate = validate('User');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            //匹配旧密码是否一致
            if(md5($data['old_password']) != $user->password){
                $this->error('旧密码不正确');
            }
            //更新用户数据
            $data['code'] = $user->code;
            if($data['status'] != $user->status){
                $data['code'] = mt_rand(1000, 100000);
            }
            $userData = [
                'password' => md5($data['password']),
                'status' => $data['status'],
                'code' => $data['code']
            ];
            $result = model('User')->where(['id'=>$id])->update($userData);
            if($result ){
                return $this->success('更新用户信息成功！', url('user/index'));
            }else{
                return $this->error('更新用户信息失败！');
            }
        }else{
            return $this->fetch('', [
                'user' => $user
            ]);
        }
    }

    public function status($id, $status)
    {
        $this->isSuperAdmin();
        $data = [
            'id' => $id,
            'status' => $status
        ];
        $validate = validate('User');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }
        $user = model('User')->get(['id'=>$data['id']]);
        if(!$user){
            $this->error('参数错误！');
        }
        //更新数据
        $data['code'] = $user->code;
        if($data['status'] == 0){
            $data['code'] = mt_rand(1000, 100000);
        }
        $userData = [
            'status' => $data['status'],
            'code' => $data['code']
        ];
        $result = model('User')->where(['id'=>$data['id']])->update($userData);
        if($result){
            return $this->success('更新状态成功！');
        }else{
            return $this->error('更新状态失败！');
        }
    }

    public function delete($id)
    {
        $this->isSuperAdmin();
        //根据id查询用户数据
        if(!$id){
            $this->error('非法访问页面！');
        }
        $user = model('User')->get(['id'=>$id]);
        if(!$user){
            $this->error('非法参数！');
        }
        //删除前台用户要将用户的评论一并删除
        model('Comment')->where(['user_id'=>$id])->delete();
        $del_id = model('User')->where(['id'=>$id])->delete();
        if($del_id){
            return $this->success('删除用户成功！', url('user/index'));
        }else{
            return $this->error('删除用户失败！');
        }
    }
}