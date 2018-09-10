<?php

namespace app\gzck\Controller;

use think\Controller;
use think\Db;
use think\Request;

class Yayun2018 extends Controller
{

    public function index()
    {
        //$nation = "游泳、射箭、田径、羽毛球、棒垒球、篮球、保龄球、拳击、桥牌、皮划艇、自行车、马术、击剑、足球、高尔夫、体操、手球、曲棍球、水上摩托、柔道、卡巴迪、空手道、现代五项、滑翔伞、赛艇、7人制橄榄球、帆船、藤球、滑板、攀岩、壁球、乒乓球、跆拳道、网球、铁人三项、排球、举重、摔跤、武术、电竞";
        //$a = explode("、", $nation);
        //foreach ($a as $v ) {
        //    $data = array(
        //        "name" => $v
        //    );
        //    $res = Db::table("yy_item")->insert($data);
        //    echo $res;
        //}
    }
    public function show()
    {
        $api_url = ".";
        $this->assign("API_URL",$api_url);
        return $this->fetch('index');
    }

    public function load()
    {
        $sql = "SELECT * FROM zt_all_newslist WHERE a_id=1 ORDER by iorder DESC ,id DESC ";
        $res = Db::query($sql);
        foreach ($res as $key=>$val) {
            if (!empty($res[$key]["link_ids"])) {
                $sql1 = "SELECT * FROM zt_all_link WHERE lid IN ( " . $res[$key]["link_ids"] . ") AND status=1";
                $temp = Db::query($sql1);

                $str = "";
                foreach ($temp as $k=> $v) {
                    $str = $str . ($k+1) ."|" .  $v["title"] . "<br>\n\r";
                }
                $res[$key]["link_ids"] = $str;
            }

            if (!empty($res[$key]["map_a"])) {
                $sql1 = "SELECT * FROM zt_yy_nation WHERE id IN ( " . trim($res[$key]["map_a"],",") . ") AND status=1";
                $temp = Db::query($sql1);

                $str = "";
                foreach ($temp as $k=> $v) {
                    $str = $str . ($k+1) ."|" .  $v["name"] . "<br>\n\r";
                }
                $res[$key]["map_a"] = $str;
            }

            if (!empty($res[$key]["map_b"])) {
                $sql1 = "SELECT * FROM zt_yy_item WHERE id IN ( " .trim($res[$key]["map_b"],",") . ") AND status=1";
                $temp = Db::query($sql1);

                $str = "";
                foreach ($temp as $k=> $v) {
                    $str = $str . ($k+1) ."|" .  $v["name"] . "<br>\n\r";
                }
                $res[$key]["map_b"] = $str;
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
        $res = Db::table('zt_all_newslist')->update($data);
        return json($res);
    }

    public function showadd()
    {
        $post_url = "./add";
        $this->assign("POST_URL",$post_url);

        $map_a = Db::table('zt_yy_nation')->select();
        $map_b = Db::table('zt_yy_item') ->select();
        $this->assign("map_a", $map_a);
        $this->assign("map_b", $map_b);

        return $this->fetch('add');

    }
    public function add(Request $request)
    {
        $r = $request->param();
        //活动id
        $data['a_id'] = 1;
        $data['title'] = $r['title'];
        $data['sub_title'] = $r['sub_title'];
        $data['date'] = $r['date'];
        $data['status'] = $r['status'];
        $data['iorder'] = $r['iorder'];
        $data['map_a'] = "," . trim(implode(",",$r['map_a']),",") . ",";
        $data['map_b'] ="," .  trim(implode(",",$r['map_b']),",") . ",";
        $res = Db::table('zt_all_newslist')->insert($data);
        if ($res >= 1) {
            $this->success("新增成功",'Yayun2018/show');
        }else {
            $this->error('新增失败');
        }
    }

    public function linkload(Request $request)
    {
        $r = $request->param();
        $nid = $r["nid"];
        $linkIds = Db::table('zt_all_newslist')->where('id',$nid)->field('link_ids')->find();
        if (empty($linkIds["link_ids"])) {
            return json([]);
        }else {
            $res = Db::query("SELECT * FROM zt_all_link WHERE lid IN ("  . $linkIds["link_ids"] . ")");
            return json($res);
        }
    }

    public function showlink(Request $request)
    {
        $r = $request->param();
        $nid = $r["nid"];

        $api_url = "http://localhost/gzck/public/index.php/gzck/Yayun2018";
        $this->assign("API_URL",$api_url);
        $this->assign('Nid',$nid);
        return $this->fetch('showlink');
    }

    public function showaddlink(Request $request)
    {
        $r = $request->param();
        $nid = $r["nid"];
        $api_url = "http://localhost/gzck/public/index.php/gzck/Yayun2018";
        $this->assign("POST_URL",$api_url . "/addlink/nid/" . $nid);
        $this->assign('Nid',$nid);
        return $this->fetch('addlink');

    }

    public function addlink(Request $request)
    {
        $r = $request->param();
        $nid = $r['nid'];

        $data['title'] = $r['title'];
        $data['link'] = $r['link'];
        $data['type'] = $r['type'];
        $data['status'] = $r['status'];
        $lastInsId = Db::table('zt_all_link')->insert($data,false,true);
        if ($lastInsId > 0) {
            $linkIds = Db::table('zt_all_newslist')->where('id',$nid)->field('link_ids')->find();
            $ids="";
            if (empty($linkIds["link_ids"])) {
                $ids = $lastInsId;
            }else {
               $ids = $linkIds["link_ids"] . "," . $lastInsId;
            }
            Db::table('zt_all_newslist')->where('id',$nid)->update(array("link_ids"=>$ids));
            $this->success("新增成功",'Yayun2018/show');
        }else {
            $this->error('新增失败');
        }
    }

    public function linkedit(Request $request)
    {
        $r = $request->param();
        $res = Db::table('zt_all_link')->update($r);
        return json($res);
    }

    //public function initStatistic()
    //{
    //    $nation = Db::query("SELECT * FROM yy_nation");
    //    $item = Db::query("SELECT * FROM yy_item where id > 40");
    //    foreach ($nation as $v) {
    //        foreach ($item as $i){
    //            $sql = "INSERT INTO yy_statistic (n_id,i_id) VALUES (" . $v["id"] . "," . $i["id"] . " )";
    //            Db::execute($sql);
    //        }
    //    }
    //    echo "OK";
    //}

    //public function xiufu()
    //{
    //    $newlist = Db::query("select * from all_newslist");
    //    foreach ($newlist as $v) {
    //        $data["map_a"] = ",".  $v["map_a"] . ",";
    //        $data["map_b"] = ",".  $v["map_b"] . ",";
    //        $data["id"] = $v["id"];
    //        $res = Db::table("all_newslist")->update($data);
    //        var_dump($res);
    //    }
    //}

    public function changeMap(Request $request)
    {
        $id = $request->param("id");
        $nation = Db::query("SELECT id,name FROM zt_yy_nation WHERE status=1");
        $item = Db::query("SELECT id, name FROM zt_yy_item WHERE status=1");
        $new = Db::query("SELECT * FROM zt_all_newslist WHERE id=" . $id);
        $map_a = explode(",",trim($new[0]["map_a"],","));
        $map_b = explode(",",trim($new[0]["map_b"],","));
        foreach ($nation as $key =>$v) {
            $nation[$key]["checked"] = false;
            foreach ($map_a as $ma) {
                if ($v["id"] == $ma) {
                    $nation[$key]["checked"] = true;
                }
            }
        }
        foreach ($item as $k =>$v) {
            $item[$k]["checked"] = false;
            foreach ($map_b as $ma) {
                if ($v["id"] == $ma) {
                    $item[$k]["checked"] = true;
                }
            }
        }
        $this->assign("nation_list", $nation);
        $this->assign("item_list", $item);
        $this->assign("new", $new[0]);
        return $this->fetch("changemap");
    }

    public function change(Request $request)
    {
        $p = $request->param();
        $data["id"] = $p["newid"] ;
        $data["map_a"] = "," . implode(",",$p["map_a"]) . ",";
        $data["map_b"] =",".  implode(",",$p["map_b"]) . ",";
        $res = Db::table("zt_all_newslist")->update($data);
        if($res){
            $this->success("修改成功", "http://localhost/gzck/public/index.php/gzck/Yayun2018/show");
        }else{
            $this->error($res);
        }
    }

    public function prizeShow(Request $request)
    {
        $api_url = "";
        $this->assign("API_URL",$api_url);

        $nid = intval($request->param("nid"));
        $iid = intval($request->param("iid"));
        if($nid > 0) {
            $loadUrl = $api_url . "/prizeLoad/nid/"  . $nid;
        }elseif($iid > 0) {
            $loadUrl = $api_url . "/prizeLoad/iid/"  . $iid;
        }else {
            $loadUrl = $api_url . "/prizeLoad/nid/"  . $nid;
        }
        $this->assign("LOAD_URL", $loadUrl);

        $nation = Db::query("SELECT id,name FROM zt_yy_nation WHERE status=1");
        $this->assign("NATION", json_encode($nation));

        $nation = Db::query("SELECT id,name FROM zt_yy_item WHERE status=1");
        $this->assign("ITEM", json_encode($nation));
        return $this->fetch('prizeShow');
    }

    public function prizeLoad(Request $request)
    {
        $nid = intval($request->param("nid"));
        $iid = intval($request->param("iid"));
        if ($nid > 0 ) {
            $sql = "SELECT s.*,n.name as n_name,i.name as i_name FROM zt_yy_statistic as s LEFT JOIN zt_yy_nation as n ON s.n_id=n.id LEFT JOIN zt_yy_item as i ON s.i_id=i.id WHERE s.n_id=" . $nid;
        }elseif($iid > 0) {
            $sql = "SELECT s.*,n.name as n_name,i.name as i_name FROM zt_yy_statistic as s LEFT JOIN zt_yy_nation as n ON s.n_id=n.id LEFT JOIN zt_yy_item as i ON s.i_id=i.id WHERE s.i_id=" . $iid;
        }else{
            $sql = "SELECT s.*,n.name as n_name,i.name as i_name FROM zt_yy_statistic as s LEFT JOIN zt_yy_nation as n ON s.n_id=n.id LEFT JOIN zt_yy_item as i ON s.i_id=i.id WHERE s.n_id=1";
        }
        $res = Db::query($sql);
        return json($res);
    }

    public function prizeEdit(Request $request)
    {
        $r = $request->param();
        $data["id"] = $r["id"];
        $data["n_id"] = $r["n_id"];
        $data["i_id"] = $r["i_id"];
        $data["gold"] = $r["gold"];
        $data["silver"] = $r["silver"];
        $data["copper"] = $r["copper"];
        $data["utime"] = date("Y-m-d H:i:s", time());

        $this->log($data);

        $res = Db::table('zt_yy_statistic')->update($data);
        return json($res);
    }

    private function log($data)
    {
        $sql = "SELECT s.*,n.name as n_name,i.name as i_name FROM zt_yy_statistic AS s LEFT JOIN zt_yy_nation as n ON s.n_id=n.id LEFT JOIN zt_yy_item as i ON s.i_id=i.id WHERE s.id=" . $data["id"];
        $src = Db::query($sql);
        $srcArray = $src[0];
        $msg = "id 为" . $srcArray["id"] . " " . $srcArray["n_name"] . " " . $srcArray["i_name"] . " ";
        if($data["gold"] != $srcArray["gold"]){
            $msg =$msg .  "由金牌" . $srcArray["gold"] . " 改为 " . $data["gold"];
        }elseif($data["silver"] != $srcArray["silver"]){
            $msg =$msg .  "由银牌" . $srcArray["silver"] . " 改为 " . $data["silver"];
        }elseif($data["copper"] != $srcArray["copper"]){
            $msg =$msg .  "由铜牌" . $srcArray["silver"] . " 改为 " . $data["silver"];
        }
        $idata["msg"] = $msg;
        Db::table("yy_log")->insert($idata);
    }

    public function showlog()
    {
        $data = Db::query("SELECT * FROM zt_yy_log ORDER BY id DESC limit 200");
        $this->assign("data", $data);
        return $this->fetch("log");

    }



}