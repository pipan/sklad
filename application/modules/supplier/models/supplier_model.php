<?php
class Supplier_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('supplier');
	}
	
	public static function get_select(){
		return array('supplier.supplier_name', 'supplier.supplier_email', 'supplier.supplier_phone', 'supplier.supplier_web', 'supplier.supplier_contact_person', 'supplier.supplier_priority', 'supplier.supplier_active');
	}
	public static function get_select_id(){
		return array('supplier.id', 'supplier.supplier_name', 'supplier.supplier_email', 'supplier.supplier_phone', 'supplier.supplier_web', 'supplier.supplier_contact_person', 'supplier.supplier_priority', 'supplier.supplier_active');
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'supplier_name' => "",
				'supplier_email' => "",
				'supplier_phone' => "",
				'supplier_web' => "",
				'supplier_contact_person' => "",
				'supplier_priority' => 0.0,
				'supplier_active' => 1,
		);
	}
	
	public function get_filter($filter){
		if ($filter['remove'] == null || $filter['remove'] != 1){
			$this->db->where('supplier_active', 1);
		}
	}
	
	public function get($join = array(), $id = false, $positive = true){
		$this->db->select($this->join($join, $this->get_select_id()));
		if ($id == false){
			$this->db->order_by('supplier_name ASC');
			$query = $this->db->get($this->table);
			return $query->result_array();
		}
		else{
			if ($positive){
				$query = $this->db->get_where($this->table, array($this->table.'.id =' => $id));
				return $query->row_array();
			}
			else{
				$this->db->order_by('supplier_name ASC');
				$query = $this->db->get_where($this->table, array($this->table.'.id !=' => $id));
				return $query->result_array();
			}
		}
	}
	public function get_active($join = array()){
		$this->db->where(array('supplier_active' => 1));
		$this->db->order_by('supplier_priority DESC');
		return $this->get($join);
	}
	
	public function remove($id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('supplier_active' => 0));
	}
	
	public function restore($id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('supplier_active' => 1));
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS supplier(
				id int(9) NOT NULL AUTO_INCREMENT,
				supplier_name varchar(30) NOT NULL,
				supplier_email varchar(100),
				supplier_phone varchar(20),
				supplier_web varchar(100),
				supplier_contact_person varchar(100),
				supplier_priority float(10,5) NOT NULL,
				supplier_active tinyint(1) NOT NULL,
				PRIMARY KEY (id))
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}