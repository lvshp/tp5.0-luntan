<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Parser;
use think\config\driver\Json;
use think\response;
class User extends Controller
{
    // 注册
    public function signUp()
    {
        $data = input();
        $validate = new \app\admin\logic\User;
        if(!$validate->check($data)){
            return Json(array('status'=>'2','message'=>$validate->getError()));
        }

        // $model = new app\admin\model\UserModel;
        $model = new \app\admin\model\User();
        $res = $model->addUser($data);
        if($res == '1'){
            $reg = ['status'=>'1','message'=>'注册成功'];
        }elseif($res == '3'){
            $reg = ['status'=>'3','message'=>'用户名或邮箱已经被注册'];
        }else{
            $reg = ['status'=>'4','message'=>'服务器繁忙，注册失败，请稍后尝试或联系管理员'];
        }
        
        return Json($reg);
    }

    // 登录
    public function login()
    {
        $data = input();
        $validate = new \app\admin\logic\Login;
        if(!$validate->check($data)){
            return Json(array('status'=>'2','msg'=>$validate->getError()));
        }

        $model = new \app\admin\model\User();
        $res = $model->login($data);
        if($res == '1'){
            $reg = ['status'=>'1','msg'=>'登陆成功'];
        }elseif($res == '3'){
            $reg = ['status'=>'3','msg'=>'用户不存在'];
        }else{
            $reg = ['status'=>'2','msg'=>'用户名或密码输入错误'];
        }
        return Json($reg);
    }
    //注销
    public function logout()
    {
        session('USER_INFO',null);
        echo "<script>alert('退出成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
    }
}
