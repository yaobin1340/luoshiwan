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
        $comment = $this->db->select()->from('comment')->where('pid',$id)->where('flag',1)->order_by('cdate','desc')->get()->result_array();
        $comment_num = $this->db->select('count(1) num')->from('comment')->where('pid',$id)->where('flag',1)->get()->row_array();
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
        if (!$comment){
            $data['comment']=1;
        }else{
            $data['comment']=$comment;
        }
        $data['comment_num']=$comment_num['num'];
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
            ->where('a.openid',$openid)->where('a.del',1)->get()->result_array();
        //die(var_dump($this->db->last_query()));
        $default_address=$this->db->select('a.*,f.name f_name,g.name g_name,h.name h_name')->from('address a')
            ->join('province f','f.code = a.provice_code','left')
            ->join('city g','g.code = a.city_code','left')
            ->join('area h','h.code = a.area_code','left')
            ->where(array(
                'a.openid'=>$openid,
                'a.default'=>1,
                'a.del'=>1
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

    function delete_address($id){
        $this->db->where('id',$id)->update('address',array('del'=>-1));
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
    function show_order($status=null){
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
        $this->db->limit(2);
        $this->db->order_by('id','desc');
        $order_main=$this->db->get()->result_array();
        if (!$order_main){
            $data['main'] = 1;
        }else{
            $data['main'] = $order_main;
        }
        foreach($order_main as $v){
            $ids[] = $v['id'];
        }
        if (isset($ids)){
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

    /** 这里显示各个状态的订单数量 */
    function order_num(){
        $openid=$this->session->userdata('openid');
        $res1=$this->db->select('count(1) num')->from('order')
            ->where(array(
                'openid'=>$openid,
                'del'=>1,
                'status'=>1
            ))->get()->row_array();
        $res2=$this->db->select('count(1) num')->from('order')
            ->where(array(
            'openid'=>$openid,
            'del'=>1,
            'status'=>2
        ))->get()->row_array();
        $res3=$this->db->select('count(1) num')->from('order')
            ->where(array(
                'openid'=>$openid,
                'del'=>1,
                'status'=>3
            ))->get()->row_array();
        $res4=$this->db->select('count(1) num')->from('order')
            ->where(array(
                'openid'=>$openid,
                'del'=>1,
                'status'=>4
            ))->get()->row_array();
        $res6=$this->db->select('count(1) num')->from('order')
            ->where(array(
                'openid'=>$openid,
                'del'=>1,
                'status'=>5
            ))->get()->row_array();
        $data['status1']=$res1['num'];
        $data['status2']=$res2['num'];
        $data['status3']=$res3['num'];
        $data['status4']=$res4['num'];
        $data['status6']=$res6['num'];
        return $data;

    }

    function my_address(){
        $openid=$this->session->userdata('openid');
        $address=$this->db->select('a.*,f.name f_name,g.name g_name,h.name h_name')->from('address a')
            ->join('province f','f.code = a.provice_code','left')
            ->join('city g','g.code = a.city_code','left')
            ->join('area h','h.code = a.area_code','left')
            ->where('a.openid',$openid)->where('a.del',1)->get()->result_array();
        if($address){
            $data['item']=$address;
        }else {
            $data['item']=1;
        }
        return $data;

    }

    function save_feedback(){
        $openid=$this->session->userdata('openid');
        $username='测试名称';
        if(!$this->input->post('id')){
            return -1;
        }

        $row=$this->db->select()->from('comment')->where(array(
            'openid'=>$openid,
            'pid'=>$this->input->post('id'),
            'flag'=>1
        ))->get()->row_array();
        $status=$this->db->select('status')->from('order')->where('id',$this->input->post('id'))->get()->row_array();
        if($status['status']!=4){
            return -1;
        }
        $this->db->trans_start();
        if(!$row){
            $data=array(
                'username'=>$username,
                'content'=>$this->input->post('content'),
                'openid'=>$openid,
                'cdate' => date("y-m-d H:i:s",time()),
                'pid'=>$this->input->post('id')
            );
            $this->db->insert('comment',$data);
            $this->db->where('id',$this->input->post('id'))->update('order',array('status'=>7));
        }else{
            $this->db->where('id',$this->input->post('id'))->update('order',array('status'=>7));
        }
        $this->db->trans_complete();//------结束事务
        if ($this->db->trans_status() === FALSE) {
            return -1;
        } else {
            return 1;
        }
    }

    function delete_order($id){
        return $this->db->where('id',$id)->update('order',array('del'=>-1));
    }

    function show_express($id){
        $row = $this->db->select('a.*,b.name ex_name')->from('order a')
            ->join('express b','a.express = b.express','left')->where('a.id',$id)->get()->row_array();
        if(!$row){
            $data['head']=1;
        }else{
            $data['head']=$row;
        }
        return $data;
    }

    function my_info(){
        $openid=$this->session->userdata('openid');
        $row = $this->db->select()->from('user_info')->where('openid',$openid)->get()->row_array();
        if(!$row){
            $data['info'] = 1;
        }else{
            $data['info'] = $row;
        }
        return $data;
    }

    function save_my_info(){
        $openid=$this->session->userdata('openid');
        $data = array(
            'name'=>$this->input->post('name'),
            'mail'=>$this->input->post('mail'),
            'phone'=>$this->input->post('phone'),
            'sex'=>$this->input->post('sex'),
            'openid'=>$openid
        );
        $row = $this->db->select()->from('user_info')->where('openid',$openid)->get()->row_array();
        if(!$row){
            return $this->db->insert('user_info',$data);
        }else{
            return $this->db->where('openid',$openid)->update('user_info',$data);
        }
    }

    function my_house(){
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
            ->limit(6)
            ->get()->result_array();
        if (!$res){
            $data['items']= 1;
        }
        /* echo $this->db->last_query();
         die(var_dump($res));*/
        $data['items']= $res;
        return $data;
    }

    function delete_house($id){
        return $this->db->where('id',$id)->delete('house');
    }
}