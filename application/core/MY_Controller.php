<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 扩展业务控制器
 *
 * @package		app
 * @subpackage	Libraries
 * @category	controller
 * @author      yaobin<645894453@qq.com>
 *        
 */
class MY_Controller extends CI_Controller
{

    public function __construct ()
    {
        parent::__construct();
        //初始数据
        $this->cismarty->assign('base_url',base_url());//url路径
		ini_set('date.timezone','Asia/Shanghai');
		$this->session->set_userdata('openid', 'sdasdsdsdfs3sad903ekncaocasoj');
		$this->session->set_userdata('username', '测试名称');
		//$this->session->sess_destroy();die;
	 /*   if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
	    	if(!$this->session->userdata('openid')){
	    		$appid="wx84455ea5b029beb2";
				$secret="c9df7b05ce5aec516f9893079d246dd4";
				if(empty($_GET['code'])){
					$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
					$url = urlencode($url);
					redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx84455ea5b029beb2&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect");
				}else{
					$j_access_token=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$_GET['code']}&grant_type=authorization_code");
					$a_access_token=json_decode($j_access_token,true);
					$access_token=$a_access_token["access_token"];
					$openid=$a_access_token["openid"];

					$rs = $this->sysconfig_model->check_openid($openid);
					$this->session->set_userdata('openid', $openid);
				}	
	    	}
	    }
	    $this->cismarty->assign('rel_name',$this->session->userdata('rel_name'));
	    $this->cismarty->assign('admin_group',$this->session->userdata('admin_group'));*/
    }
    
    
	//重载smarty方法assign
	public function assign($key,$val) {  
        $this->cismarty->assign($key,$val);  
    }  
    
	//重载smarty方法display
    public function display($html) {
        $this->cismarty->display($html);  
    }
    
    /**
     * 获取产品菜单的树状结构
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
    
	
	/**
	 * 提示信息
	 * @param varchar $message 提示信息
	 * @param varchar $url 跳转页面，如果为空则后退
	 * @param int $type 提示类型，1是自动关闭的提示框，2是错误提示框
	 * @return array 显示页码的数组
	 **/
	public function show_message($message,$url=null,$type=1){
		if($url){
			$js = "location.href='".$url."';";
		}else{
			$js = "history.back();";
		}
	
		if($type=='1'){
			echo "<html class='no-js'>
				<head>
				  <meta charset='utf-8'>
				  <title>提示信息</title>
				  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
				  <link rel='stylesheet' href='/assets/css/amazeui.min.css'>
				  <script src='/assets/js/jquery.min.js'></script>
				  <script src='/assets/js/amazeui.min.js'></script>
				</head>
				<body>
				<div class='am-modal am-modal-loading am-modal-no-btn' tabindex='-1' id='my-modal-loading'>
				  <div class='am-modal-dialog'>
				    <div class='am-modal-hd'>".$message."...</div>
				    <div class='am-modal-bd'>
				      <span class='am-icon-spinner am-icon-spin'></span>
				    </div>
				  </div>
				</div>

				<script>
				var callFn = function(){
				  ".$js."
				};
				$('#my-modal-loading').modal();
				setTimeout(callFn,1200); 
				</script>
				</body>
				</html>";
		}
		exit;
	}
	
	/**************************************************************
	*  生成指定长度的随机码。
	*  @param int $length 随机码的长度。
	*  @access public
	**************************************************************/
	function createRandomCode($length)
	{
		$randomCode = "";
		$randomChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0; $i < $length; $i++)
		{
			$randomCode .= $randomChars { mt_rand(0, 35) };
		}
		return $randomCode;
	}
	
	/**************************************************************
	*  生成指定长度的随机码。
	*  @param int $length 随机码的长度。
	*  @access public
	**************************************************************/
	function toVirtualPath($physicalPpath)
	{
		$virtualPath = str_replace($_SERVER['DOCUMENT_ROOT'], "", $physicalPpath);
		$virtualPath = str_replace("\\", "/", $virtualPath);
		return $virtualPath;
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

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */