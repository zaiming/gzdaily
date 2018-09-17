<?php
/**
 * Created by PhpStorm.
 * User: wenfei
 * Date: 2018/6/25
 * Time: 10:37
 */


namespace app\admin\Controller;

use think\Controller;
use think\Db;
use think\Request;



class Timeline extends Controller
{

    public function index()
    {
        echo "HH;";

    }
    //show
    public function show()
    {

         $id = input('param.id');
//       echo json_encode(['code'=>1,'data'=>$id],JSON_UNESCAPED_UNICODE);
         $api_url = "/login/public/index.php/admin/Timeline";
         $this->assign("API_URL",$api_url);
         $this->assign('id',$id);
         return $this->fetch('index');
    }

    public function load()
    {
        $id = input('param.id');
        $sql = "SELECT n.* FROM zt_wc_newslist n where $id  = n.zt_id ORDER by iorder DESC ,id DESC ";
        $res = Db::query($sql);
        foreach ($res as $key=>$val) {
            if (!empty($res[$key]["link_ids"])) {
                $sql1 = "SELECT * FROM zt_wc_link WHERE lid IN ( " . $res[$key]["link_ids"] . ") AND status=1";
                $temp = Db::query($sql1);
                $str = "";
                foreach ($temp as $k=> $v) {
                    $str = $str . ($k+1) ."|" .  $v["title"] . "<br>\n\r";
                }
                $res[$key]["link_ids"] = $str;
            }
        }
        return json($res);
    }

    public function edit(Request $request)
    {
        $r = $request->param();
        $data["id"] = $r["id"];
        $data["date"] = $r["date"];
        $data["title"] = $r["title"];
        $data["sub_title"] = $r["sub_title"];
        $data["status"] = $r["status"];
        $data["iorder"] = $r["iorder"];
        //增加专题ID
        $data["zt_id"] = $r["id"];
        $res = Db::table('zt_wc_newslist')->update($data);
        return json($res);
    }

    public function showadd()
    {
        $id = input('param.id');
        $post_url = "/login/public/index.php/admin/Timeline/add/id/$id";
        $this->assign("POST_URL",$post_url);
        $this->assign('id',$id);
        return $this->fetch('add');

    }
    public function add(Request $request)
    {
        $id = input('param.id');
        $r = $request->param();
        $data['title'] = $r['title'];
        $data['sub_title'] = $r['sub_title'];
        $data['date'] = $r['date'];
        $data['status'] = $r['status'];
        $data['iorder'] = $r['iorder'];
        //专题ID
        $data['zt_id'] = $id;
        $res = Db::table('zt_wc_newslist')->insert($data);
        if ($res >= 1) {
            $this->success("新增成功",'/login/public/index.php/admin/Timeline/show/id/'.$id);
        }else {
            $this->error('新增失败');
        }
    }

    public function linkload(Request $request)
    {
        $r = $request->param();
        $nid = $r["nid"];
        $linkIds = Db::table('zt_wc_newslist')->where('id',$nid)->field('link_ids')->find();
        if (empty($linkIds["link_ids"])) {
            return json([]);
        }else {
            $res = Db::query("SELECT * FROM zt_wc_link WHERE lid IN ("  . $linkIds["link_ids"] . ")");
            return json($res);
        }
    }

    public function showlink(Request $request)
    {
        $r = $request->param();
        $nid = $r["nid"];
        //专题ID
        $id = input('param.id');
        $api_url = "/login/public/index.php/admin//Timeline";
        $this->assign("API_URL",$api_url);
        $this->assign('Nid',$nid);
        $this->assign('id',$id);
        return $this->fetch('showlink');
    }

    public function showaddlink(Request $request)
    {

        $r = $request->param();
        //专题ID
        $id = input('param.id');
        $nid = $r["nid"];
        $api_url = "/login/public/index.php/admin/Timeline";
        $this->assign("POST_URL",$api_url . "/addlink/id/".$id."/nid/" . $nid);
        $this->assign('Nid',$nid);
        $this->assign('id',$id);
        return $this->fetch('addlink');

    }

    public function addlink(Request $request)
    {
        $r = $request->param();
        $nid = $r['nid'];
        //专题ID
        $id = input('param.id');
        $data['title'] = $r['title'];
        $data['link'] = $r['link'];
        $data['type'] = $r['type'];
        $data['status'] = $r['status'];
        $lastInsId = Db::table('zt_wc_link')->insert($data,false,true);
        if ($lastInsId > 0) {
            $linkIds = Db::table('zt_wc_newslist')->where('id',$nid)->field('link_ids')->find();
            $ids="";
            if (empty($linkIds["link_ids"])) {
                $ids = $lastInsId;
            }else {
               $ids = $linkIds["link_ids"] . "," . $lastInsId;
            }
            Db::table('zt_wc_newslist')->where('id',$nid)->update(array("link_ids"=>$ids));
            $this->success("新增成功",'/login/public/index.php/admin/Timeline/show/id/'.$id);
        }else {
            $this->error('新增失败');
        }
    }

    public function linkedit(Request $request)
    {
        $r = $request->param();
        $res = Db::table('wc_link')->update($r);
        return json($res);
    }


//ajax data
    public function newslist()
    {
        //跨域调用
        //专题ID
        $id = input('param.id');
        header("access-control-allow-origin:*");
        $sql = "SELECT * FROM zt_wc_newslist WHERE status=1 and zt_id = $id ORDER by date";
        $res = Db::query($sql);
        foreach ($res as $key=>$val) {
            if ($val["link_ids"] != "" ) {
                $sql1 = "SELECT * FROM zt_wc_link WHERE lid IN (" . $val["link_ids"] . ") AND status=1 ";
                $link_title_list = Db::query($sql1);
                $res[$key]["link_title_list"] = $link_title_list;
            }else {
                $res[$key]["link_title_list"] = [];
            }
        }
        $returnArray = [];
        foreach ($res as $key=>$val) {
            if (!key_exists($val["date"], $returnArray)){
                $returnArray[$val["date"]] = [];
            }
            array_push($returnArray[$val["date"]],$res[$key]);
        }

        return json($returnArray);
    }

}