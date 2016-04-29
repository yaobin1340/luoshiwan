<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 后台画面控制器
 *
 * @package		app
 * @subpackage	core
 * @category	controller
 * @author		yaobin<645894453@qq.com>
 *
 */
class Manage extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('manage_model');
	}

	function _remap($method,$params = array())
	{
		if(! $this->session->userdata('username'))
		{
			if($this->input->is_ajax_request()){
				header('Content-type: text/json');
				echo '{
                        "statusCode":"301",
                        "message":"\u4f1a\u8bdd\u8d85\u65f6\uff0c\u8bf7\u91cd\u65b0\u767b\u5f55\u3002"
                    }';
			}else{
				redirect(site_url('manage_login/login'));
			}

		}else{
			return call_user_func_array(array($this, $method), $params);
		}
	}

	public function index()
	{
		$this->load->view('manage/index.php');
	}

	public function upload_pic(){
		$path = './././uploadfiles/others/';
		$path_out = '/uploadfiles/others/';
		$msg = '';
	
		//设置原图限制
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = '1000';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
	
		if($this->upload->do_upload('filedata')){
			$data = $this->upload->data();
			$targetPath = $path_out.$data['file_name'];
			$msg="{'url':'".$targetPath."','localname':'','id':'1'}";
			$err = '';
		}else{
			$err = $this->upload->display_errors();
		}
		echo "{'err':'".$err."','msg':".$msg."}";
	}
	

	
	public function list_product(){
		$data = $this->manage_model->list_product();
		$this->load->view('manage/list_product.php',$data);
	}

	public function list_product_dialog(){
		$data = $this->manage_model->list_product();
		$this->load->view('manage/list_product_dialog.php',$data);
	}
	
	public function add_product(){
		$this->load->view('manage/add_product.php');
	}
	
	public function delete_product($id){
		$rs = $this->manage_model->delete_product($id);
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_product", "", "");
		} else {
			form_submit_json("300", $rs);
		}
	}
	
	public function edit_product($id){
		$data = $this->manage_model->get_product($id);
		$this->load->view('manage/add_product.php',$data);
	}
	
	public function save_product(){
		if(!$this->manage_model->check_num($this->input->post('num'))){
			form_submit_json("300", "您输入的型号已经存在");exit;
		}
		if($this->input->post())
		if($_FILES["userfile"]['name'] and $this->input->post('old_img')){//修改上传的图片，需要先删除原来的图片
			@unlink('./././uploadfiles/product/'.$this->input->post('old_img'));//del old img
		}else if(!$_FILES["userfile"]['name'] and !$this->input->post('old_img')){//未上传图片
			form_submit_json("300", "请添加图片");exit;
		}
	
		if(!$_FILES["userfile"]['name'] and $this->input->post('old_img')){//不修改图片信息
			$data = $this->input->post();
			unset($data['ajax']);
			unset($data['old_img']);
			$rs = $this->manage_model->save_product($data);
		}else{
			$config['upload_path'] = './././uploadfiles/product';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '1000';
			$config['encrypt_name'] = true;
			$this->load->library('upload', $config);
			if($this->upload->do_upload()){
				$img_info = $this->upload->data();
				$data = $this->input->post();
				$data['pic'] = $img_info['file_name'];
				unset($data['ajax']);
				unset($data['old_img']);
				$rs = $this->manage_model->save_product($data);
			}else{
				form_submit_json("300", $this->upload->display_errors('<b>','</b>'));
				exit;
			}
		}
	
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_product");
		} else {
			form_submit_json("300", $rs);
		}
	}

	public function list_production(){
		$data = $this->manage_model->list_production();
		$this->load->view('manage/list_production.php',$data);
	}

	public function add_production(){
		$this->load->view('manage/add_production.php');
	}

	public function get_size($num){
		$data = $this->manage_model->get_size($num);
		echo json_encode($data);
	}

	public function save_production(){
		$rs = $this->manage_model->save_production();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_production");
		} else {
			form_submit_json("300", $rs);
		}
	}

	public function edit_production($id){
		$data = $this->manage_model->get_production($id);
		$this->load->view('manage/add_production.php',$data);
	}

	public function delete_production($id){
		$rs = $this->manage_model->delete_production($id);
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_production", "", "");
		} else if($rs == -2){
			form_submit_json("300", '已经作废或已经生产完成的订单无法作废');
		}
		else {
			form_submit_json("300", $rs);
		}
	}

	//包装入库
	public function stock_in(){
		$rs = $this->manage_model->stock_in();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_stock_log");
		} else {
			form_submit_json("300", $rs);
		}
	}

	public function list_stock_log(){
		$data = $this->manage_model->list_stock_log();
		$this->load->view('manage/list_stock_log.php',$data);
	}

	public function add_stock_in(){
		$this->load->view('manage/add_stock_in.php');
	}

	public function list_production_dialog(){
		$data = $this->manage_model->list_production(1);
		$this->load->view('manage/list_production_dialog.php',$data);
	}

	public function get_production($id){
		$data = $this->manage_model->get_production($id);
		echo json_encode($data);
	}

	public function list_users(){
		$data = $this->manage_model->list_users();
		$this->load->view('manage/list_users.php',$data);
	}

	public function add_user(){
		$this->load->view('manage/add_user.php');
	}

	public function save_user(){
		$rs = $this->manage_model->save_user();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_users");
		} elseif($rs == -2){
			form_submit_json("300", '该用户已经存在');
		}else {
			form_submit_json("300", $rs);
		}
	}

	public function reset_pwd($id){
		$rs = $this->manage_model->reset_pwd($id);
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_users", "", "");
		}else {
			form_submit_json("300", $rs);
		}
	}

	public function disable_user($id){
		$rs = $this->manage_model->disable_user($id);
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_users", "", "");
		}else {
			form_submit_json("300", $rs);
		}
	}

	public function list_cust(){
		$data = $this->manage_model->list_cust();
		$this->load->view('manage/list_cust.php',$data);
	}

	public function add_cust(){
		$this->load->view('manage/add_cust.php');
	}

	public function save_cust(){
		$rs = $this->manage_model->save_cust();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_cust");
		} else {
			form_submit_json("300", $rs);
		}
	}



}
