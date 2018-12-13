<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-9-29
 * Time: 下午4:51
 */
// Make sure you are using a correct path here.
namespace app\admin\controller;
use think\Controller;

class Icon extends Controller
{
        public function index(){
            echo 'jfodjfiajpojfoip';
        }

        public function ueditor(){
            date_default_timezone_set("PRC");
            error_reporting(E_ERROR);
            header("Content-Type: text/html; charset=utf-8");
            $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./ueditor/php/config.json")), true);
            $action = $_GET['action'];
            switch ($action) {
                case 'config':
                    $result =  json_encode($CONFIG);
                    break;

                /* 上传图片 */
                case 'uploadimage':
                    /* 上传涂鸦 */
                case 'uploadscrawl':
                    /* 上传视频 */
                case 'uploadvideo':
                    /* 上传文件 */
                case 'uploadfile':
                    $result = include("/login/public/static/ueditor/php/action_upload.php");
                    break;

                /* 列出图片 */
                case 'listimage':
                    $result = include("/login/public/static/ueditor/php/action_list.php");
                    break;
                /* 列出文件 */
                case 'listfile':
                    $result = include("/login/public/static/ueditor/php/action_list.php");
                    break;

                /* 抓取远程文件 */
                case 'catchimage':
                    $result = include("/login/public/static/ueditor/php/action_crawler.php");
                    break;

                default:
                    $result = json_encode(array(
                        'state'=> '请求地址出错'
                    ));
                    break;
            }

            /* 输出结果 */
            if (isset($_GET["callback"])) {
                if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                    echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
                } else {
                    echo json_encode(array(
                        'state'=> 'callback参数不合法'
                    ));
                }
            } else {
                $row = json_decode($result,true);
                if($row['state'] == 'SUCCESS'){
                    vendor('aliyun.autoload');
                    $accessKeyId = "LTAIJRVwKrRilkFT";//去阿里云后台获取秘钥
                    $accessKeySecret = "COxyl2mlFcDCB6OR6TQDBQvfXAGnkg";//去阿里云后台获取秘钥
                    $endpoint = "https://gzdaily-public.oss-cn-shenzhen.aliyuncs.com";//你的阿里云OSS地址
                    $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
                    $bucket= "";//oss中的文件上传空间
                    $object = 'images/' . $row['title'];//想要保存文件的名称
                    $file = './images/' . $row['title'];//文件路径，必须是本地的。
                    try{
                        $ossClient->uploadFile($bucket,$object,$file);
                        //上传后不删除本地文件,请删除或注释这一行
                        unlink($file);
                    } catch(OssException $e) {
                        return;
                    }
                }
                echo $result;
            }
        }
       public function add($content){
        $file = time() . rand(100000,999999) . '.txt';
        $text = fopen("./text/{$file}","w");
        fwrite($text,$content);
        vendor('aliyun.autoload');
        $accessKeyId = "LTAIJRVwKrRilkFT";//去阿里云后台获取秘钥
        $accessKeySecret = "COxyl2mlFcDCB6OR6TQDBQvfXAGnkg";//去阿里云后台获取秘钥
        $endpoint = "https://gzdaily-public.oss-cn-shenzhen.aliyuncs.com";//你的阿里云OSS地址
        $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $bucket= "";//oss中的文件上传空间
        $object = 'text/' . $file;//想要保存文件的名称
        $files = './text/' . $file;//文件路径，必须是本地的。
        try{
            $ossClient->uploadFile($bucket,$object,$files);
            //如果不删除本地文件 删除或注释这一行
            unlink($files);
            //存入数据库或其他操作
        } catch(OssException $e) {
            //上传失败，自己编码
            return;
        }
    }

}

