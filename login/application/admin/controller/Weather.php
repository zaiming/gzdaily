<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-11-1
 * Time: 上午10:44
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Weather extends Controller{
    public function index(){
        echo 'nihao';
    }
    public function list(){
        $sql2018 = "SELECT ww.* FROM dn_gz_weather2018 ww ORDER by ww.id DESC";
        $res2018 = Db::query($sql2018);
        $sql2017 = "SELECT w.* FROM dn_gz_weather w ORDER by w.id DESC";
        $res2017 = Db::query($sql2017);
        $res = $res2018+$res2017;
        return json($res);
    }
}
