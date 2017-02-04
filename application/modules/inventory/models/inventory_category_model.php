<?php
class Inventory_category_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('inventory_category');
	}
	
	public static function get_select(){
		return array('inventory_category.category_name');
	}
	public static function get_select_id(){
		return array('inventory_category.id', 'inventory_category.category_name');
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'category_name' => "",
		);
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS ".$this->table."(
				id int(9) NOT NULL AUTO_INCREMENT,
				category_name varchar(50) NOT NULL,
				PRIMARY KEY (id))
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}