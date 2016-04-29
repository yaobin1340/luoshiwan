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
        $this->cismarty->display('index.html');
    }
    
    function product_main(){
        $this->cismarty->display('product.html');
    }
    
    function product_list(){
        $this->cismarty->display('product_list.html');
    }

    function product_info(){
        $this->cismarty->display('product_info.html');
    }
}