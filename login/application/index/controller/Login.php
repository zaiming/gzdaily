<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-8-31
 * Time: 上午10:07
 */
namespace app\index\controller;

use think\Controller;

class Login extends Controller
{
    public function index()
    {
        echo 'nihaoya';
        return $this->fetch();
    }
    // 处理登录逻辑
    public function doLogin()
    {
        $param = input('post.');
        if(empty($param['user_name'])){

            $this->error('用户名不能为空');
        }

        if(empty($param['user_pwd'])){

            $this->error('密码不能为空');
        }
        if(empty($param['captcha'])){

            $this->error('验证码不能为空');
        }

        // 处理验证码
        if(!captcha_check($param['captcha'])){

            $this->error('验证码错误');
        };

        // 验证用户名
        $has = db('imm_sys_users')->where('user_name', $param['user_name'])->find();
        if(empty($has)){

            $this->error('用户名错误');
        }

        // 验证密码
        if($has['user_pw'] != $param['user_pwd']){

            $this->error('密码错误');
        }

        // 记录用户登录信息
        cookie('user_id', $has['id'], 3600);  // 一个小时有效期
        cookie('user_name', $has['user_name'], 3600);

        $this->redirect(url('index/index'));
    }
    // 退出登录
    public function loginOut()
    {
        cookie('user_id', null);
        cookie('user_name', null);

        $this->redirect(url('login/index'));
    }
}