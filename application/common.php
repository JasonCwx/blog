<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function is_super($data)
{
    if($data == 0){
        $str = "<span class='label label-danger radius'>普通</span>";
    }else{
        $str = "<span class='label label-success radius'>超级</span>";
    }

    return $str;
}

function is_active($data)
{
    if($data == 0){
        $str = "<span class='label label-danger radius'>未激活</span>";
    }else{
        $str = "<span class='label label-success radius'>激活</span>";
    }

    return $str;
}

//公共分页样式函数
function pagination($obj)
{
    if(!$obj){
        return '';
    }

    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-blog">'.$obj->render().'</div>';
}

//根据对应标签id获取相应的值
function getTagNameById($id){
    $tag = model('Tag')->get(['id'=>$id]);
    return $tag->name;
}

//根据对应标签id获取相应的值
function getCategoryNameById($id){
    $category = model('Category')->get(['id'=>$id]);
    return $category->name;
}

function getNameById($id){
    $user = model('User')->get(['id'=>$id]);
    return $user->name;
}

function getArticleTitleById($id){
    $article = model('Article')->get(['id'=>$id]);
    return $article->title;
}

function getCountByCategory($id){
    return model('Article')->where(['category_id'=>$id])->count();
}

function getCountByTag($id){
    return model('ArticleAndTag')->where(['tag_id'=>$id])->count();
}

function getCommentCountById($id){
    return model('Comment')->where(['article_id'=>$id])->count();
}

//发送邮箱函数
function sendMail($to, $title, $content)
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    $mail->SMTPAuth=true;
    $mail->Host = 'smtp.qq.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';
    $mail->FromName = 'Jason';
    $mail->Username ='3486117125';
    $mail->Password = 'hfslwwmafhfscjib';
    $mail->From = '3486117125@qq.com';
    $mail->isHTML(true);
    $mail->addAddress($to);
    $mail->Subject = $title;
    $mail->Body = $content;
    $status = $mail->send();
    if($status){
        return true;
    }else{
        return false;
    }
}
