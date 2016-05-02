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

    function show_cart(){
        $data=$this->product_model->show_cart();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('cart.html');
    }
}