<?php
class CMS extends Part{
	
	public function __construct($module){
		parent::__construct($module, true, array('manage_user'));
		
		$this->load->library("form_validation");
		$this->load->library("Bcrypt");
		
		//header
		$this->data['menu'] = cms_header($this->session->userdata('user'));
		
		//menu
		$this->data['submenu'] = cms_subheader('user');
		
		$this->data['login'] = array(
				'link' => base_url()."cms/logout",
				'text' => "odhlásenie",
		);
		
		//layout
		$this->layouts = "layouts/cms2";
		
		$this->paging_session = "page_user";
	}
	
	public function password_validation(){
		if ($user = $this->admin_model->get_login_by_id($this->session->userdata('admin_id'))){
			if ($this->bcrypt->check_password($this->input->post('old_password').$user['salt'], $user['password'])){
				return true;
			}
		}
		$this->form_validation->set_message('password_validation', 'Nesprávne staré heslo');
		return false;
	}
	
	public function _login($name){
		$login = $this->admin_model->get_login($name);
		$this->session->set_userdata('admin_id', $login['id']);
		redirect("cms/static");
	}
	
	public function index($page = 1){
		
		//load filter library
		$this->load->library('filter', array('name' => 'user_filter'));
		//set elements of filter
		$this->filter->add_element(array('name' => 'remove', 'default_value' => 0, 'form_name' => 'filter_remove'));
		
		//save filter if needed
		$this->filter->form_validation($this->form_validation);
		$this->form_validation->set_rules('filter', 'filter', 'required');
		
		if ($this->form_validation->run() !== false){
			$this->session->set_userdata($this->paging_session, false);
			if ($this->input->post('filter') == 'filter'){
		
				$this->filter->save_post($this->input);
			}
			else{
				$this->filter->set_default();
			}
			$this->filter->save($this->session);
			$this->data['filter'] = $this->filter->get_data();
		}
		//try to load filter
		else{
			$this->data['filter'] = $this->filter->load($this->session);
		}
		
		if ($page == null && $this->session->userdata($this->paging_session) !== false){
			$page = $this->session->userdata($this->paging_session);
		}
		else if ($page == null){
			$page = 1;
		}
		$this->session->set_userdata($this->paging_session, $page);
		$this->data['page'] = $page;
		$this->data['page_offset'] = 2;
		$this->data['page_last'] = ceil($this->admin_model->count_allf($this->data['filter']) / $this->limit);
		$this->data['page_link'] = base_url()."/cms/user/index/%p";
		
		$this->data['users'] = $this->admin_model->get_listf(array('privelage'), ($page - 1) * $this->limit, $this->limit, $this->data['filter']);
		
		$this->data['page_title'] = "Používatelia";
                
		$layout_data['title'] = "Trans Sklad - Používatelia";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function add(){
		//form validation rules
		$this->form_validation->set_rules('name', 'meno', 'required|max_length[30]');
		$this->form_validation->set_rules('surname', 'priezvisko', 'required|max_length[30]');
		$this->form_validation->set_rules('email', 'email', 'required|max_length[100]');
		$this->form_validation->set_rules('password', 'heslo', 'required|max_length[30]');
		$this->form_validation->set_rules('password_repeat', 'heslo zopakované', 'required|max_length[30]');
		$this->form_validation->set_rules('privelage', 'oprávnenie', 'required|callback_id_exists[privelage_model]');
		$this->form_validation->set_rules('email_notification', 'upozornenei na e-mail', '');
			
		//form validation
		if ($this->form_validation->run() !== false ){
			$table_data = $this->_get_td_add();
			
			$id = $this->admin_model->save($table_data);
			
			$data = array(
					'data' => $this->admin_model->get(array(), $id),
			);
			$this->set_undo('user', 'add', $data);
			$this->set_message('Používatel pridaný <a href="'.base_url().'cms/user/undo">undo</a>');
			
			redirect("cms/user");
		}
		
		//$this->data['profile'] = $this->admin_model->get(array(), $this->session->userdata('admin_id'));
		$this->data['user'] = $this->admin_model->get_dummy();
		$this->data['privelages'] = $this->privelage_model->get();
		
		$this->data['page_title'] = "Používatelia";
		
		$layout_data['title'] = "Trans Sklad - Používatelia";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body_new", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function _get_td_add(){
		$salt = random_string('alnum', 16);
		$uid = $this->admin_model->get_uid();
		
		$table_data = array(
				'uid' => $uid,
				'email' => $this->input->post('email'),
				'admin_name' => $this->input->post('name'),
				'admin_surname' => $this->input->post('surname'),
				'admin_nickname' => $this->input->post('nickname'),
				'salt' => $salt,
				'password' => $this->bcrypt->hash_password($this->input->post('password').$salt),
				'privelage_id' => $this->input->post('privelage'),
				'email_notification' => $this->input->post('email_notification'),
				'active' => 1,
		);
		
		return $table_data;
	}
	
	public function edit($id){
		if ($this->admin_model->exists($id)){
			$this->form_validation->set_rules('name', 'meno', 'required|max_length[30]');
			$this->form_validation->set_rules('surname', 'priezvisko', 'required|max_length[30]');
			$this->form_validation->set_rules('email', 'email', 'required|max_length[100]');
			$this->form_validation->set_rules('privelage', 'oprávnenie', 'required|callback_id_exists[privelage_model]');
			$this->form_validation->set_rules('email_notification', 'upozornenei na e-mail', '');
				
			//form validation
			if ($this->form_validation->run() !== false ){
				$table_data = $this->_get_td_edit();
					
				$data = array(
						'data' => $this->admin_model->get(array(), $id),
				);
				$this->set_undo('user', 'update', $data);
				$this->set_message('Používatel upravený <a href="'.base_url().'cms/user/undo">undo</a>');
				
				$id = $this->admin_model->save($table_data, $id);
					
				redirect("cms/user");
			}
			
			//$this->data['profile'] = $this->admin_model->get(array(), $this->session->userdata('admin_id'));
			$this->data['user'] = $this->admin_model->get(array('privelage'), $id);
			$this->data['privelages'] = $this->privelage_model->get();
			
			$this->data['page_title'] = "Používatelia";
			
			$layout_data['title'] = "Trans Sklad - Používatelia";
			$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
			$layout_data['body'] = $this->load->view("cms/body_edit", $this->data, true);
			$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
			$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
			$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
				
			$this->model->load->view($this->layouts, $layout_data);
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function _get_td_edit(){
		$table_data = array(
				'email' => $this->input->post('email'),
				'admin_name' => $this->input->post('name'),
				'admin_surname' => $this->input->post('surname'),
				'admin_nickname' => $this->input->post('nickname'),
				'privelage_id' => $this->input->post('privelage'),
				'email_notification' => $this->input->post('email_notification'),
		);
	
		return $table_data;
	}
	
	public function password($id){
		if ($this->admin_model->exists($id)){
			//form validation rules
			$this->form_validation->set_rules('new_password', 'nové heslo', 'required');
			$this->form_validation->set_rules('repeat_password', 'zopakuj heslo', 'required|matches[new_password]');
			
			if ($this->form_validation->run() == true){
				$table_data = $this->_get_td_password();
					
				$data = array(
						'data' => $this->admin_model->get(array(), $id),
				);
				$this->set_undo('user', 'update', $data);
				$this->set_message('Používatel upravený <a href="'.base_url().'cms/user/undo">undo</a>');
				
				$id = $this->admin_model->save($table_data, $id);
					
				redirect("cms/user");
			}
			
			$this->data['user'] = $this->admin_model->get(array('privelage'), $id);
			
			$this->data['page_title'] = "Používatelia";
			
			$layout_data['title'] = "Trans Sklad - Používatelia";
			$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
			$layout_data['body'] = $this->load->view("cms/body_password", $this->data, true);
			$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
			$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
			$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
				
			$this->model->load->view($this->layouts, $layout_data);
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function _get_td_password(){
		$salt = random_string('alnum', 16);
		
		$table_data = array(
				'salt' => $salt,
				'password' => $this->bcrypt->hash_password($this->input->post('new_password').$salt),
		);
	
		return $table_data;
	}
	
	public function remove($id){
		if ($this->admin_model->exists($id)){
			$data = array(
					'data' => $this->admin_model->get(array(), $id),
			);
			$this->set_undo('user', 'remove', $data);
			$this->set_message('Používatel odstránený <a href="'.base_url().'cms/user/undo">undo</a>');
			
			$this->admin_model->remove($id);
				
			redirect("cms/user");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function force_remove($id){
		if ($this->admin_model->exists($id)){
			$this->admin_model->force_remove($id);
	
			redirect("cms/user");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function restore($id){
		if ($this->admin_model->exists($id)){
			$data = array(
					'data' => $this->admin_model->get(array(), $id),
			);
			$this->set_undo('user', 'remove', $data);
			$this->set_message('Používatel obnovený <a href="'.base_url().'cms/user/undo">undo</a>');
				
			$this->admin_model->restore($id);
			
			redirect("cms/user");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function undo(){
		if ($this->session->userdata('undo') !== false){
			$undo = $this->session->userdata('undo');
			switch($undo['type']){
				case 'user':
					switch($undo['action']){
						case 'update':
							$this->admin_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Používatel zmenený späť');
							break;
						case 'add':
							$this->admin_model->force_remove($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Používatel odstránený späť');
							break;
						case 'remove';
							$this->admin_model->restore($undo['data']['id']);
							$this->set_message('[Undo action] Používatel obnovený');
							break;
						case 'restore':
							$this->admin_model->remove($undo['data']['id']);
							$this->set_message('[Undo action] Používatel odstránený');
							break;
					}
					break;
			}
		}
		redirect("cms/user");
	}
	
}