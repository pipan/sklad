<?php
class Admin_model extends MY_Model{

	public $select_login;

	public function __construct(){
		parent::__construct('admin');
	}

	public static function get_select(){
		return array('admin.uid', 'admin.admin_nickname', 'admin.email', 'admin.admin_name', 'admin.admin_surname', 'admin.privelage_id', 'admin.email_notification', 'admin.active');
	}
	public static function get_select_id(){
		return array('admin.uid', 'admin.id', 'admin.admin_nickname', 'admin.email', 'admin.admin_name', 'admin.admin_surname', 'admin.password', 'admin.salt', 'admin.privelage_id', 'admin.email_notification', 'admin.active');
	}
	public static function get_relation(){
		return array(
				'privelage' => array(
						'join' => 'privelage',
						'on' => 'admin.privelage_id=privelage.id',
						'type' => 'left',
						'select' => Privelage_model::get_select(),
				),
		);
	}

	public function get_dummy(){
		return array(
				'id' => 0,
				'uid' => "",
				'email' => "",
				'admin_nickname' => "",
				'admin_name' => "",
				'admin_surname' => "",
				'privelage' => 0,
				'email_notification' => 0,
				'active' => 0,
		);
	}

	public function get_filter($filter){
		if (!isset($filter['remove']) || $filter['remove'] == null || $filter['remove'] != 1){
			$this->db->where('active', 1);
		}
	}

	public function get_uid(){
		$user = 1;
		while ($user != null){
			$uid = random_string('numeric', 3);
			$user = $this->get_login_by_uid($uid);
		}
		return $uid;
	}

	public function get_login($nickname = ""){
		$this->db->select($this->get_select_id());
		$query = $this->db->get_where($this->table, array('admin_nickname =' => $nickname));
		$row = $query->row_array();
		if (isset($row['id']) && $row['id'] != null){
			return $this->get(array('privelage'), $row['id']);
		}
		return null;
	}

	public function get_login_by_uid($uid){
		$this->db->select($this->get_select_id());
		$query = $this->db->get_where($this->table, array('uid =' => $uid));
		$row = $query->row_array();
		if ($row != null && $row['id'] != null){
			return $this->get(array('privelage'), $row['id']);
		}
		return null;
	}

	public function get_login_by_id($id){
		$this->db->select($this->get_select_id());
		$query = $this->db->get_where($this->table, array('id =' => $id));
		return $query->row_array();
	}

	public function get_listf($join = array(), $limit_from, $limit, $filter){
		$this->db->order_by("admin_name ASC, admin_surname ASC");
		return parent::get_listf($join, $limit_from, $limit, $filter);
	}

	public function get_notify(){
		$this->db->select($this->get_select());
		$query = $this->db->get_where($this->table, array('email_notification =' => 1));
		return $query->result_array();
	}

	public function remove($id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('active' => 0));
	}

	public function restore($id){
		$this->db->where('id', $id);
		$this->db->update($this->table, array('active' => 1));
	}

	public function force_remove($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

	public function _create_table(){
		$this->db->query("CREATE TABLE IF NOT EXISTS admin(
				id int(9) NOT NULL AUTO_INCREMENT,
				uid varchar(3) NOT NULL,
				email varchar(100) NOT NULL,
				admin_name varchar(30),
				admin_surname varchar(30),
				salt varchar(16) NOT NULL,
				password varchar(128) NOT NULL,
				admin_nickname varchar(50) NOT NULL,
				privelage_id int(9),
				email_notification tinyint(1) NOT NULL,
				active tinyint(1) NOT NULL,
				PRIMARY KEY (id),
				FOREIGN KEY (privelage_id) REFERENCES privelage(id) ON DELETE SET NULL)
				COLLATE utf8_general_ci,
				ENGINE innoDB");
	}
}
