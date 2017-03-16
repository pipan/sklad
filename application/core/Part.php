<?php
class Part extends CI_Controller{
	
	//module name this part was created by
	protected $module;
	protected $require_login;
	protected $require_privelage;
	
	protected $data;
	protected $limit;
	
	protected $paging_session;
	
	public function __construct($module, $require_login = false, $require_privelage = array()){
            parent::__construct($module->module_name);

            $this->module = $module;
            $this->require_login = $require_login;
            $this->require_privelage = $require_privelage;

            $this->limit = 30;

            //working with messages
            if($this->session->userdata('message') !== false){
                    $this->data['message'] = $this->session->userdata('message');
                    $this->session->unset_userdata('message'); 
            } 

            if (!$this->module_lib->required($this->module->module_name)){
                die($this->module_lib->get_error_message());
            }
	}
	
	public function get_require_login(){
		return $this->require_login;
	}
	
	public function get_require_privelage(){
		return $this->require_privelage;
	}
	
	public function _error_message($message){
		$this->data['error_message'] = $message;
		$template_data['title'] = "CMS chyba";
		
		$template_data['header'] = $this->load->view("cms/header", $this->data, true);
		$template_data['body'] = $this->load->view("cms/body_error", $this->data, true);
		$template_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$template_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$template_data['footer'] = $this->load->view("cms/footer", $this->data, true);
		
		//load template
		$this->module->load->view("layouts/cms2", $template_data);
	}
	
	public function id_exists($value, $model){
		if ($this->$model->exists($value)){
			return true;
		}
		$this->form_validation->set_message('id_exists', 'id doesn\'t belongs to database');
		return false;
	}
	public function id_exists_zero($value, $model){
		if ($value == 0 || $this->$model->exists($value)){
			return true;
		}
		$this->form_validation->set_message('id_exists', 'id doesn\'t belongs to database and is different from zero');
		return false;
	}
	
	public function set_message($message){
		$this->session->set_userdata('message', $message);
	}
	public function set_undo($type, $action, $data){
		$data['type'] = $type;
		$data['action'] = $action;
		$this->session->set_userdata('undo', $data);
	}
	
	public function get_filter($name){
		if ($this->session->userdata($name) !== false){
			$this->data['filter'] = $this->session->userdata($name);
			return true; 
		}
		return false;
	}
	public function set_filter($name, $data){
		$this->session->set_userdata($name, $data);
	}
}