<?php
class Inventory_in_order_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('inventory_in_order');
	}
	
	public static function get_select(){
		return array('inventory_in_order.order_id', 'inventory_in_order.inventory_id', 'inventory_in_order.order_amount', 'inventory_in_order.order_amount_orig', 'inventory_in_order.order_amount_info');
	}
	public static function get_select_id(){
		return array('inventory_in_order.id', 'inventory_in_order.order_id', 'inventory_in_order.inventory_id', 'inventory_in_order.order_amount', 'inventory_in_order.order_amount_orig', 'inventory_in_order.order_amount_info');
	}
	public static function get_relation(){
		return array(
				'inventory' => array(
						'join' => "inventory",
						'on' => "inventory_in_order.inventory_id=inventory.id",
						'type' => "inner",
						'select' => Inventory_model::get_select(),
				),
				'order' => array(
						'join' => "inv_order",
						'on' => "inventory_in_order.order_id=inv_order.id",
						'type' => "inner",
						'select' => Order_model::get_select(),
				),
				'iis_inventory' => array(
						'join' => "inventory_in_supplier",
						'on' => "inventory_in_order.inventory_id=inventory_in_supplier.inventory_id",
						'type' => "inner",
						'select' =>Inventory_in_supplier_model::get_select(),
				),
		);
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'order_id' => 0,
				'inventory_id' => 0,
		);
	}
	
	public function get_excel_data($order_id, $supplier_id){
		$this->db->where("order_id", $order_id);
		$this->db->where("supplier_id", $supplier_id);
		$this->db->order_by("category_id ASC");
		return $this->get(array('iis_inventory', 'inventory', 'inventory.category'));
	}
	
	public function get_by_order($order_id, $join = array()){
		$this->db->where("order_id", $order_id);
		return $this->get($join);
	}
	
	public function remove_by_order($order_id){
		$this->db->where('order_id', $order_id);
		$this->db->delete($this->table);
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS inventory_in_order(
				id int(9) NOT NULL AUTO_INCREMENT,
				order_id int(9) NOT NULL,
				inventory_id int(9) NOT NULL,
				order_amount int(11) NOT NULL,
				order_amount_orig varchar(20) NOT NULL,
				order_amount_info varchar(100),
				PRIMARY KEY (id),
				FOREIGN KEY (inventory_id) REFERENCES inventory(id),
				FOREIGN KEY (order_id) REFERENCES inv_order(id) ON DELETE CASCADE)
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}