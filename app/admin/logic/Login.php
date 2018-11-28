<?php
namespace app\admin\logic;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username' => 'require|token',
        'password' => 'require|min:8',
    ];

    protected $message = [
        'username.require'          => '用户名或密码不正确',
        'password.require'          => '用户名或密码不正确',
        'password.min'              => '用户名或密码不正确',
    ];

}