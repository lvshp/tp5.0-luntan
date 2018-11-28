<?php

use think\Route;

Route::get('/','admin/index/index');    // 首页

Route::get('/article/:id','admin/index/article'); //文章页
Route::get('/new-article','admin/index/add');  //发表文章视图
Route::post('/doadd','admin/index/doadd');      //添加文章
Route::post('/ajax_reply','admin/index/ajax_reply');  // ajax添加评论
Route::get('/editreply/:id','admin/index/editreply'); // 修改评论表单页面
Route::post('doedit_reply','admin/index/doedit_reply'); // 修改评论
Route::post('reply/delreply','admin/index/delreply');     //删除评论

Route::post('/signUp','admin/user/signUp');     //注册
Route::post('/login','admin/user/login');       //登录
Route::get('/logout','admin/user/logout');      //注销登录