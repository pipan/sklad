<?php
class Remember_me_model extends MY_Model{
	
	public $select_login;
	
	public function __construct(){
		parent::__construct('remember_me');
	}
	
	public static function get_select(){
		return array('remember_me.user_id', 'remember_me.serial', 'remember_me.token', 'remember_me.last_login');
	}
	public static function get_select_id(){
		return array('remember_me.id', 'remember_me.user_id', 'remember_me.serial', 'remember_me.token', 'remember_me.last_login');
	}
	public static function get_relation(){
            return array(
                'user' => array(
                    'join' => 'admin',
                    'on' => 'remember_me.user_id=admin.id',
                    'type' => 'left',
                    'select' => Admin_model::get_select(),
                ),
            );
	}
	
	public function get_dummy(){
		return array(
				'id' => 0,
				'user_id' => 0,
				'token' => '',
				'serial' => '',
				'last_login' => date("Y-n-d H:i:s"),
		);
	}
	
	public function filter($filter = array()){
		
	}
	
	public function exists_full($user_id, $serial, $token){
		$this->db->where('user_id', $user_id);
		$this->db->where('serial', $serial);
		$this->db->where('token', $token);
		$count = $this->count_all();
		return ($count > 0);
	}
	public function exists_half($user_id, $serial){
		$this->db->where('user_id', $user_id);
		$this->db->where('serial', $serial);
		$count = $this->count_all();
		return ($count > 0);
	}
	
	public function get_full($user_id, $serial, $token){
		$this->db->select($this->get_select_id());
		$this->db->from($this->table);
		$this->db->where('user_id', $user_id);
		$this->db->where('serial', $serial);
		$this->db->where('token', $token);
                $query = $this->db->get();
		return $query->row_array();
	}
	
	public function delete_full($user_id, $serial, $token){
		$this->db->delete($this->table, array('user_id' => $user_id, 'serial' => $serial, 'token' => $token));
	}
	public function delete_by_user($user_id){
		$this->db->delete($this->table, array('user_id' => $user_id));
	}
	
	public function _create_table(){
            $this->db->query("CREATE TABLE IF NOT EXISTS remember_me(
                id int(9) NOT NULL AUTO_INCREMENT,
                user_id int(9) NOT NULL,
                serial varchar(20) NOT NULL,
                token varchar(20) NOT NULL,
                last_login datetime NOT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (user_id) REFERENCES admin(id) ON DELETE CASCADE)
                COLLATE utf8_general_ci,
                ENGINE innoDB");
	}
}