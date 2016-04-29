<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 网站后台模型
 *
 * @package		app
 * @subpackage	core
 * @category	model
 * @author		yaobin<645894453@qq.com>
 *        
 */
class Manage_model extends MY_Model
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function __destruct ()
    {
        parent::__destruct();
    }
    
    /**
     * 用户登录检查
     * 
     * @return boolean
     */
    public function check_login ($brokerOnly=true)
    {
        $login_id = $this->input->post('username');
        $passwd = $this->input->post('password');
        $this->db->from('admin');
        $this->db->where('username', $login_id);
        $this->db->where('passwd', sha1($passwd));
        if($brokerOnly) {
        	$this->db->where('admin_group <=', '2');
        }
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	$res = $rs->row();
        	$user_info['user_id'] = $res->id;
            $user_info['username'] = $this->input->post('username');
            $user_info['group_id'] = $res->admin_group;
            $user_info['rel_name'] = $res->rel_name;
            $user_info['manager_group'] = $res->manager_group;
            $user_info['company_id'] = $res->company_id;
            $user_info['subsidiary_id'] = $res->subsidiary_id;

            $this->session->set_userdata($user_info);
            return true;
        } else {
			return false;
        }
    }
    
    /**
     * 修改密码
     * 
     */
    public function change_pwd ()
    {
        $login_id = $this->input->post('username');
        $newpassword = $this->input->post('newpassword');
        
		$rs=$this->db->where('username', $login_id)->update('admin', array('passwd'=>sha1($newpassword))); 
        if ($rs) {
            return 1;
        } else {
            return $rs;
        }
    }

	public function list_product(){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;
	
		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('product');
		if($this->input->post('num'))
			$this->db->like('num',$this->input->post('num'));
	
		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;
	
		$data['num'] = $this->input->post('num')?$this->input->post('num'):null;
		//list
		$this->db->select('*');
		$this->db->from('product');
		if($this->input->post('num')){
			$this->db->like('num',$this->input->post('num'));
		}
	
		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function list_production($dialog){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;

		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('production');
		if($dialog){
			$this->db->where('status',1);
		}
		if($this->input->post('num'))
			$this->db->like('num',$this->input->post('num'));

		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;

		$data['num'] = $this->input->post('num')?$this->input->post('num'):null;
		//list
		$this->db->select('a.*,color,rgb');
		$this->db->from('production a');
		$this->db->join('product b','a.num=b.num','left');
		if($dialog){
			$this->db->where('status',1);
		}
		if($this->input->post('num')){
			$this->db->like('a.num',$this->input->post('num'));
		}

		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function save_product($data){
		$data_head = array(
			'num'=>$this->input->post('num'),
			'color'=>$this->input->post('color'),
			'rgb'=>$this->input->post('rgb'),
		);
		if($data['pic']){
			$data_head['pic']= $data['pic'];
		}
		$size = $this->input->post('size');
		$h_stock = $this->input->post('h_stock');
		$stock = $this->input->post('stock');
		$this->db->trans_start();
		if($this->input->post('id')){//修改
			unset($data_head['num']);

			$rs = $this->db->select('num')->from('product')->where('id',$this->input->post('id'))->get()->row();
			$this->db->where('id',$this->input->post('id'));
			$this->db->update('product',$data_head);

			$this->db->where('num',$rs->num);
			$this->db->delete('stock');

			foreach($size as $k=>$v){
				$data_line = array(
					'num'=>$rs->num,
					'size'=>$v,
					'h_stock'=>$h_stock[$k],
					'stock'=>$stock[$k],
				);
				$this->db->insert('stock',$data_line);
			}

		}else{//新增
			$data_head['cdate'] = date('Y-m-d H:i:s',time());
			$this->db->insert('product', $data_head);


			foreach($size as $k=>$v){
				$data_line = array(
					'num'=>$this->input->post('num'),
					'size'=>$v,
					'h_stock'=>$h_stock[$k],
					'stock'=>$stock[$k],
				);
				$this->db->insert('stock',$data_line);
			}
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return $this->db_error;
		} else {
			return 1;
		}
	}

	public function get_product($id){
		$this->db->from('product')->where('id', $id);
		$data = $this->db->get()->row_array();
		$data['list'] = $this->db->select()->from('stock')->where('num',$data['num'])->order_by('size','acs')->get()->result();
		return $data;
	}

	public function check_num($num){
		$rs = $this->db->select('count(1) num')->from('product')->where('num',$num)->get()->row();
		if($rs->num > 0){
			return false;
		}else{
			return true;
		}
	}

	public function get_size($num){
		$rs = $this->db->select()->from('stock')->where('num',$num)->get()->result();
		return $rs;
	}

	public function save_production(){
		$data_head = array(
			'num'=>$this->input->post('num'),
			'cdate'=>date('Y-m-d H:i:s'),
			'status'=>1
		);
		$size = $this->input->post('size');
		$h_stock = $this->input->post('h_stock');
		$this->db->trans_start();
		$this->db->insert('production',$data_head);
		$h_id = $this->db->insert_id();
		foreach($size as $k=>$v){
			$data_line = array(
				'h_id'=>$h_id,
				'size'=>$v,
				'h_stock'=>$h_stock[$k],
				'stock'=>0
			);
			$this->db->insert('production_detail',$data_line);
			$this->db->where('num',$this->input->post('num'));
			$this->db->where('size',$v);
			$this->db->set('h_stock', 'h_stock+'.$h_stock[$k], FALSE);
			$this->db->update('stock');
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return $this->db_error;
		} else {
			return 1;
		}

	}

	public function get_production($id){
		$data = $this->db->select('a.*,color,rgb')->from('production a')
			->join('product b','a.num=b.num','left')->where('a.id',$id)->get()->row_array();
		$data['list'] = $this->db->select('a.*,c.h_stock s_h_stock,c.stock s_stock')->from('production_detail a')
			->join('production b','a.h_id=b.id','left')
			->join('stock c','b.num=c.num and a.size=c.size','left')
			->where('h_id',$id)->get()->result_array();
		return $data;
	}

	public function delete_production($id){
		$this->db->trans_start();
		$rs = $this->db->select('num,status')->from('production')->where('id',$id)->get()->row();
		if($rs->status != 1){
			return -2;
		}
		$num = $rs->num;
		$data = $this->db->select()->from('production_detail')->where('h_id',$id)->get()->result_array();

		foreach($data as $k=>$v){
			$h_stock = $v['h_stock'] - $v['stock'];
			$this->db->where('num',$num);
			$this->db->where('size',$v['size']);
			$this->db->set('h_stock', 'h_stock-'.$h_stock, FALSE);
			$this->db->update('stock');
		}
		$this->db->where('id',$id);
		$this->db->update('production',array('status'=>-1));
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return $this->db_error;
		} else {
			return 1;
		}
	}

	//包装入库
	public function stock_in(){
		$this->db->trans_start();
		$num = $this->input->post('num');
		$size = $this->input->post('size');
		$h_id = $this->input->post('h_id');
		$qty = $this->input->post('qty');

		foreach($size as $k=>$v){
			if($qty[$k] > 0){
				//已生产出的数量
				$this->db->where('h_id',$h_id[$k]);
				$this->db->where('size',$v);
				$this->db->set('stock', 'stock+'.$qty[$k], FALSE);
				$this->db->update('production_detail');

				//实际库存
				$this->db->where('num',$num);
				$this->db->where('size',$v);
				$this->db->set('stock', 'stock+'.$qty[$k], FALSE);
				$this->db->update('stock');

				//修改生产下单的状态
				$rs = $this->db->select('sum(h_stock) h_stock,sum(stock) stock')->from('production_detail')
					->where('h_id',$h_id[$k])->get()->row();
				if($rs->stock >= $rs->h_stock){
					$this->db->where('id',$h_id[$k]);
					$this->db->update('production',array('status'=>2));
				}

				$stock_log = array(
					'num'=>$num,
					'size'=>$v,
					'qty'=>$qty[$k],
					'io'=>1,
					'type'=>1,
					'cdate'=>date('Y-m-d H:i:s'),
					'user' => $this->session->userdata('rel_name')
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

	public function list_stock_log(){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;

		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('stock_log');
		$this->db->where('type',1);
		if($this->input->post('num'))
			$this->db->like('num',$this->input->post('num'));

		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;

		$data['num'] = $this->input->post('num')?$this->input->post('num'):null;
		//list
		$this->db->select('a.*,color,rgb');
		$this->db->from('stock_log a');
		$this->db->where('a.type',1);
		$this->db->join('product b','a.num=b.num','left');
		if($this->input->post('num')){
			$this->db->like('a.num',$this->input->post('num'));
		}

		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function list_users(){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;

		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('admin');
		$this->db->where('admin_group !=',1);
		if($this->input->post('username'))
			$this->db->like('username',$this->input->post('username'));

		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;

		$data['username'] = $this->input->post('username')?$this->input->post('username'):null;
		//list
		$this->db->select();
		$this->db->from('admin');
		$this->db->where('admin_group !=',1);
		if($this->input->post('username'))
			$this->db->like('username',$this->input->post('username'));

		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function save_user(){
		$data = array(
			'username'=>$this->input->post('username'),
			'rel_name'=>$this->input->post('rel_name'),
			'passwd'=>sha1('888888'),
			'admin_group'=>$this->input->post('admin_group'),
			'cdate'=>date('Y-m-d H:i:s')
		);

		$rs = $this->db->select('count(1) num')->from('admin')->where('username',$data['username'])->get()->row();
		if($rs->num > 0){
			return -2;
		}
		$rs = $this->db->insert('admin',$data);
		if($rs)
			return 1;
		else
			return -1;
	}

	public function reset_pwd($id){
		$this->db->where('id',$id);
		$rs = $this->db->update('admin',array('passwd'=>sha1('888888')));
		if($rs)
			return 1;
		else
			return -1;
	}

	public function disable_user($id){
		$this->db->where('id',$id);
		$rs = $this->db->update('admin',array('status'=>-1));
		if($rs)
			return 1;
		else
			return -1;
	}

	public function list_cust(){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;

		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('cust');
		if($this->input->post('name'))
			$this->db->like('name',$this->input->post('name'));

		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;

		$data['name'] = $this->input->post('name')?$this->input->post('name'):null;
		//list
		$this->db->select();
		$this->db->from('cust');
		if($this->input->post('name'))
			$this->db->like('name',$this->input->post('name'));

		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function save_cust(){
		$data = array(
			'name'=>$this->input->post('name'),
			'phone'=>$this->input->post('phone'),
			'remark'=>$this->input->post('remark'),
			'cdate'=>date('Y-m-d H:i:s')
		);

		$rs = $this->db->insert('cust',$data);
		if($rs)
			return 1;
		else
			return -1;
	}



}
