<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-12-13
 * Time: 上午9:55
 */

namespace app\face\controller;


if (is_file(__DIR__ . '/../aliyuncs/autoload.php')) {
    require_once __DIR__ . '/../aliyuncs/autoload.php';
}
if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
use think\Controller;
use think\Config;
use OSS\OssClient;
use Oss\Core\OssException;
class Common extends Controller
{
    public function moveOss($accessKeyId,$accessKeySecret,$endpoint,$bucket,$object,$content)
    {
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $res= $ossClient->putObject($bucket, $object, $content);

        } catch (OssException $e) {
            print $e->getMessage();
        }
        return $res['info']['url'];
    }
    public function getUrl($accessKeyId,$accessKeySecret,$endpoint,$bucket,$object){
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $signedUrl = $ossClient->signUrl($bucket,$object,600);
        return $signedUrl;
    }
}