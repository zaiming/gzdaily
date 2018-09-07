<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 18-9-1
 * Time: 上午10:44
 */
namespace app\index\controller;
use think\Controller;

class Tools extends Controller
{
    ///Laypage 分页插件演示
    public  function laypage()
    {
        if(request()->isAjax()){

            //模拟八页的数据
            return json(['code'=>1,'page'=>8,'content'=>'模拟数据']);
        }
        return $this ->fetch();
    }
    //模板渲染接口
    public function template()
    {
        if(request()->isAjax()){
            $cards =[
                ['code'=>'A','name'=>'南京'],
                ['code'=>'B','name'=>'无锡'],
                ['code'=>'C','name'=>'徐州'],
                ['code'=>'D','name'=>'常州'],
                ['code'=>'E','name'=>'苏州'],
                ['code'=>'F','name'=>'南通'],
                ['code'=>'G','name'=>'连云港'],
                ['code'=>'H','name'=>'淮安'],
                ['code'=>'J','name'=>'盐城'],
                ['code'=>'K','name'=>'扬州'],
                ['code'=>'L','name'=>'镇江'],
                ['code'=>'M','name'=>'泰州'],
            ];
            return json(['code'=>1,'data'=>$cards,'msg'=>'江苏各市车牌号码']);
        }
        return $this->fetch();
    }
    //bootstrap-table
        public function bootstraptable()
        {
            if(request()->isAjax()){
                $param = input('param.');
                $where = '';
                if(!empty($param['name'])){
                    $where['name'] = $param['name'];
                }
                $limit = $param['pageSize'];
                $offset = ($param['pageNumber']-1)*$limit;
                //此处自己处理分页
                $selectResult = db('area')->where($where)->limit($offset,$limit)->select();
                foreach($selectResult as $key=>$vo){
                    $selectResult[$key]['operate'] = '<button class="btn btn-info" type="button">编辑</button>';
                }
                $return['total'] = db('area')->where($where)->count();//data
                $return['rows'] = $selectResult;
                return json($return);
            }
            return $this->fetch();

        }

}
