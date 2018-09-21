<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-9-12
 * Time: 下午12:16
 */
namespace app\wap\Controller;

use think\Controller;
use think\Db;
use think\Request;

class Sports extends Controller
{
    public function index()
    {
        echo "HH;";

    }

    public function frontshow()
    {

        $tid = input('param.tid');
        $sql = "SELECT m.* FROM zt_manage m where $tid  = m.id ";
        $res = Db::query($sql);
        $this->assign("BG_IMAGE",$res[0]['bg_image']);
        $this->assign("TOP_IMAGE",$res[0]['top_image']);
        $this->assign("SHARE_TITLE",$res[0]['share_title']);
        $this->assign("SHARE_DES",$res[0]['share_title']);
        $this->assign("SHARE_IMGAE",$res[0]['share_image']);
        $this->assign('tid',$tid);
        return $this->fetch('frontshow');

//        echo json_encode(['code'=>1,'data'=>$res[0]['bg_image']],JSON_UNESCAPED_UNICODE);
    }
}
