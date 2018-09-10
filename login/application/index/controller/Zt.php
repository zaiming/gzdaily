<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-9-1
 * Time: 下午6:58
 */
namespace app\index\controller;


use app\index\model\ZtManage;
use app\index\model\ZtType;
use think\db;
use think\Controller;
use think\Validate;


class Zt extends Controller
{
    // 展示列表
    public function index()
    {
        $ztModel = new ZtManage();
     //   $zttypeModel = new ZtType();
        // dump($userModel);
//        $ztList = $ztModel->select();
        $sql_str = "SELECT m.*, t.type_name  from zt_type t ,zt_manage m where t.id = m.type_id";
        $ztList = db::query($sql_str);
//        if(1==$ztList[0]['type_id']){
//
//        }
//        if(2==$ztList[0]['type_id']){
//
//        }
//        echo json_encode(['code'=>1,'data'=>$typeName],JSON_UNESCAPED_UNICODE);

        //dump($userList);die;
//        $this->assign([
//            'zt_list' => $ztList,
//            'type_name'=> $typeName,
//            'df'=>123
//        ]);
        $this->assign('zt_list',$ztList);

//        echo json_encode(['code'=>1,'data'=>$ztList],JSON_UNESCAPED_UNICODE);

        return $this->fetch();
    }
    //添加专题
    public  function addZt()
    {
        if(request()->isPost()){
            $param = input('post.');
            $rule = [
                ['name','require|unique:ZtManage','专题名必须|该专题已经存在'],
            ];
            $validate = new Validate($rule);
            $result = $validate->check($param);
            if(!$result){
                return json(['code'=>-1,'data'=> '','msg' => $validate->getError()]);
            }
            $ztModel = new ZtManage();
    //        echo json_encode(['code'=>1,'data'=>$param],JSON_UNESCAPED_UNICODE);
            $flag = $ztModel->insert($param);
            if(empty($flag)){
                return json(['code'=>-2,'data'=>'','msg'=>'系统错误']);
            }
            return json(['code'=>1,'data'=>'','msg'=>'添加专题成功']);
        }
        $id = input('param.uid');
        $ztModel = new ZtManage();
        $ztInfo = $ztModel->where('id',$id)->find();
        return json(['code'=>1,'data'=>$ztInfo,'msg'=>'同类专题信息']);
    }
    //编辑专题
    public function editZt()
    {
        if(request()->isPost()){
            $param = input('post.');
            //验证数据
            if(empty($param['name'])){
                return json(['code' => -1, 'data' => '', 'msg' => '专题名不能为空']);
            }
            $editParam = [
                'id' => $param['id'],
                'name' => $param['name'],
                'yes_no'=> $param['yes_no'],
                'type_id'=> $param['type_id'],
                'bg_image'=> $param['bg_image'],
                'top_image'=> $param['top_image'],
                'share_title'=> $param['share_title'],
                'share_des'=> $param['share_des'],
                'share_image'=> $param['share_image'],
            ];
        //echo json_encode(['code'=>1,'data'=>$editParam,'msg'=>'同类专题信息'],JSON_UNESCAPED_UNICODE);
            // 检测新修改的专题名，表中是否已经存在
            $ztModel = new ZtManage();
            $has = $ztModel->field('id')
                ->where("name = '" . $param['name'] . "' and id != " . $param['id'])
                ->find();
            if(!empty($has)){
                return json(['code' => -2, 'data' => '', 'msg' => '该专题名已经存在']);
            }
            //$flag = $ztModel->insert('');
            $flag = $ztModel->where('id', $param['id'])->update($editParam);
            if(false === $flag){
                return json(['code' => -7, 'data' => '', 'msg' => '系统错误']);
            }
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
        }
        $id = input('param.uid');
        $ztModel = new ZtManage();
        $ztInfo = $ztModel->where('id',$id)->find();
        return json(['code'=>1,'data'=>$ztInfo,'msg'=>' 专题信息']);
    }
    public function delZt()
    {
        $id = input('param.uid');
        $ztModel = new ZtManage();
        $flag = $ztModel->where('id',$id)->delete();
        if(false == $flag){
            return json(['code'=>-1,'data'=>'','msg'=>'系统错误']);
        }else{
            return json(['code'=>1,'data'=>'','msg'=>'删除成功']);
        }
    }
}