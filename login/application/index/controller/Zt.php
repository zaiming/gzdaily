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
use think\Db;
use think\Controller;
use think\Validate;


class Zt extends Controller
{
    public function upload(){
        //获取表单上传文件
        $file = request()->file('image');
        //移动到框架应用根目录public/uploads
        if($file){
            $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
            if($info){
                //成功上传后获取上传信息
                //输出jpg
                echo $info->getExtension();
                echo $info->getSaveName();
                echo $info->getFilename();
            }else{
                //上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    public function laypage()
    {
//        $sql_str = "SELECT m.*, t.type_name  from zt_type t ,zt_manage m where t.id = m.type_id";
//        $ztList = Db::query($sql_str)->paginate(10);
          $ztList = Db::name('zt_type')->alias(['zt_type'=>'t','zt_manage'=>'m'])->join('zt_manage','t.id=m.type_id')->paginate(10);

        $this->assign('zt_list',$ztList);
        return $this->fetch();
    }
    // 展示列表
    public function index()
    {
        $ztModel = new ZtManage();
     // $zttypeModel = new ZtType();
        //// dump($userModel);
//      $ztList = $ztModel->select();
        $sql_str = "SELECT m.*, t.type_name  from zt_type t ,zt_manage m where t.id = m.type_id";
        $ztList = Db::query($sql_str);

        $this->assign('zt_list',$ztList);

        if(request()->isAjax()){

        }
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


//    //bootstrap-table page
//    public function bootstraptable()
//    {
//
//        $sql_str = "SELECT m.*, t.type_name  from zt_type t ,zt_manage m where t.id = m.type_id";
//        $ztList = Db::query($sql_str);
//
//        $this->assign('zt_list',$ztList);
//
////        echo json_encode(['code'=>1,'data'=>$ztList],JSON_UNESCAPED_UNICODE);
//
//        if(request()->isAjax()){
//
//            $param = input('param.');
//            $where = '';
//            if(!empty($param['name'])){
//                $where['name'] = $param['name'];
//            }
//            $limit = $param['pageSize'];
//            $offset = ($param['pageNumber']-1)*$limit;
//            //此处自己处理分页
//            $selectResult = db('zt_manage')->where($where)->limit($offset,$limit)->select();
//            foreach($selectResult as $key=>$vo){
////                $selectResult[$key]['operate'] = '<button class="btn btn-info" type="button">编辑</button>';
//                $selectResult[$key]['operate'] = '';
//            }
//            $return['total'] = db('zt_manage')->where($where)->count();//data
//            $return['rows'] = $selectResult;
//            return json($return);
//        }
//        return $this->fetch();
//
//    }
}