<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/4/29
 * Time: 22:07
 *33eee
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
        $address=$this->db->select('a.*,f.name f_name,g.name g_name,h.name h_name')->from('address a')
            ->join('province f','f.code = a.provice_code','left')
            ->join('city g','g.code = a.city_code','left')
            ->join('area h','h.code = a.area_code','left')
            ->where('a.openid',$openid)->get()->result_array();
        //die(var_dump($this->db->last_query()));
        $default_address=$this->db->select('a.*,f.name f_name,g.name g_name,h.name h_name')->from('address a')
            ->join('province f','f.code = a.provice_code','left')
            ->join('city g','g.code = a.city_code','left')
            ->join('area h','h.code = a.area_code','left')
            ->where(array(
                'a.openid'=>$openid,
                'a.default'=>1
            ))->get()->row_array();
        if (!$address){
            $data['address']=1;
        }else{
            $data['address']=$address;
        }
        if (!$default_address){
            $data['default_address']=1;
        }else{
            $data['default_address']=$default_address;
        }
       return $data;
    }

    function save_address(){
        $openid=$this->session->userdata('openid');
        $data=$this->input->post();
        $data['default'] = $this->input->post('default')? 1 : -1;
        $data['openid'] = $openid;
        $this->db->trans_start();
        if (!$this->input->post('id')){
            if ($this->input->post('default')){
                $this->db->where('openid',$openid)->update('address',array('default'=> -1));
                $this->db->insert('address',$data);
            }else{
                $this->db->insert('address',$data);
            }
        }else{
            unset($data['id']);
            if ($this->input->post('default')){
                $this->db->where('openid',$openid)->update('address',array('default'=> -1));
                $this->db->where('id',$this->input->post('id'))->update('address',$data);
            }else{
                $this->db->where('id',$this->input->post('id'))->update('address',$data);
            }
        }

        $this->db->trans_complete();//------结束事务
        if ($this->db->trans_status() === FALSE) {
            return -1;
        } else {
            return 1;
        }
    }

    function get_address($id){
        $openid=$this->session->userdata('openid');
        $default_address=$this->db->select()->from('address')
            ->where(array(
               // 'openid'=>$openid,
                'id'=> $id
            ))->get()->row_array();

        if (!$default_address){
            $data=1;
        }else{
            $data=$default_address;
        }
        return $data;
    }

    function delete_address(){
        $this->db->where('id',$this->input->post('id'))->delete('address');
    }

    function save_order(){
        $openid=$this->session->userdata('openid');
        $order_id=-1;
       if (!$this->input->post('address_id') || !$this->input->post('cart_id')){
           return -1;
       }
        //先根据购物车商品的编号，查用户购物车信息是否还存在，以免重复保存订单
        $carts=$this->input->post('cart_id');
        foreach ($carts as $key => $v){
            $row = $this->db->select()->from('cart')
                ->where(array(
                    'id'=>$v,
                    'openid'=>$openid
                ))->get()->row_array();
            if (!$row){
                return -1;
            }
        }
        $this->db->trans_start();
        //先新建order数据
        $order_data=array(
            'address_id'=>$this->input->post('address_id'),
            'num' => date("ymdHis",time()).mt_rand(0,9999),
            'cdate' => date("y-m-d H:i:s",time()),
            'status' => 1,
            'openid' => $openid
        );
        $this->db->insert('order',$order_data);
        $order_id = $this->db->insert_id();
        foreach ($carts as $key => $v){
            $detail = $this->db->select('a.*,b.id pro_id,b.name pro_name,b.bg_pic,c.size de_size,c.price de_price')->from('cart a')
                ->join('product b','a.pid = b.id','left')
                ->join('product_detail c','a.pd_id = c.id','left')
                ->where('a.openid',$openid)
                ->where('a.id',$v)
                ->get()->row_array();

          $this->db->insert('order_detail',array(
              'oid'=>$order_id,
              'pid'=>$detail['pro_id'],
              'price'=>$detail['de_price'],
              's_price'=>0,
              'qty'=>$detail['qty'],
              'pd_id'=>$detail['pd_id']
          ));
        }
        $this->db->where_in('id',$this->input->post('cart_id'))->delete('cart');
        $this->db->trans_complete();//------结束事务
        if ($this->db->trans_status() === FALSE) {
            return -1;
        } else {
            return $order_id;
        }
    }

    /** 这里显示订单信息 */
    function show_order(){
        $openid=$this->session->userdata('openid');
       $order_main=$this->db->select()->from('order')->where('openid',$openid)->get()->result_array();
        if (!$order_main){
            $data['main'] = 1;
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

    /** 这里显示订单详情 */
    function order_info($id){
        $openid=$this->session->userdata('openid');
        $order_main=$this->db->select('a.*,f.name f_name,g.name g_name,h.name h_name,b.name add_name,b.phone,b.address,b.zip')->from('order a')
            ->join('address b','b.id = a.address_id','left')
            ->join('province f','f.code = b.provice_code','left')
            ->join('city g','g.code = b.city_code','left')
            ->join('area h','h.code = b.area_code','left')
            ->where('a.id',$id)
            ->where('a.openid',$openid)->get()->row_array();
        if (!$order_main){
            $data['main'] = 1;
        }else{
            $data['main'] = $order_main;
        }
        $order_detail=$this->db->select('a.*,b.bg_pic,b.name pro_name,c.size de_size')->from('order_detail a')
            ->join('product b','a.pid = b.id','left')
            ->join('product_detail c','a.pd_id = c.id','left')
            ->where_in('oid',$order_main['id'])->get()->result_array();
        if (!$order_detail){
            $data['detail'] = 1;
        }else{
            $data['detail'] = $order_detail;
        }
        return $data;
    }
}