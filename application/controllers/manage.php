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
class Manage extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		ini_set('date.timezone','Asia/Shanghai');
		$this->load->model('manage_model');
		$this->load->library('image_lib');
		$this->load->helper('directory');
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

	//===============================================public start==========================================//
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

	public function add_pics($time,$flag=null){
		$data['time'] = $time;
		$data['flag'] = $flag;

		$this->load->view('manage/add_pics.php',$data);
	}

	public function save_pics($time){
		if (is_readable('./././uploadfiles/pics/'.$time) == false) {
			mkdir('./././uploadfiles/pics/'.$time);
		}

		if (is_readable('./././uploadfiles/pics/'.$time) == false) {
			mkdir('./././uploadfiles/pics/'.$time);
		}

		$path = './././uploadfiles/pics/'.$time;

		//设置缩小图片属性
		$config_small['image_library'] = 'gd2';
		$config_small['create_thumb'] = TRUE;
		$config_small['quality'] = 80;
		$config_small['maintain_ratio'] = TRUE; //保持图片比例
		$config_small['new_image'] = $path;
		$config_small['width'] = 300;
		$config_small['height'] = 190;

		//设置原图限制
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = '10000';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);

		if($this->upload->do_upload()){
			$data = $this->upload->data();//返回上传文件的所有相关信息的数组
			$config_small['source_image'] = $data['full_path']; //文件路径带文件名
			$this->image_lib->initialize($config_small);
			$this->image_lib->resize();
			form_submit_json("200", "操作成功", "");
		}else{
			form_submit_json("300", $this->upload->display_errors('<b>','</b>'));
			exit;
		}
	}

	//ajax获取图片信息
	public function get_pics($time){
		$path = './././uploadfiles/pics/'.$time;
		$map = directory_map($path);
		$data = array();
		//整理图片名字，取缩略图片
		foreach($map as $v){
			if(substr(substr($v,0,strrpos($v,'.')),-5) == 'thumb'){
				$data['img'][] = $v;
			}
		}
		$data['time'] = $time;//文件夹名称
		echo json_encode($data);
	}

	//ajax删除图片
	public function del_pic($folder,$pic){
		@unlink('./././uploadfiles/pics/'.$folder.'/'.$pic);
		@unlink('./././uploadfiles/pics/'.$folder.'/'.str_replace('_thumb', '', $pic));
		$data = array(
			'flag'=>1,
			'pic'=>$pic
		);
		echo json_encode($data);
	}

	//===============================================public end==========================================//
	
	public function list_product(){
		$data = $this->manage_model->list_product();
		$this->load->view('manage/list_product.php',$data);
	}

	public function add_product(){
		$data['list_type'] = $this->manage_model->get_product_type_list();
		$this->load->view('manage/add_product.php',$data);
	}
	
	public function edit_product($id){
		$data = $this->manage_model->get_product($id);
		$data['list_type'] = $this->manage_model->get_product_type_list();
		$this->load->view('manage/add_product.php',$data);
	}
	
	public function save_product(){
		$rs = $this->manage_model->save_product();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_product");
		} else {
			form_submit_json("300", $rs);
		}
	}

	//产品类别
	public function list_product_type(){
		$data = $this->manage_model->list_product_type();
		if($data['res_list'])
			$data['res_list'] = $this->subtree($data['res_list']);
		$this->load->view('manage/list_product_type.php',$data);
	}

	public function add_product_type(){
		$data['type_list'] = $this->get_all_product_type(null);
		$this->load->view('manage/add_product_type.php',$data);
	}

	public function delete_product_type($id){
		$rs = $this->manage_model->delete_product_type($id);
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_product_type", "", "");
		} else {
			form_submit_json("300", $rs);
		}
	}

	public function edit_product_type($id){
		$data = $this->manage_model->get_product_type($id);
		$data['type_list'] = $this->get_all_product_type($id);
		$this->load->view('manage/add_product_type.php',$data);
	}

	public function save_product_type(){
		$rs = $this->manage_model->save_product_type();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_product_type");
		} else {
			form_submit_json("300", $rs);
		}
	}

	//用于选择上级类别,如果存在id，则不取存在的类别，避免出现自己的上级目录是自己的死循环状况
	public function get_all_product_type($id){
		$data = $this->manage_model->get_all_product_type($id);
		return $data;
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

	/**
	 * 树状结构菜单
	 **/
	public function subtree($arr,$id=0,$lev=1)
	{
		static $subs = array();
		foreach($arr as $v){
			if((int)$v['parent_id']==$id){
				$v['lev'] = $lev;
				$subs[]=$v;
				$this->subtree($arr,$v['id'],$lev+1);
			}
		}
		return $subs;
	}





}
