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
        $sql = "SELECT * FROM zt_slide_newslist";
        $res = Db::query($sql);
        $this->assign('imageurl',$res[5]['image_url']);
      //  echo json_encode(['code'=>1,'data'=>$res[5]['image_url']],JSON_UNESCAPED_UNICODE);
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
                $sql1 = "SELECT l.* FROM zt_slide_link l WHERE l.id IN (" . $res[$key]["link_ids"].")   AND status = 1";
                $temp = Db::query($sql1);
                $str = "";
                foreach ($temp as $k=>$v){
                    $str = $str.($k+1). "|" .$v["title"] . "<br>\n\r";
                }
                $res[$key]["link_ids"] = $str;
            }
        }

//        foreach ( $res as $key=>$val) {
//            if(!empty($res[$key]["image_ids"])){
//                $sql2 = "SELECT i.* FROM zt_slide_image i WHERE i.id IN (" . $res[$key]["image_ids"].")   AND i.status = 1";
//                $temp = Db::query($sql2);
//                $str = "";
//                foreach ($temp as $k=>$v){
//                    $str = $str.($k+1). "|" .$v["title"] . "<br>\n\r";
//                }
//                $res[$key]["image_ids"] = $str;
//            }
//        }
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
    // ueditor 编辑器
    public function ueditor(Request $request)
    {

        if(request()->isPost()){
            $content = input('post.content');
            dump($content);
            die;
        }


       // return $this->fetch();
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
    public function imageload(Request $request)
    {
        $r = $request->param();
        $nid = $r['nid'];
        $imageIds = Db::table('zt_slide_newslist')->where('id',$nid)->field('image_ids')->find();
        if(empty($imageIds["image_ids"])){
            return json([]);
        }else{
            $res = Db::query("SELECT * FROM zt_slide_image WHERE id IN(".$imageIds["image_ids"].")");
            return json($res);
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
    public  function showimage(Request $request)
    {
        $r = $request->param();
        $id = input('param.id');
        $nid = $r['nid'];
        $api_url = "/login/public/index.php/admin/slide/";
        $this->assign("API_URL",$api_url);
        $this->assign('id',$id);
        $this->assign('nid',$nid);
        return $this->fetch('showimage');
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
    public function showaddimage(Request $request)
    {
        $r = $request->param();
        //专题id
        $id = input('param.id');
        $nid = $r["nid"];
        $api_url = "/login/public/index.php/admin/slide/";
        $this->assign("POST_URL",$api_url."/add/id/".$id."/nid/".$nid);
        $this->assign('nid',$nid);
        $this->assign("id",$id);
        return $this->fetch('addimage');
    }
    public function showaddlink(Request $request)
    {
        $r = $request->param();
        //专题id
        $id = input('param.id');
        $nid = $r["nid"];
        $api_url = "/login/public/index.php/admin/slide/";
        $this->assign("POST_URL",$api_url."/addlink/id/".$id."/nid/".$nid);
        $this->assign('nid',$nid);
        $this->assign("id",$id);
        return $this->fetch('addlink');
    }
    public function addlink(Request $request)
    {
        $r = $request->param();
        //专题ID
        $id = input('param.id');
        $nid = $r['nid'];
        $data['title'] = $r['title'];
        $data['link'] = $r['link'];
        $data['type'] = $r['type'];
        $data['status'] = $r['status'];
       //ueditor upload image path
        $content = input('post.content');
        dump($content);
        $data['image_link'] = $r['content'];

        $lastInsId = Db::table('zt_slide_link')->insert($data,false,true);

        if($lastInsId > 0){
            $linkIds = Db::table('zt_slide_newslist')->where('id',$nid)->field('link_ids')->find();
            $ids = "";
            if(empty($linkIds["link_ids"])){
                $ids = $lastInsId;
            }
            else{
                $ids = $linkIds['link_ids'].",".$lastInsId;

            }
            Db::table('zt_slide_newslist')->where('id',$nid)->update(array("link_ids"=>$ids));

            $this->success("新增成功",'/login/public/index.php/admin/slide/show/id/'.$id);

        }else{
            $this->error('新增失败');
        }

    }
    public function imageedit(Request $request)
    {
        $r = $request->param();
        $res = Db::table('zt_slide_image')->update($r);
        return json($res);
    }
    public function linkedit(Request $request)
    {
        $r = $request->param();
        $res = Db::table('zt_slide_link')->update($r);
        return json($res);
    }
    public function newslist()
    {
        //跨域调用
        //专题ID
        $id = input('param.id');
        header("access-control-allow-origin:*");
        $sql = "SELECT * FROM zt_slide_newslist WHERE status=1 and zt_id =$id ORDER by date";
        $res = Db::query($sql);
        foreach ($res as $key=>$val){
            if($val["link_ids"] !=""){
                $sql1 = "SELECT * FROM zt_slide_link WHERE id IN(".$val["link_ids"].") AND status=1 ";
                $link_title_list = Db::query($sql1);
                $res[$key]["link_title_list"] = $link_title_list;
            }else{
                $res[$key]["link_title_list"] = [];
            }
        }
        $returnArray = [];
        foreach ($res as $key=>$val){
            if(!key_exists($val["date"],$returnArray)){
                $returnArray[$val["date"]] = [];
            }
            array_push($returnArray[$val["date"]],$res[$key]);
        }
        return json($returnArray);
    }


}