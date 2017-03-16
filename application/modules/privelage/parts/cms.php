<?php
class CMS extends Part{
	
	public function __construct($module){
		parent::__construct($module, true, array('manage_privelage'));
		
		$this->load->library("form_validation");
		$this->load->library("Bcrypt");
		
		//header
		$this->data['menu'] = cms_header($this->session->userdata('user'));
		
		//menu
		$this->data['submenu'] = cms_subheader('privelage');
		
		$this->data['login'] = array(
				'link' => base_url()."cms/logout",
				'text' => "odhlásenie",
		);
		
		//layout
		$this->layouts = "layouts/cms2";
		
		$this->paging_session = "page_privelage";
	}
	
	public function index($page = null){
		
		if ($page == null && $this->session->userdata($this->paging_session) !== false){
			$page = $this->session->userdata($this->paging_session);
		}
		else if ($page == null){
			$page = 1;
		}
		$this->session->set_userdata($this->paging_session, $page);
			
		$this->data['page'] = $page;
		$this->data['page_offset'] = 3;
		$this->data['page_last'] = ceil($this->privelage_model->count_all() / $this->limit);
		$this->data['page_link'] = base_url()."cms/privelage/index/%p";
		
		$this->data['privelages'] = $this->privelage_model->get_list(array(), ($page - 1) * $this->limit, $this->limit);
		
		$this->data['page_title'] = "Oprávnenia";
		
		$layout_data['title'] = "Trans Sklad - Oprávnenia";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function add($id = 0){
		if ($id == 0 || $this->privelage_model->exists($id)){
			$this->data['page_title'] = "Oprávnenia";
			
			if ($id == 0){
				$this->data['privelage'] = $this->privelage_model->get_dummy();
			}
			else{
				$this->data['privelage'] = $this->privelage_model->get(array(), $id);
			}
			
			$this->form_validation->set_rules('privelage_name', 'Názov', 'required|max_length[30]');
			$this->form_validation->set_rules('manage_user', 'spravovať používatela', '');
			$this->form_validation->set_rules('manage_privelage', 'spravovať oprávnenia', '');
			$this->form_validation->set_rules('manage_inventory', 'spravovať inventár', '');
			$this->form_validation->set_rules('manage_supplier', 'spravovať dodávateľov', '');
			
			if ($this->form_validation->run() !== false){
				//undo data
				if ($id > 0){
					//cookies undo info
					$data = array(
							'data' => $this->privelage_model->get(array(), $id),
					);
					$this->set_undo('privelage', 'update', $data);
					$this->set_message('Oprávnenie zmenené, <a href="'.base_url().'cms/privelage/undo">undo</a>');
				}
				
				$table_data = $this->_get_td_add($id);
				$privelage_id = $this->privelage_model->save($table_data, $id);
				 
				//undo data
				if($id == 0){
					$data = array(
							'data' => $this->privelage_model->get(array(), $privelage_id),
					);
					$this->set_undo('privelage', 'add', $data);
					$this->set_message('Nové oprávnenie pridané, <a href="'.base_url().'cms/privelage/undo">undo</a>');
				}
				
				redirect("cms/privelage");
			}
			
			$layout_data['title'] = "Trans Sklad - Oprávnenia";
			$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
			$layout_data['body'] = $this->load->view("cms/body_add", $this->data, true);
			$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
			$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
			$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
				
			$this->model->load->view($this->layouts, $layout_data);
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function _get_td_add($id){
		$table_data = array(
				'privelage_name' => $this->input->post('privelage_name'),
				'manage_user' => $this->input->post('manage_user') !== false,
				'manage_privelage' => $this->input->post('manage_privelage') !== false,
				'manage_inventory' => $this->input->post('manage_inventory') !== false,
				'manage_supplier' => $this->input->post('manage_supplier') !== false,
				'manage_order' => $this->input->post('manage_order') !== false,
		);
		
		return $table_data;
	}
	
	public function remove($id){
		if ($this->privelage_model->exists($id)){
			
			//set message and undo
			$data = array(
					'data' => $this->privelage_model->get(array(), $id, true),
			);
			$this->set_undo('privelage', 'remove', $data);
			$this->set_message('Oprávnenie odstránené <a href="'.base_url().'cms/privelage/undo">undo</a>');
	
			$this->privelage_model->remove($id);
			redirect("cms/privelage");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function undo(){
		if ($this->session->userdata('undo') !== false){
			$undo = $this->session->userdata('undo');
			switch($undo['type']){
				case 'privelage':
					switch($undo['action']){
						case 'update':
							$this->privelage_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Oprávnenie zmenené späť');
							break;
						case 'add':
							$this->privelage_model->remove($undo['data']['id']);
							$this->set_message('[Undo action] Oprávnenie odstránené');
							break;
						case 'remove':
							$this->privelage_model->save($undo['data']);
							$this->set_message('[Undo action] Oprávnenie pridané späť');
							break;
					}
					break;
			}
		}
		redirect("cms/privelage");
	}
	
}