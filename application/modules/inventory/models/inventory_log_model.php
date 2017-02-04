<?php
class Inventory_log_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('inventory_log');
	}
	
	public static function get_select(){
		return array('inventory_log.inventory_id', 'inventory_log.user_id', 'inventory_log.log_amount', 'inventory_log.log_date', 'inventory_log.log_note');
	}
	public static function get_select_id(){
		return array('inventory_log.id', 'inventory_log.inventory_id', 'inventory_log.user_id', 'inventory_log.log_amount', 'inventory_log.log_date', 'inventory_log.log_note');
	}
	public static function get_relation(){
		return array(
				'inventory' => array(
						'join' => 'inventory',
						'on' => 'inventory_log.inventory_id=inventory.id',
						'type' => 'inner',
						'select' => Inventory_model::get_select(),
				),
				'user' => array(
						'join' => 'admin',
						'on' => 'inventory_log.user_id=admin.id',
						'type' => 'inner',
						'select' => Admin_model::get_select(),
				),
		);
	}
	
	public function get_filter($filter){
		if ($filter['inventory_id'] != null && $filter['inventory_id'] != 0){
			$this->db->where('inventory_id', $filter['inventory_id']);
		}
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'inventory_id' => 0,
				'user_id' => 0,
				'log_amount' => 0,
				'log_date' => "0000-00-00 00:00:00",
				'log_note' => "",
		);
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS inventory_log(
				id int(9) NOT NULL AUTO_INCREMENT,
				inventory_id int(9) NOT NULL,
				user_id int(9) NOT NULL,
				log_amount int(11) NOT NULL,
				log_date datetime NOT NULL,
				log_note varchar(255),
				PRIMARY KEY (id),
				FOREIGN KEY (inventory_id) REFERENCES inventory(id) ON DELETE CASCADE,
				FOREIGN KEY (user_id) REFERENCES admin(id) ON DELETE CASCADE)
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}