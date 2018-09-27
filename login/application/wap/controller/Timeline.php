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
    //前端展示
    public function frontshow()
    {
        $id = input('param.id');
        $sql = "SELECT m.*,s.* FROM zt_manage m,zt_slide s where $id  = m.id AND $id = s.id";
        $res = Db::query($sql);
        $this->assign("SLIDE1",$res[0]['slide1']);
        $this->assign("SLIDE2",$res[0]['slide2']);
        $this->assign("SLIDE3",$res[0]['slide3']);
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