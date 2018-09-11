<?php
/**
 * Created by PhpStorm.
 * User: wenfei
 * Date: 2018/6/25
 * Time: 10:37
 */


namespace app\wap\Controller;

use think\Controller;
use think\Db;
use think\Request;



class Timeline extends Controller
{

    public function index()
    {
        echo "HH;";

    }
    //qianduan
    public function frontshow()
    {
        $id = input('param.id');
        $sql = "SELECT m.* FROM zt_manage m where $id  = m.id ";
        $res = Db::query($sql);
        $this->assign("BG_IMAGE",$res[0]['bg_image']);
        $this->assign("TOP_IMAGE",$res[0]['top_image']);
        $this->assign("SHARE_TITLE",$res[0]['share_title']);
        $this->assign("SHARE_DES",$res[0]['share_title']);
        $this->assign("SHARE_IMGAE",$res[0]['share_image']);
        $this->assign('id',$id);
        return $this->fetch('frontshow');

//        echo json_encode(['code'=>1,'data'=>$res[0]['bg_image']],JSON_UNESCAPED_UNICODE);
//        echo json_encode(['code'=>1,'data'=>$res[0]],JSON_UNESCAPED_UNICODE);
    }


}