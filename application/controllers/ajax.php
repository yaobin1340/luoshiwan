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
}