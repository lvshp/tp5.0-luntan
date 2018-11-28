<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Session;
class User extends Model
{
    // 注册添加用户
    public function addUser($data){
        unset($data['password_confirm']);
        unset($data['__token__']);
        $res = DB::table('user')
                    ->where('nickname',$data['nickname'])
                    ->whereOr('email',$data['email'])
                    ->find();
        $data['create_time'] = date('Y-m-d H:i:s',time());
        $data['password'] = md5($data['password']);
        if($res){
            // 代表用户名或邮箱已存在
            return '3';
        }else{
            $reg = DB::table('user')->insert($data);
            if($reg){
                return 1;
            }else{
                return false;
            }
        }
    }

    // 登录验证
    public function login($data){
        $user = DB::table('user')
                    ->where('nickname',$data['username'])
                    ->whereOr('email',$data['username'])
                    ->find();
        if(!$user){
            // 代表用户不存在
            return '3';
        }
        if($user['password'] == md5($data['password'])){
            $user['article_num'] = DB::table('article')->where('author',$user['id'])->count();
            $user['reply_num'] = DB::table('reply')->where('author',$user['nickname'])->count();
            session('USER_INFO',$user);
            return 1;
        }else{
            return false;
        }
    }

}