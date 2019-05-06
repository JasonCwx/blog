<?php
namespace app\admin\controller;

class AdminUser extends Base
{
    public function index()
    {
        //获取全部管理员
        $users = model('AdminUser')->order(['create_time'=>'desc'])->paginate();
        //获取管理员数据总数
        $count = model('AdminUser')->count();
        return $this->fetch('', [
            'users' => $users,
            'count' => $count
        ]);
    }

    public function add()
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            //获取提交的数据
            $data = input('post.');
            //首先判断两次密码是否输入一致
            if($data['password'] != $data['re_password']){
                $this->error('两次密码输入不一致!');
            }
            //校验数据
            $validate = validate('AdminUser');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }

            //查询用户名或者邮箱还有手机号码是否已存在
            $username = model('AdminUser')->get(['username'=>$data['username']]);
            if($username){
                $this->error('该用户名已被注册，请重新输入!');
            }
            $email = model('AdminUser')->get(['email'=>$data['email']]);
            if($email){
                $this->error('该邮箱已被注册，请重新输入!');
            }
            $phone = model('AdminUser')->get(['phone'=>$data['phone']]);
            if($phone){
                $this->error('该手机号码已被注册，请重新输入!');
            }
            //数据入库
            $adminUserData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'password' => md5($data['password']),
                'email' => $data['email'],
                'phone' => $data['phone'],
                'is_super' => $data['is_super']
            ];
            $adminUserId = model('AdminUser')->save($adminUserData);
            if($adminUserId){
                return $this->success('添加成功！', url('user/index'));
            }else{
                return $this->error('添加失败！');
            }
        }else{
            return $this->fetch();
        }
    }

    public function edit($id=0)
    {
        $this->isSuperAdmin();
        if(request()->isPost()){
            $data = input('post.');
            //判断两次新密码输入是否一致
            if($data['password'] != $data['re_password']){
                $this->error('两次新密码输入不一致！');
            }
            //校验数据
            $validate = validate('AdminUser');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            //查询旧密码是否正确
            $user = model('AdminUser')->get(['id'=>$data['id']]);
            if(md5($data['old_password']) != $user->password){
                $this->error('旧密码不正确！');
            }

            //数据更新操作
            $adminUserData = [
                'password' => md5($data['password']),
                'is_super' => $data['is_super']
            ];
            $adminUserId = model('AdminUser')->save($adminUserData, ['id'=>$id]);
            if($adminUserId){
                return $this->success('更新数据成功！', url('user/index'));
            }else{
                return $this->error('更新数据失败！');
            }
        }else{
            $user = model('AdminUser')->get(['id'=>$id]);
            if(!$user){
                return $this->error('访问出错！');
            }
            return $this->fetch('', [
                'user' => $user
            ]);
        }
    }

    public function delete($id)
    {
        $this->isSuperAdmin();
        //根据id是否能查询到数据
        $data = model('AdminUser')->get(['id'=>$id]);
        if(empty($data)){
            $this->error('参数非法!');
        }
        //删除数据操作
        $del_id = model('AdminUser')->where(['id'=>$id])->delete();
        if($del_id){
            return $this->success('删除成功！', url('category/index'));
        }else{
            return $this->error('删除失败！');
        }
    }
}