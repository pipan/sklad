<?php
class Filter{
	
	protected $elements = array();
	protected $name;
	
	public function __construct($params){
		$this->name = $params['name'];
	}
	
	public function add_element($elem){
		$this->elements[$elem['name']] = $elem;
		if (!isset($elem['value'])){
			$this->elements[$elem['name']]['value'] = $elem['default_value'];
		}
	}
	
	public function set_element($name, $value){
		if (isset($this->elements[$name])){
			$this->elements[$name]['value'] = $value;
		}
	}
	
	public function set_default(){
		foreach ($this->elements as $e){
			$this->elements[$e['name']]['value'] = $e['default_value'];
		}
	} 
	
	public function get_data(){
		$data = array();
		foreach ($this->elements as $e){
			$data[$e['name']] = $e['value'];
		}
		$data['is_set'] = $this->is_set();
		return $data;
	}
	
	public function is_set(){
		foreach ($this->elements as $e){
			if ($e['value'] != $e['default_value']){
				return true;
			}
		}
		return false;
	}
	
	public function form_validation($validator){
		foreach ($this->elements as $e){
			$validator->set_rules($e['form_name'], $e['name'], '');
		}
	}
	
	public function load($session){
		$data = $this->get_data();
		if ($session->userdata($this->name) !== false){
			$data = array_merge($data, $session->userdata($this->name));
		}
		return $data;
	}
	
	public function save_post($input){
		foreach ($this->elements as $e){
			$this->set_element($e['name'], $input->post($e['form_name']));
		}
	}
	public function save($session){
		$data = $this->get_data();
		$session->set_userdata($this->name, $data);
	}
}