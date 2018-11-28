<?php
namespace app\admin\logic;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'nickname' => 'require|token',
        'password' => 'require|min:8',
        'password_confirm' => 'require|confirm',
        'email'    => 'email',
    ];

    protected $message = [
        'nickname.require'          => '用户名不能为空',
        'password.require'          => '密码不能为空',
        'password_confirm.confirm'  => '确认密码与密码不一致',
        'password_confirm.require'  => '确认密码与密码不一致',
    ];

}