<?php
namespace app\index\controller;

use think\Controller;

class Register extends Controller
{
    public function index()
    {
        return $this->fetch();
	}
  	
  	// 处理注册逻辑
    public function doRegister()
    {
    	$param = input('post.');
    	if(empty($param['user_name'])){
    		$this->error('用户名不能为空');
    	}

    	if(empty($param['user_pwd'])){
    		$this->error('密码不能为空');
    	}

    	// 验证用户名
    	$has = db('users')->where('user_name', $param['user_name'])->find();
    	if(!empty($has)){
    		$this->error('此用户名已存在');
    	}else{
          // 插入用户
    	  $insert = db('users') ->insert(['user_name'	=>	$param['user_name'],	'user_pwd'	=>	$param['user_pwd']]);

          
          echo "注册成功欢迎使用" . ', <a href="' . url('login/loginout') . '">返回登录页面</a>';
        }
    }
    	
}