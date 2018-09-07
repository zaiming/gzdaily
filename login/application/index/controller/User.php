<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-8-31
 * Time: 上午11:35
 */
namespace app\index\controller;

use app\index\model\ImmSysUsers;
use think\Controller;
use think\Validate;
use think\Request;

class User extends Controller
{
    // 展示用户列表
    public function index()
    {
        $userModel = new ImmSysUsers();
       // dump($userModel);
        $userList = $userModel ->select();
        //dump($userList);die;
        $this->assign([
           'user_list' =>$userList
        ]);
        return $this->fetch();
    }
    //添加用户
    public  function addUser()
    {
        if(request()->isPost()){
            $param = input('post.');
            echo $param['user_name'];
            echo $param['user_pw'];

            $userModel = new ImmSysUsers();


            $rule =[
                ['user_name','require|unique:ImmSysUsers','用户名必须|该用户名已经存在'],
                ['user_pw','require|min:5','密码必须|密码至少5位'],

            ];

            $validate = new Validate($rule);
            $result = $validate ->check($param);

            if($result){
                return json(['code'=>0,'data'=>'','msg'=>$validate->getError()]);
            }


            $flag = $userModel->insert($param);
            if(empty($flag)){
                return json(['code'=>-2,'data'=>'','msg'=>'系统错误']);
            }
            return json(['code'=>1,'data'=> '','msg'=>'添加成功']);

        }
        return $this->fetch('user/adduser');
    }
    //编辑用户
    public function editUser()
    {
        if(request()->isPost()){
            $param = input('post.');

            // 验证数据
            if(empty($param['user_name'])){
                return json(['code' => -1, 'data' => '', 'msg' => '用户名不能为空']);
            }

            $editParam = [
                'user_name' => $param['user_name']
            ];

            // 检测新修改的用户名，表中是否已经存在
            $userModel = new ImmSysUsers();
            $has = $userModel->field('id')
                ->where("user_name = '" . $param['user_name'] . "' and id != " . $param['id'])
                ->find();

            if(!empty($has)){
                return json(['code' => -2, 'data' => '', 'msg' => '该用户已经存在']);
            }

            // 查看用户是否要求改密码
            if(!empty($param['old_pwd']) && empty($param['new_pwd'])){
                return json(['code' => -3, 'data' => '', 'msg' => '修改密码需要输入新密码']);
            }

            if(empty($param['old_pwd']) && !empty($param['new_pwd'])){
                return json(['code' => -4, 'data' => '', 'msg' => '旧密码不对']);
            }

            if(!empty($param['old_pwd']) && !empty($param['new_pwd'])){

                if(strlen($param['new_pwd']) < 5){
                    return json(['code' => -5, 'data' => '', 'msg' => '新密码不得少于5位']);
                }

                $userInfo = $userModel->where('id', $param['id'])->find();
                if($param['old_pwd'] != $userInfo->user_pwd){
                    return json(['code' => -6, 'data' => '', 'msg' => '旧密码不对']);
                }

                $editParam['user_pwd'] = md5($param['new_pwd']);
            }

            $flag = $userModel->where('id', $param['id'])->update($editParam);
            if(false === $flag){
                return json(['code' => -7, 'data' => '', 'msg' => '系统错误']);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
        }
        $id = input('param.uid');
        $userModel = new ImmSysUsers();
        $userInfo = $userModel->where('id',$id)->find();
        return json(['code'=>1,'data'=>$userInfo,'msg'=>'user information']);
    }

    //删除用户
    public function delUser()
    {
        $uid = input('param.id');
        $userModel = new ImmSysUsers();

        $flag = $userModel->where('id',$uid)->delete();
        if(false==$flag){
            return json(['code'=>1,'data'=>'','msg'=>'系统错误']);

        }
        return json(['code'=>1,'data'=>'','msg'=>'删除成功']);
    }

}