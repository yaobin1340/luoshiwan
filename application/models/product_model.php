<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/4/29
 * Time: 22:07
 *
 */
class Product_model extends MY_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function get_product_recommend(){
         $data=$this->db->select('a.*,min(b.price) price')->from('product a')
             ->join('product_detail b','a.id = b.pid','left')
             ->where('recommend',1)
             ->where('status',1)
             ->group_by('a.id')
             ->get()->result_array();
        if (!$data){
            return 1;
        }
        return $data;
    }

    public function get_product_type(){
        $data=$this->db->select()->from('product_type')
            ->get()->result_array();
        if (!$data){
            return 1;
        }
        return $data;
    }

    public function get_product_list($id,$flag){
        $row=$this->db->select()->from('product_type')->where('id',$id)->get()->row_array();
         $this->db->select('a.*,min(b.price) price')->from('product a')
            ->join('product_detail b','a.id = b.pid','left')
             ->where(array(
                 'status'=>1,
                 'type'=>$id
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
        $res=$this->db
            ->group_by('a.id')
            ->limit(6)
            ->get()->result_array();
        if (!$res){
            $data['items']= 1;
        }
       /* echo $this->db->last_query();
        die(var_dump($res));*/
        $data['type_id']=$id;
        $data['flag']=$flag;
        $data['items']= $res;
        $data['title']=$row['name'];
        return $data;
    }
//
    public function get_product_main($flag){

        $this->db->select('a.*,min(b.price) price')->from('product a')
            ->join('product_detail b','a.id = b.pid','left')
            ->where(array(
                'status'=>1
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
        $res=$this->db
            ->group_by('a.id')
            ->limit(6)
            ->get()->result_array();
        if (!$res){
            $data['items']= 1;
        }
        /* echo $this->db->last_query();
         die(var_dump($res));*/
        $data['flag']=$flag;
        $data['items']= $res;
        return $data;
    }

    public function get_product_info($id){
       $info = $this->db->select('a.*,min(b.price) price')->from('product a')
           ->join('product_detail b','a.id = b.pid','left')
           ->where(array(
               'a.id'=>$id
           ))
           ->group_by('a.id')
           ->get()->row_array();
        $details=$this->db->select()->from('product_detail')->where('pid',$id)->order_by('price','asc')->get()->result_array();
        $type=$this->db->select()->from('product_type')->where('id',$info['type'])->get()->row_array();
        if (!$info){
            $data['info']=1;
        }else{
            $data['info']=$info;
        }
        if (!$details){
            $data['details']=1;
        }else{
            $data['details']=$details;
        }
        if (!$type){
            $data['title']=1;
        }else{
            $data['title']=$type['name'];
        }
        return $data;
    }

    function show_cart(){
        $openid=$this->session->userdata('openid');
       $res = $this->db->select('count(1) num')->from('cart')->where('openid',$openid)->get()->row_array();
        if (!$res){
            $data['num']=0;
        }else{
            $data['num']=$res['num'];
        }
    $detail = $this->db->select('a.*,b.id pro_id,b.name pro_name,b.bg_pic,c.size de_size,c.price de_price')->from('cart a')
    ->join('product b','a.pid = b.id','left')
    ->join('product_detail c','a.pd_id = c.id','left')
    ->where('a.openid',$openid)
    ->get()->result_array();
        if (!$detail){
            $data['item']=1;
        }else{
            $data['item']=$detail;
        }
       return $data;
    }
}