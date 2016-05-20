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

	public function list_order(){
		$data = $this->manage_model->list_order();
		$this->load->view('manage/list_order.php',$data);
	}

	public function edit_order($id){

		$data = $this->manage_model->get_order($id);
		$data['express'] = '';

		if($data['head']->express_num){
			$express = $this->getorder($data['head']->express,$data['head']->express_num);
			if(isset($express['data'])){
				$data['express'] = $express['data'];
			}else{
				$data['express'] = array(array('context'=>'暂无快递信息','time'=>date('Y-m-d H:i:s',time())));
			}
		}
		$this->load->view('manage/edit_order.php',$data);
	}

	public function save_order(){
		$rs = $this->manage_model->save_order();
		if ($rs === 1) {
			form_submit_json("200", "操作成功", "list_order");
		} else {
			form_submit_json("300", $rs);
		}
	}

	public function fahuo($id){
		$data = $this->manage_model->get_order($id);
		$data['express'] = $this->manage_model->get_express();
		$data['id'] = $id;
		$this->load->view('manage/fahuo_dialog.php',$data);
	}

	public function save_fahuo() {
		$ret = $this->manage_model->save_fahuo();
		if($ret == 1){
			form_submit_json("200", "操作成功", 'edit_order');
		} else {
			form_submit_json("300", "保存失败");
		}
	}


	/*
	 * 采集网页内容的方法
	 */
	private function getcontent($url){
		if(function_exists("file_get_contents")){
			$file_contents = file_get_contents($url);
		}else{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}
		return $file_contents;
	}

	/*
	 * 解析object成数组的方法
	 * @param $json 输入的object数组
	 * return $data 数组
	 */
	private function json_array($json){
		if($json){
			foreach ((array)$json as $k=>$v){
				$data[$k] = !is_string($v)?$this->json_array($v):$v;
			}
			return $data;
		}
	}

	/*
	 * 返回$data array      快递数组
	 * @param $name         快递名称
	 * 支持输入的快递名称如下
	 * (申通-EMS-顺丰-圆通-中通-如风达-韵达-天天-汇通-全峰-德邦-宅急送-安信达-包裹平邮-邦送物流
	 * DHL快递-大田物流-德邦物流-EMS国内-EMS国际-E邮宝-凡客配送-国通快递-挂号信-共速达-国际小包
	 * 汇通快递-华宇物流-汇强快递-佳吉快运-佳怡物流-加拿大邮政-快捷速递-龙邦速递-联邦快递-联昊通
	 * 能达速递-如风达-瑞典邮政-全一快递-全峰快递-全日通-申通快递-顺丰快递-速尔快递-TNT快递-天天快递
	 * 天地华宇-UPS快递-新邦物流-新蛋物流-香港邮政-圆通快递-韵达快递-邮政包裹-优速快递-中通快递)
	 * 中铁快运-宅急送-中邮物流
	 * @param $order        快递的单号
	 * $data['ischeck'] ==1   已经签收
	 * $data['data']        快递实时查询的状态 array
	 */
	public  function getorder($name,$order){
		$result = $this->getcontent("http://www.kuaidi100.com/query?type={$name}&postid={$order}");
		$result = json_decode($result);
		$data = $this->json_array($result);
		return $data;
	}





}
