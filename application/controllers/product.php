<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/4/29
 * Time: 22:03
 */
class Product extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }

    function index(){
        $data=$this->product_model->get_product_recommend();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('index.html');
    }
    
    function product_main($flag=1){
        $data=$this->product_model->get_product_main($flag);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('product.html');
    }
    
    function product_list($id,$flag=1){
        $data=$this->product_model->get_product_list($id,$flag);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('product_list.html');
    }

    function product_info($id,$html_flag=-1){
        $data=$this->product_model->get_product_info($id);
        $this->cismarty->assign('html_flag',$html_flag);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('product_info.html');
    }

    function product_type(){
        $data=$this->product_model->get_product_type();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('prt_type.html');
    }

    /** 这里显示购物车信息 */
    function show_cart(){
        $data=$this->product_model->show_cart();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('cart.html');
    }

    /** 这里做地址的处理 */
    function add_address($type){
        $this->cismarty->assign('type',$type);
        $this->cismarty->display('add_address.html');
    }

    function save_address($page=1){
      //  die(var_dump($this->input->post())) ;
       $this->product_model->save_address();
        if ($page == 1){
            redirect('product/show_cart');
        }elseif ($page ==2){
            redirect('product/my_address');
        }
    }

    function edit_address($id){
        //  die(var_dump($this->input->post())) ;
        $data = $this->product_model->get_address($id);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('edit_address.html');
    }

    function delete_address($id){
         $this->product_model->delete_address($id);
         redirect('product/my_address');
    }

    /** 这里保存订单信息 */
    function save_order(){
        $data=$this->product_model->save_order();
        if ($data == -1){
            redirect('product/show_cart');
        }else{
            redirect('product/order_info/'.$data);
        }
    }

    /** 这里显示订单信息 */
    function show_order(){
        $data=$this->product_model->show_order();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_order.html');
    }

    /** 这里显示订单详情 */
    function order_info($id,$status=null){
        $data=$this->product_model->order_info($id);
        $this->cismarty->assign('status',$status);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_order_info.html');
    }

    /** 这里进入个人中心 */
    function my_center(){
        $data=$this->product_model->order_num();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_center.html');
    }

    /** 这里显示各种状态的订单 */
    function status_order($status=null){
        $data=$this->product_model->show_order($status);
        $this->cismarty->assign('status',$status);
        $this->cismarty->assign('data',$data);
        switch ($status){
            case 1:
                $this->cismarty->display('my_order_status.html');
                break;
            case 2:
                $this->cismarty->display('my_order_status.html');
                break;
            case 3:
                $this->cismarty->display('my_order_status.html');
                break;
            case 4:
                $this->cismarty->display('my_order_status.html');
                break;
            case 5:
                $this->cismarty->display('my_order_status.html');
                break;
            default:
                $this->cismarty->display('my_order_status.html');
        }

    }

    function my_address(){
        $data=$this->product_model->my_address();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_address.html');
    }

    function feedback($id,$status=null){
        $this->cismarty->assign('id',$id);
        $this->cismarty->assign('status',$status);
        $this->cismarty->display('feedback.html');
    }

    function save_feedback($status=null){
        $this->product_model->save_feedback();
        redirect('product/status_order/'.$status);
    }

    function delete_order($id,$status=null){
        $this->product_model->delete_order($id);
        if($status==1){
            redirect('product/status_order/1');
        }else{
            redirect('product/status_order/4');
        }
    }

    function show_express($id,$status=null){
        $data = $this->product_model->show_express($id);
        //die(var_dump($data));
        if($data['head']!=1){
            $express = $this->getorder($data['head']['express'],$data['head']['express_num']);
        }
        if(isset($express['data'])){
            $data['express'] = $express['data'];
        }else{
            $data['express'] = array(array('context'=>'暂无快递信息','time'=>date('Y-m-d H:i:s',time())));
        }
        $this->cismarty->assign('status',$status);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('express_info.html');
    }

    function my_info(){
        $data = $this->product_model->my_info();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_info.html');
    }

    function save_my_info(){
        $res = $this->product_model->save_my_info();
        redirect('product/my_center');
    }

    function my_house(){
        $data = $this->product_model->my_house();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_house.html');
    }

    function delete_house($id){
        $res = $this->product_model->delete_house($id);
        redirect('product/my_house');
    }
}