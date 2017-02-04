<?php
class Inventory_in_supplier_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('inventory_in_supplier');
	}
	
	public static function get_select(){
		return array('inventory_in_supplier.inventory_id', 'inventory_in_supplier.supplier_id', 'inventory_in_supplier.code', 'inventory_in_supplier.info', 'inventory_in_supplier.price');
	}
	public static function get_select_id(){
		return array('inventory_in_supplier.id', 'inventory_in_supplier.inventory_id', 'inventory_in_supplier.supplier_id', 'inventory_in_supplier.code', 'inventory_in_supplier.info', 'inventory_in_supplier.price');
	}
	public static function get_relation(){
		return array(
				'inventory' => array(
						'join' => "inventory",
						'on' => "inventory_in_supplier.inventory_id=inventory.id",
						'type' => "inner",
						'select' => Inventory_model::get_select(),
				),
				'supplier' => array(
						'join' => "supplier",
						'on' => "inventory_in_supplier.supplier_id=supplier.id",
						'type' => "inner",
						'select' => Supplier_model::get_select(),
				),
		);
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'inventory_id' => 0,
				'supplier_id' => 0,
				'code' => "",
				'info' => "",
				'price' => 0.0,
		);
	}
	
	public function get_by_inventory($inventory_id, $join = array()){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->where('inventory_id', $inventory_id);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}
	public function get_uniq($inventory_id, $supplier_id, $join = array()){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->where('supplier_id', $supplier_id);
		$this->db->where('inventory_id', $inventory_id);
		$query = $this->db->get($this->table);
		if ($query->row_array() == null){
			return $this->get_dummy();
		}
		else{
			return $query->row_array();
		}
	}
	
	public function remove_by_inventory($inventory_id){
		$this->db->where('inventory_id', $inventory_id);
		$this->db->delete($this->table);
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS inventory_in_supplier(
				id int(9) NOT NULL AUTO_INCREMENT,
				inventory_id int(9) NOT NULL,
				supplier_id int(9) NOT NULL,
				code varchar(255) NOT NULL,
				info varchar(100),
				price float(10, 2),
				PRIMARY KEY (id),
				FOREIGN KEY (inventory_id) REFERENCES inventory(id) ON DELETE CASCADE,
				FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE)
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}