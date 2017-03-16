<?php
class Privelage_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('privelage');
	}
	
	public static function get_select(){
		return array('privelage.privelage_name', 'privelage.manage_user', 'privelage.manage_privelage', 'privelage.manage_inventory', 'privelage.manage_supplier', 'privelage.manage_order', 'privelage.manage_settings');
	}
	public static function get_select_id(){
		return array('privelage.id', 'privelage.privelage_name', 'privelage.manage_user', 'privelage.manage_privelage', 'privelage.manage_inventory', 'privelage.manage_supplier', 'privelage.manage_order', 'privelage.manage_settings');
	}
	
	public function get_dummy(){
		return array(
                    'id' => 0,
                    'privelage_name' => "",
                    'manage_user' => 0,
                    'manage_privelage' => 0,
                    'manage_inventory' => 0,
                    'manage_supplier' => 0,
                    'manage_order' => 0,
                    'manage_settings' => 0,
		);
	}
	
	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS privelage(
				id int(9) NOT NULL AUTO_INCREMENT,
				privelage_name varchar(30) NOT NULL,
				manage_user tinyint(1) NOT NULL,
				manage_privelage tinyint(1) NOT NULL,
				manage_inventory tinyint(1) NOT NULL,
				manage_supplier tinyint(1) NOT NULL,
				manage_order tinyint(1) NOT NULL,
                                manage_settings tinyint(1) NOT NULL,
				PRIMARY KEY (id))
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}