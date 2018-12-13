<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-12-7
 * Time: 下午3:00
 */

namespace app\face\controller;

use think\Controller;
use think\Request;
use think\File;
class Index extends Common
{
        public  function index(Request $request){
            $r = $request->param();
            print_r('hello 广州日报人！');

            return $this->fetch();
        }

    //php向服务器接口发送请求实例
        public function  https_request($url,$data){
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$url);
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
            if(!empty($data)){
                curl_setopt($curl,CURLOPT_POST,1);
                curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
            }
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
            //content-Type
            curl_setopt($curl,CURLOPT_HTTPHEADER,array(
                'Content-Type:application/json;charset=utf-8',
                'Content-Length:'.strlen($data)
            ));
            $output = curl_exec($curl);
            curl_close($curl);
            return $output;
        }
        public function getuser(){
            $post_data = array(
                'imageurl1' => 'http://gzdaily-face.oss-cn-shenzhen.aliyuncs.com/AB.jpg?OSSAccessKeyId=LTAIJRVwKrRilkFT&Expires=1544701638&Signature=iRCjoxl3A%2FmemnY%2FRKM10tssHnQ%3D',
                'imageurl2' => 'http://gzdaily-face.oss-cn-shenzhen.aliyuncs.com/girl.jpg?OSSAccessKeyId=LTAIJRVwKrRilkFT&Expires=1544701638&Signature=U2U3yLM8EmMAiHs2SZ2YDAGmeQI%3D'
            );
            $url = "http://127.0.0.1:8888/get_user";
            $json = json_encode($post_data);
            $res = $this->https_request($url,$json);
            $res = json_decode($res,true);
            //print_r($post_data."你好呀！");
            print_r($json);
            print_r($res);

            return $res;
        }
        public function aliyun(Request $request){

            return $this->fetch('aliyun');
        }

        public  function aliyunup(Request $request)
        {
            error_reporting(0);
            header("Content-type:text/html;charset=utf-8");
            if($this->request->isPost()){
//                $arrList1= $_FILES['image']['name'];
//                $arrList2= $_FILES['image']['tmp_name'];
                $arrList11= $_FILES['image1']['name'];
                $arrList12= $_FILES['image1']['tmp_name'];
                $arrList21= $_FILES['image2']['name'];
                $arrList22= $_FILES['image2']['tmp_name'];
                $filepath = '__STATIC__/static';
                if(move_uploaded_file($arrList12,$filepath.$arrList11)){
                    echo "本地上传成功";
                }else{
                    echo "本地上传失败";
                }
                $info2=array();
                for($i=0;$i<count($arrList11);$i++){
                    $object= $arrList11[$i];
                    $content=file_get_contents($arrList12[$i]);
                    $info=$this->moveOss('LTAIJRVwKrRilkFT','COxyl2mlFcDCB6OR6TQDBQvfXAGnkg',
                        'http://oss-cn-shenzhen.aliyuncs.com','gzdaily-face',$object,$content);
                    $arr2[]=$info;
                    echo $info;echo "<br/>";
                    echo " 模板图上传OSS success";echo "<br/>";
                }
                $result=implode(';',$arr2);
                $imageurl1 = $this->getUrl('LTAIJRVwKrRilkFT','COxyl2mlFcDCB6OR6TQDBQvfXAGnkg',
                    'http://oss-cn-shenzhen.aliyuncs.com','gzdaily-face',$object);
                echo $imageurl1;
                $info2=array();
                for($i=0;$i<count($arrList21);$i++){
                    $object= $arrList21[$i];
                    $content=file_get_contents($arrList22[$i]);
                    $info=$this->moveOss('LTAIJRVwKrRilkFT','COxyl2mlFcDCB6OR6TQDBQvfXAGnkg',
                        'http://oss-cn-shenzhen.aliyuncs.com','gzdaily-face',$object,$content);
                    $arr2[]=$info;
                    echo $info;echo "<br/>";
                    echo "头像图上传OSS success";echo "<br/>";
                }
                $result=implode(';',$arr2);
                $imageurl2 = $this->getUrl('LTAIJRVwKrRilkFT','COxyl2mlFcDCB6OR6TQDBQvfXAGnkg',
                    'http://oss-cn-shenzhen.aliyuncs.com','gzdaily-face',$object);
                echo $imageurl2;
                print_r($result);

            }else{
                return view();
            }

        }
        public function change(Request $request){

            return $this->fetch('change');
        }
        public function preview(Request $request){
            return $this->fetch('preview');
        }
        public function picture(Request $request){

            return $this->fetch('picture');
        }
        public function upload(Request $request){
            $r = $request->param();
//            date_default_timezone_set('PRC');  //获取中国时区，'PRC':中华人民共和国

            if(!file_exists(date("Ymd",time()))) //如果文件夹不存在，则创建一个
                mkdir(date("Ymd",time()));

//          $username = $_POST['name']; //获取索引
            $face = $r['face'];
            $back = $r['back'];

            $filesName = $_FILES['file']['name'];  //文件名数组
            $filesTmpNamew = $_FILES['file']['tmp_name'];  //临时文件名数组

            for($i= 0;$i<count($filesName);$i++)  // count():php获取数组长度的方法
            {
                if(file_exists(date("Ymd",time()).'/'.$filesName[$i])){
                    die($filesName[$i]."文件已存在");  //如果上传的文件已经存在
                }
                else{
                    move_uploaded_file($filesTmpNamew[$i], date("Ymd",time()).'/'.$filesName[$i]);  //保存在缓冲区的是临时文件名而不是文件名
                }
            }
//          $json_array = array('name'=>$username,'age'=>$age ,'sex'=>$sex,'file1'=>$filesName[0] ,'file2'=>$filesName[1]); //转换成数组类型
            $json_array = array('face'=>$face ,'back'=>$back,'file1'=>$filesName[0] ,'file2'=>$filesName[1]); //转换成数组类型

            $json= json_encode($json_array);  //将数组转换成json对象
            echo $json;
//            return $this->fetch('tu');
        }
        public function yulan(Request $request){
            $file = request()->file('f');
            $info = $file->move(ROOT_PATH .'public/uploads/avatar');
            $a = $info->getSaveName();
            $imgp = str_replace("\\",'/',$a);
            $imgpath = 'uploads/avatar/'.$imgp;
            $banner_img = $imgpath;
            $response = array();
            if($info){
                $response['isSuccess'] = true;
                $response['f'] = $imgpath;
            }else{
                $response['isSuccess'] = false;
            }
            echo json_encode($response);
            return $this->fetch('yulan');
        }
        public function uploadimg(Request $request){
            $pic = $request->param();
            //获取图片信息
            list($type,$file) = explode(',',$pic['course_img']);
            // 判断类型
            if(strstr($type,'public/jpeg')!=''){
                $ext = '.jpg';
            }elseif(strstr($type,'public/gif')!=''){
                $ext = '.gif';
            }elseif(strstr($type,'public/png')!=''){
                $ext = '.png';
            }
            // 生成的文件名
            $photo = md5(time()).$ext;
            $file_dir = 'uploads/images/'.Date('Ymd');
            //判断文件夹是否存在
            if (!is_dir($file_dir)) {
                mkdir($file_dir,755,true);
            }
            if(file_put_contents($file_dir.'/'.$photo,base64_decode($file), true)){
                $ret = ['img'=>$file_dir.'/'.$photo];
                //这里必须用echo返回json 用return jquery接收不到
                echo json_encode($ret);
            }
            return $this->fetch('tu');
            }
            public function image(Request $request){

                return $this->fetch('image');
            }
             public function yibu(Request $request){

                 return $this->fetch('yibu');
             }
            public function up(Request $request){
                header('content-type:text/html charset:utf-8');
                $dir_base = "http://imm.gzdaily.com/";     //文件上传根目录
                //没有成功上传文件，报错并退出。
                $output = "<textarea>";
                $index = 0;        //$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
                foreach($_FILES as $file){
                    $upload_file_name = 'upload_file' . $index;        //对应index.html FomData中的文件命名
                    $filename = $_FILES[$upload_file_name]['name'];
                    $gb_filename = iconv('utf-8','gb2312',$filename);    //名字转换成gb2312处理
                    //文件不存在才上传
                    if(!file_exists($dir_base.$gb_filename)) {
                        $isMoved = false;  //默认上传失败
                        $MAXIMUM_FILESIZE = 1 * 1024 * 1024;     //文件大小限制    1M = 1 * 1024 * 1024 B;
                        $rEFileTypes = "/^\.(jpg|jpeg|gif|png){1}$/i";
                        if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE &&
                            preg_match($rEFileTypes, strrchr($gb_filename, '.'))) {
                            $isMoved = @move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], $dir_base.$gb_filename);        //上传文件
                        }
                    }else{
                        $isMoved = true;    //已存在文件设置为上传成功
                    }
                    if($isMoved){
                        //输出图片文件<img>标签
                        //注：在一些系统src可能需要urlencode处理，发现图片无法显示，
                        //请尝试 urlencode($gb_filename) 或 urlencode($filename)，不行请查看HTML中显示的src并酌情解决。
                        $output .= "<img src='{$dir_base}{$filename}' title='{$filename}' alt='{$filename}'/>";
                    }else {
                        //上传失败则把error.jpg传回给前端
                        $output .= "<img src='{$dir_base}aa.jpg' title='{$filename}' alt='{$filename}'/>";
                    }
                    $index++;
                }
                echo $output."</textarea>";

            }


}