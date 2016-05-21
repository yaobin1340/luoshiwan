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

    function delete_cart($id){
        $openid=$this->session->userdata('openid');
       $res = $this->db->where('id',$id)->delete('cart');
        if (!$res){
            return -1;
        }else{
            $num = $this->db->select('count(1) num')->from('cart')->where('openid',$openid)->get()->row_array();
            if (!$num){
                return 0;
            }else{
                return $num['num'];
            }
        }
    }

    function change_cart($id,$qty){
       $res = $this->db->where('id',$id)->update('cart',array('qty'=>$qty));
        if (!$res){
            return -1;
        }else{
            return 1;
        }
    }

    public function getprovince(){
        $data=$this->db->select()->from('province')->get()->result_array();
        if (!$data){
            return 1;
        }else{
            return $data;
        }
    }

    public function getcity($code){
        $data=$this->db->select()->from('city')->where('provincecode',$code)->get()->result_array();
        if (!$data){
            return 1;
        }else{
            return $data;
        }
    }

    public function getarea($code){
        $data=$this->db->select()->from('area')->where('citycode',$code)->get()->result_array();
        if (!$data){
            return 1;
        }else{
            return $data;
        }
    }

    function save_order($address_id,$pid){

    }

    /** 这里保存订单信息 */
    function get_order($page,$status){
        $limit=2;
        $openid=$this->session->userdata('openid');
        $this->db->select()->from('order')->where('openid',$openid);
        if(!empty($status)){
            switch ($status){
                case 1:
                    $this->db->where('status',1);
                    break;
                case 2:
                    $this->db->where('status',2);
                    break;
                case 3:
                    $this->db->where('status',3);
                    break;
                case 4:
                    $this->db->where('status',4);
                    break;
                case 5:
                    $this->db->where('status',5);
                    break;

            }
        }
        $this->db->where('del',1);
        $this->db->limit($limit, $offset = ($page - 1) * $limit);
        $this->db->order_by('id','desc');
        $order_main=$this->db->get()->result_array();
        if (!$order_main){
            return 1;
        }else{
            $data['main'] = $order_main;
        }
        foreach($order_main as $v){
            $ids[] = $v['id'];
        }
        if ($ids){
            $order_detail=$this->db->select('a.*,b.bg_pic,b.name pro_name,c.size de_size')->from('order_detail a')
                ->join('product b','a.pid = b.id','left')
                ->join('product_detail c','a.pd_id = c.id','left')
                ->where_in('oid',$ids)->get()->result_array();

            if (!$order_detail){
                $data['detail'] = 1;
            }else{
                $data['detail'] = $order_detail;
            }
        }else{
            $data['detail'] = 1;
        }

        return $data;

    }

    /** 这里修改默认地址 */
    function default_address($address_id){
        $openid=$this->session->userdata('openid');
        $this->db->trans_start();
        $this->db->where('openid',$openid)->update('address',array('default'=> -1));
        $this->db->where('id',$address_id)->update('address',array('default'=> 1));

        $this->db->trans_complete();//------结束事务
        if ($this->db->trans_status() === FALSE) {
            return -1;
        } else {
            return 1;
        }
    }

    function change_remind($id){
        $row = $this->db->select()->from('order')->where('id',$id)->get()->row_array();
        if($row){
            if($row['remind']==1){
                $res = $this->db->where('id',$id)->update('order',array('remind'=>2));
                if($res){
                    return 1;
                }
            }
        }
        return -1;
    }

    /** 这里做保存收藏 */
    function save_house($id){
        $openid=$this->session->userdata('openid');
        $row = $this->db->select()->from('house')->where('pid',$id)->where('openid',$openid)->get()->row_array();
        if(!$row){
            $data = array(
                'openid'=>$openid,
                'pid'=>$id,
                'cdate' => date("y-m-d H:i:s",time())
            );
                $res = $this->db->insert('house',$data);
                if($res){
                    return 1;
                }
        }else{
            return 2;
        }
        return -1;
    }

    /** 捞取收藏信息 */
    function get_house($page=1){
        $limit=6;
        $openid=$this->session->userdata('openid');
        $this->db->select('a.*,c.id house_id,min(b.price) price')->from('house c')
            ->join('product a','a.id = c.pid','left')
            ->join('product_detail b','a.id = b.pid','left')
            ->where(array(
                'a.status'=>1,
                'c.openid'=>$openid
            ));
        $res=$this->db
            ->group_by('c.pid')
            ->order_by('c.id','desc')
            ->limit($limit, $offset = ($page - 1) * $limit)
            ->get()->result_array();
        if (!$res){
            return 1;
        }else{
            return $res;
        }
        /* echo $this->db->last_query();
         die(var_dump($res));*/
    }
}