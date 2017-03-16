<?php
class Inventory_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('inventory');
	}
	
	public static function get_select(){
		return array('inventory.inventory_name', 'inventory.min_amount', 'inventory.amount', 'inventory.category_id', 'inventory.description', 'inventory.location', 'inventory.inventory_active');
	}
	public static function get_select_id(){
		return array('inventory.id', 'inventory.inventory_name', 'inventory.min_amount', 'inventory.amount', 'inventory.category_id', 'inventory.description', 'inventory.location', 'inventory.inventory_active');
	}
	public static function get_relation(){
		return array(
				'category' => array(
						'join' => 'inventory_category',
						'on' => 'inventory.category_id=inventory_category.id',
						'type' => 'left',
						'select' => Inventory_category_model::get_select(),
				),
		);
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'inventory_name' => "",
				'min_amount' => 0,
				'amount' => 0,
				'category_id' => 0,
				'description' => "",
				'location' => "",
				'inventory_active' => 1,
		);
	}
	
	public function get_filter($filter){
		if (isset($filter['search']) && $filter['search'] != ""){
			$this->db->like('inventory_name', $filter['search']);
		}
		if ($filter['remove'] == null || $filter['remove'] != 1){
			$this->db->where('inventory_active', 1);
		}
		if (isset($filter['category_id']) && $filter['category_id'] != null){
			$this->db->where('category_id', $filter['category_id']);
		}
	}
	
	public function count_warnings(){
		$this->db->where('amount < min_amount');
		$this->db->where('inventory_active', 1);
		return $this->db->count_all_results($this->table);
	}
	public function count_search($search, $category = false){
		$this->db->like('inventory_name', $search);
		if ($category !== false && is_array($category)){
			$this->db->where('category_id IN ('.implode(',', $category).')');
		}
		return $this->db->count_all_results($this->table);
	}
	
	public function get($join = array(), $id = false, $positive = true){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->where('inventory_active', 1);
		if ($id == false){
			$this->db->order_by('inventory_name ASC');
			$query = $this->db->get($this->table);
			return $query->result_array();
		}
		else{
			if ($positive){
				$query = $this->db->get_where($this->table, array($this->table.'.id =' => $id));
				return $query->row_array();
			}
			else{
				$this->db->order_by('inventory_name ASC');
				$query = $this->db->get_where($this->table, array($this->table.'.id !=' => $id));
				return $query->result_array();
			}
		}
	}
	public function get_list($join = array(), $limit_from, $limit){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->order_by("inventory_name ASC");
		$query = $this->db->get($this->table, $limit, $limit_from);
		return $query->result_array();
	}
	public function get_warnings($join = array()){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->order_by('inventory_name ASC');
		$this->db->where('amount < min_amount');
		$this->db->where('inventory_active', 1);
		$query = $this->db->get($this->table);
		return $query->result_array();
		
	}
	public function search($join = array(), $search = "", $category = false){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->order_by('inventory_name ASC');
		$this->db->where('inventory_active', 1);
		$this->db->where('inventory_name LIKE \'%'.$search.'%\'');
		if ($category !== false && is_array($category)){
			$this->db->where('category_id IN ('.implode(',', $category).')');
		}
		$query = $this->db->get($this->table);
		return $query->result_array();
	}
	public function get_by_category($category_id, $join = array()){
		$this->db->where('category_id', $category_id);
		return $this->get($join);
	}
	
	
	public function set_amount($amount, $id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('amount' => $amount));
	}
	
	public function remove($id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('inventory_active' => 0));
	}
	
	public function restore($id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('inventory_active' => 1));
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS inventory(
				id int(9) NOT NULL AUTO_INCREMENT,
				inventory_name varchar(128) NOT NULL,
				min_amount int(11) NOT NULL,
				amount int(11) NOt NULL,
				category_id int(9),
				description varchar(255),
				location varchar(100),
				inventory_active tinyint(1) NOT NULL,
				PRIMARY KEY (id),
				FOREIGN KEY (category_id) REFERENCES inventory_category(id) ON DELETE SET NULL)
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}