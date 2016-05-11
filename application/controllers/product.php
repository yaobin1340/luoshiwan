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
            redirect('product/index');
        }
    }

    function edit_address($id){
        //  die(var_dump($this->input->post())) ;
        $data = $this->product_model->get_address($id);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('edit_address.html');
    }

    function delete_address(){
         $this->product_model->delete_address();
         redirect('product/index');
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
    function order_info($id){
        $data=$this->product_model->order_info($id);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_order_info.html');
    }
}