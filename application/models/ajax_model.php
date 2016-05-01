<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/5/1
 * Time: 11:51
 */
class Product_model extends MY_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function __destruct(){
        parent::__destruct();
    }

    function get_product($type,$flag,$page,$limit){
        $this->db->select('a.*,min(b.price) price')->from('product a')
            ->join('product_detail b','a.id = b.pid','left')
            ->where(array(
                'status'=>1,
                'recommend'=>1,
                'type'=>$type
            ));
        switch ($flag){
            case 1:
                break;
            case 2:

                break;
            case 3:
                $this->db->order_by('price','desc');
                break;
            case 4:
                $this->db->order_by('price','asc');
                break;
            default:
        }
        $res=$this->db->group_by('a.id')
             ->limit($limit, $offset = ($page - 1) * $limit)
            ->get()->result_array();
        if (!$res){
            return 1;
        }else{
            return $res;
        }
    }
}