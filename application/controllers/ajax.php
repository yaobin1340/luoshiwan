<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/5/1
 * Time: 11:47
 */
class Ajax extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ajax_model');
    }

   function get_product($flag,$page=2,$type=-1,$limit=6){
       $data=$this->ajax_model->get_product($flag,$page,$type,$limit);
       echo json_encode($data);
   }

    //ajax获取图片信息
    public function get_pics($time){
        $this->load->helper('directory');
        $path = './././uploadfiles/pics/'.$time;
        $map =directory_map($path);
        $data = array();
        //整理图片名字，取缩略图片
        foreach($map as $v){
            if(substr(substr($v,0,strrpos($v,'.')),-5) == 'thumb'){
                $data['img'][] = $v;
            }
        }
        $data['time'] = $time;//文件夹名称
        echo json_encode($data);
    }

}