<?php
class MY_Model extends CI_Model{
	
	public $table;
	
	public function __construct($table = ""){
		parent::__construct();
		$this->table = $table;
	}
	
	public static function get_select(){
		return array();
	}
	public static function get_select_id(){
		return array();
	}
	public static function get_relation(){
		return array();
	}
	
	public function get_filter($filter){
		
	}
	
	public function is_empty(){
		$this->db->select('id');
		$this->db->from($this->table);
		$this->db->limit(1, 0);
		return (!$this->db->count_all_results() > 0);
	}
	
	public function exists($id){
		$this->db->select('id');
		$this->db->from($this->table);
		$this->db->where('id', $id);
		return ($this->db->count_all_results() > 0);
	}
	
	public function count_all(){
		return $this->db->count_all_results($this->table);
	}
	public function count_allf($filter){
		$this->get_filter($filter);
		return $this->count_all();
	}
	
	public function data_match($id, $data){
		$ret = true;
		$db_data = $this->get(array(), $id);
		foreach ($data as $key => $d){
			echo $key."=>".$d;
			if (!isset($db_data[$key])){
				$ret = false;
				break;
			}
			else{
				if ($d != $db_data[$key]){
					$ret = false;
					break;
				}
			}
		}
		return $ret;
	}
	
	public function join($join, $select = false){
		if ($select == false){
			$select = $this->get_select_id();
		}
		if ($join != null){
			foreach ($join as $j){
				$is_relation_ok = false;
				//if there is a '.' i have to go trough another relatin from another model
				if (strpos($j, '.') !== false){
					$explode = explode('.', $j);
					$class = ucfirst($explode[0])."_model"; 
					$relation = call_user_func_array(array(&$class, "get_relation"), array());
					//$tmp_sel = $class::get_relation();
					if (sizeof($relation) > 0 && isset($relation[$explode[1]])){
						$j = $explode[1];
						$is_relation_ok = true;
					}
				}
				//no '.' means i stay in relation for this mdel
				else{
					$relation = $this->get_relation();
					if (isset($relation[$j])){
						$is_relation_ok = true;
					}
				}
				//if it is ok
				if ($is_relation_ok){
					
					if (isset($relation[$j]['as'])){
						$relation[$j]['join'] .= " AS ".$relation[$j]['as'];
						$on = explode("=", $relation[$j]['on']);
						$on2 = explode(".", $on[1]);
						$relation[$j]['on'] = $on[0]."=".$relation[$j]['as'].".".$on2[1];
					}
					
					$this->db->join($relation[$j]['join'], $relation[$j]['on'], $relation[$j]['type']);
					if (isset($relation[$j]['select'])){
						$tmp_sel = $relation[$j]['select'];
					}
					else{
						$class = ucfirst($j)."_model";
						call_user_func(array($class, "get_select"));
					}
					$select = array_merge($select, $tmp_sel);
				}
			}
		}
		return $select;
	}
	
	public function get($join = array(), $id = false, $positive = true){
		$this->db->select($this->join($join, $this->get_select_id()));
		if ($id == false){
			$query = $this->db->get($this->table);
			return $query->result_array();
		}
		else{
			if ($positive){
				$query = $this->db->get_where($this->table, array($this->table.'.id =' => $id));
				return $query->row_array();
			}
			else{
				$query = $this->db->get_where($this->table, array($this->table.'.id !=' => $id));
				return $query->result_array();
			}
		}
	}
	
	public function get_list($join = array(), $limit_from, $limit){
		$this->db->select($this->join($join, $this->get_select_id()));
		$this->db->order_by("ID DESC");
		$query = $this->db->get($this->table, $limit, $limit_from);
		return $query->result_array();
	}
	public function get_listf($join = array(), $limit_from, $limit, $filter){
		$this->get_filter($filter);
		return $this->get_list($join, $limit_from, $limit);
	}
	
	public function save($data, $id = false){
		if ($id == false && (!isset($data['id']) || !$this->exists($data['id']))){
			$this->db->insert($this->table, $data);
			$ret = $this->db->insert_id();
		}
		else{
			if (isset($data['id'])){
				$id = $data['id'];
			}
			$this->db->where(array('id =' => $id));
			$this->db->update($this->table, $data);
			$ret = $id;
		}
		return $ret;
	}
	
	public function remove($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
}