<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 普通用户模型
 *
 * @package		app
 * @subpackage	core
 * @category	model
 * @author		yaobin<645894453@qq.com>
 *        
 */
class Stock_model extends MY_Model
{

	
    public function __construct ()
    {
        parent::__construct();
    }

    public function __destruct ()
    {
        parent::__destruct();
    }

	public function get_stock($num){
		$data = $this->db->select()->from('product')->where('num',$num)->get()->row_array();
		if(!$data)
			return 1;
		$data['list'] = $this->db->select()->from('stock')->where('num',$num)->order_by('size','acs')->get()->result_array();
		return $data;
	}

	public function save_stock(){
		if(!$this->input->post('type'))
			return -1;

		$this->db->trans_start();
		$num = $this->input->post('num');
		$qty = $this->input->post('qty');
		$size = $this->input->post('size');
		foreach($size as $k=>$v){
			if($qty[$k] > 0){
				//修改实际库存
				$this->db->where('num',$num);
				$this->db->where('size',$v);
				if($this->input->post('type') > 2){//出库
					$this->db->set('stock', 'stock-'.$qty[$k], FALSE);
					$io = -1;
				}else{//入库
					$this->db->set('stock', 'stock+'.$qty[$k], FALSE);
					$io = 1;
				}
				$this->db->update('stock');

				//记录库存变动记录
				$stock_log = array(
					'num' => $num,
					'size' => $v,
					'qty' => $qty[$k],
					'io' => $io,
					'type' => $this->input->post('type'),
					'cdate' => date('Y-m-d H:i:s'),
					'user' => $this->session->userdata('rel_name'),
					'cust' => $this->input->post('cust')
				);
				$this->db->insert('stock_log',$stock_log);
			}
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return $this->db_error;
		} else {
			return 1;
		}
	}

	public function get_cust(){
		return $this->db->select()->from('cust')->get()->result_array();
	}

	public function get_stock_log($num){
		$data = $this->db->select()->from('stock_log')
			->where('num',$num)
			->where('DATE_SUB(CURDATE(), INTERVAL 30 DAY) <=','cdate')
			->order_by('cdate','desc')->get()->result_array();
		if(!$data)
			return 1;
		return $data;

	}
    

}

/* End of file sysconfig_model.php */
/* Location: ./application/models/sysconfig_model.php */