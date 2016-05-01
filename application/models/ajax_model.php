<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/5/1
 * Time: 11:51
 */
class Ajax_model extends MY_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function __destruct(){
        parent::__destruct();
    }

    function get_product($flag,$page,$type,$limit){
        $this->db->select('a.*,min(b.price) price')->from('product a')
            ->join('product_detail b','a.id = b.pid','left')
            ->where('status',1);
        if ($type!=-1){
            $this->db->where('type',$type);
        }
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

    function save_cart($openid,$qty,$pid,$pd_id){
        $openid=$this->session->userdata('openid');
        $data=array(
            'openid'=>$openid,
            'pid'=>$pid,
            'pd_id'=>$pd_id,
            'qty'=>$qty
        );
        $res = $this->db->insert('cart',$data);
        if (!$res){
           return -1;
        }else{
            return 1;
        }
    }
}