<?php
class Order_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('inv_order');
	}
	
	public static function get_select(){
		return array('inv_order.order_number', 'inv_order.supplier_id', 'inv_order.order_date', 'inv_order.order_accepted');
	}
	public static function get_select_id(){
		return array('inv_order.id', 'inv_order.order_number', 'inv_order.supplier_id', 'inv_order.order_date', 'inv_order.order_accepted');
	}
	public static function get_relation(){
		return array(
				'supplier' => array(
						'join' => "supplier",
						'on' => "inv_order.supplier_id=supplier.id",
						'type' => "left",
						'select' => Supplier_model::get_select(),
				),
		);
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'order_number' => "",
				'supplier_id' => 0,
				'order_date' => "0000-00-00 00:00:00",
				'order_accepted' => 0,
		);
	}
	
	public function get_filter($filter){
		if ($filter['accepted'] != null){
			if ($filter['accepted'] == 1){
				$this->db->where('order_accepted', 1);
			}
			else if ($filter['accepted'] == 2){
				$this->db->where('order_accepted', 0);
			}
		}
	}
	
	public function count_year($year){
		$this->db->where("YEAR(order_date)", $year);
		return $this->db->count_all_results($this->table);
	}
	
	
	public function get_last($year){
		$this->db->where("YEAR(order_date)", $year);
		$this->db->order_by("ID DESC");
		$query = $this->db->get($this->table);
		return $query->row_array();
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS inv_order(
				id int(9) NOT NULL AUTO_INCREMENT,
				order_number varchar(100) NOT NULL,
				supplier_id int(9),
				order_date datetime NOT NULL,
				order_accepted tinyint(1) NOT NULL,
				PRIMARY KEY (id),
				FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE SET NULL)
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}