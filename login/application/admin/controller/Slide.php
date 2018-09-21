<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-9-20
 * Time: 下午2:00
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;

class Slide extends Controller
{
    public function index(){
        echo "fdfdfajl;j;ljklfdsfdfdf";
    }
    public function show()
    {
        $id = input('param.id');
        $api_url = "/login/public/index.php/admin/Slide";
        $this->assign('API_URL',$api_url);
        $this->assign('id',$id);
        return $this->fetch('index');

    }
    public function load()
    {
        $id = input('param.id');
        $sql = "SELECT n.* FROM zt_slide_newslist n where $id = n.zt_id ORDER by iorder DESC ,id DESC" ;
        $res = Db::query($sql);
        foreach ( $res as $key=>$val) {
            if(!empty($res[$key]["link_ids"])){
                $sql1 = "SELECT * FROM zt_slide_link WHERE id IN (".$res[$key]["link_ids"].") AND status = 1";
                $temp = Db::query($sql1);
                $str = "";
                foreach ($temp as $k=>$v){
                    $str = $str.($k+1)."|".$v["title"]."<br>\n\r";
                }
                $res[$key]["link_ids"] = $str;
            }
        }
        return json($res);
    }
    public  function edit(Request $request)
    {
        $r = $request->param();
        $data["id"] =  $r['id'];
        $data["date"] = $r['date'];
        $data["title"] = $r['title'];
        $data["sub_title"] = $r['sub_title'];
        $data["image_url"] = $r['image_url'];
        $data["status"] = $r['status'];
        $data["iorder"] = $r['iorder'];
        //专题id
        $data["zt_id"] = $r['tid'];
        $res = Db::table('zt_slide_newslist')->update($data);
        return json($res);
    }
    public function showadd()
    {
        $id = input('param.id');
        $post_url = "/login/public/index.php/admin/Slide/add/id/$id";
        $this->assign("POST_URL",$post_url);
        $this->assign("id",$id);
        return $this->fetch('add');
    }
    public function add(Request $request)
    {
        $id = input('param.id');
        $r = $request->param();
        $data['title'] = $r['title'];
        $data['sub_title'] = $r['sub_title'];
        $data['image_url'] = $r['image_url'];
        $data['date'] = $r['date'];
        $data['status'] = $r['status'];
        $data['iorder'] =$r['iorder'];
        //专题ID
        $data['zt_id'] = $id;
        $res = Db::table('zt_slide_newslist')->insert($data);
        if(1 >= $res){
            $this->success("新增成功",'/login/public/index.php/admin/slide/show/id/'.$id);
        }else{
            $this->error('新增失败');
        }

    }
    public function linkload(Request $request)
    {
        $r = $request->param();
        $nid = $r['nid'];
        $linkIds = Db::table('zt_slide_newslist')->where('id',$nid)->field('link_ids')->find();
        if(empty($linkIds["link_ids"])){
            return json([]);
        }else{
            $res = Db::query("SELECT * FROM zt_slide_link WHERE id IN(".$linkIds["link_ids"].")");
            return json($res);
        }
    }
    public  function showlink(Request $request)
    {
        $r = $request->param();
        $id = input('param.id');
        $nid = $r['nid'];
        $api_url = "/login/public/index.php/admin/slide/";
        $this->assign("API_URL",$api_url);
        $this->assign('id',$id);
        $this->assign('nid',$nid);
        return $this->fetch('showlink');
    }

}