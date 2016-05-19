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
		if($this->input->post('name'))
			$this->db->like('name',$this->input->post('name'));
	
		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;
	
		$data['name'] = $this->input->post('name')?$this->input->post('name'):null;
		//list
		$this->db->select('*');
		$this->db->from('product');
		if($this->input->post('name')){
			$this->db->like('name',$this->input->post('name'));
		}
	
		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function save_product(){
		$data_head = array(
			'name'=>$this->input->post('name'),
			'cdate'=>date('Y-m-d H:i:s'),
			'desc'=>$this->input->post('desc'),
			'folder'=>$this->input->post('folder'),
			'bg_pic'=>$this->input->post('is_bg'),
			'status'=>$this->input->post('status'),
			'recommend'=>$this->input->post('recommend'),
			'type'=>$this->input->post('type'),
		);

		$price = $this->input->post('price');
		$s_price = $this->input->post('s_price');
		$size = $this->input->post('size');
		$this->db->trans_start();

		if($this->input->post('id')){//edit
			$pid = $this->input->post('id');
			$this->db->where('id',$pid);
			$this->db->update('product',$data_head);

			$this->db->where('pid',$pid);
			$this->db->delete('product_detail');
		}else{//add
			$this->db->insert('product',$data_head);
			$pid = $this->db->insert_id();
		}

		foreach($size as $k=>$v){
			$data_line = array(
				'pid'=>$pid,
				'size'=>$v,
				'price'=>$price[$k],
				's_price'=>$s_price[$k],
			);
			$this->db->insert('product_detail',$data_line);
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

		$data['list'] = $this->db->select()->from('product_detail')->where('pid',$id)->get()->result();
		return $data;
	}


	public function list_product_type(){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;

		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('product_type');
		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;

		//list
		$this->db->select('*');
		$this->db->from('product_type');
		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$data['res_list'] = $this->db->get()->result_array();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}


	public function save_product_type(){
		$data = array(
			'name'=>$this->input->post('name'),
			'parent_id'=>$this->input->post('parent_id')?$this->input->post('parent_id'):0,
		);
		$this->db->trans_start();
		if($this->input->post('id')){//修改
			$this->db->where('id', $this->input->post('id'));
			$this->db->update('product_type', $data);
		}else{//新增
			$data['cdate'] = date('Y-m-d H:i:s',time());
			$this->db->insert('product_type', $data);
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return $this->db_error;
		} else {
			return 1;
		}
	}

	//获取新闻列别，用于选择上级类别,如果存在id，则不取存在的类别，避免出现自己的上级目录是自己的死循环状况
	public function get_all_product_type($id){
		$this->db->select()->from('product_type');
		if($id)
			$this->db->where('id !=',$id);
		$data = $this->db->get()->result_array();
		return $data;
	}

	public function delete_product_type($id){
		$rs = $this->db->delete('product_type', array('id' => $id));
		if($rs){
			return 1;
		}else{
			return $this->db_error;
		}
	}

	public function get_product_type($id){
		$this->db->select('*')->from('product_type')->where('id', $id);
		$data = $this->db->get()->row_array();
		return $data;
	}

	public function get_product_type_list(){
		$this->db->select('*')->from('product_type');
		$data = $this->db->get()->result();
		return $data;
	}

	public function list_order(){
		// 每页显示的记录条数，默认20条
		$numPerPage = $this->input->post('numPerPage') ? $this->input->post('numPerPage') : 20;
		$pageNum = $this->input->post('pageNum') ? $this->input->post('pageNum') : 1;

		//获得总记录数
		$this->db->select('count(1) as num');
		$this->db->from('order');

		if($this->input->post('num'))
			$this->db->like('num',$this->input->post('num'));
		if($this->input->post('status'))
			$this->db->where('status',$this->input->post('status'));

		$rs_total = $this->db->get()->row();
		//总记录数
		$data['countPage'] = $rs_total->num;

		$data['num'] = $this->input->post('num')?$this->input->post('num'):null;
		$data['status'] = $this->input->post('status')?$this->input->post('status'):null;
		//list
		$this->db->select();
		$this->db->from('order a');

		if($this->input->post('num'))
			$this->db->like('num',$this->input->post('num'));
		if($this->input->post('status'))
			$this->db->where('status',$this->input->post('status'));

		$this->db->limit($numPerPage, ($pageNum - 1) * $numPerPage );
		$this->db->order_by($this->input->post('orderField') ? $this->input->post('orderField') : 'id', $this->input->post('orderDirection') ? $this->input->post('orderDirection') : 'desc');
		$data['res_list'] = $this->db->get()->result();
		$data['pageNum'] = $pageNum;
		$data['numPerPage'] = $numPerPage;
		return $data;
	}

	public function get_order($id){
		$data['head'] = $this->db->select('a.*,b.name name,phone,address,zip,c.name province_name,d.name city_name,e.name area_name')->from('order a')
			->join('address b','a.address_id=b.id','left')
			->join('province c','b.provice_code=c.code','left')
			->join('city d','b.city_code=d.code','left')
			->join('area e','b.area_code=e.code','left')
			->where('a.id',$id)->get()->row();

		$data['list'] = $this->db->select('a.*,,b.name product_name,c.size')->from('order_detail a')
			->join('product b','a.pid=b.id','left')
			->join('product_detail c','a.pd_id=c.id','left')

			->where('a.oid',$id)->get()->result();

		return $data;
	}

	public function get_express(){
		return $this->db->select()->from('express')->get()->result();
	}


}
