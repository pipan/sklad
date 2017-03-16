<?php
class CMS extends Part{
	
	public function __construct($module){
		parent::__construct($module, true, array('manage_supplier'));
		
		$this->load->library("form_validation");
		
		//header
		$this->data['menu'] = cms_header($this->session->userdata('user'));
		
		//menu
		$this->data['submenu'] = cms_subheader('supplier');
		
		$this->data['login'] = array(
				'link' => base_url()."cms/logout",
				'text' => "odhlásenie",
		);
		
		//layout
		$this->layouts = "layouts/cms2";
		
		$this->paging_session = "page_supplier";
	}
	
	public function index($page = null){
			
		//load filter library
		$this->load->library('filter', array('name' => 'supplier'));
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
		$this->data['page_last'] = ceil($this->supplier_model->count_allf($this->data['filter']) / $this->limit);
		$this->data['page_link'] = base_url()."cms/supplier/index/%p";
		
		$this->data['suppliers'] = $this->supplier_model->get_listf(array(), ($page - 1) * $this->limit, $this->limit, $this->data['filter']);
		
		$this->data['page_title'] = "Dodávatelia";
		
		$layout_data['title'] = "Trans Sklad - Dodávatelia";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function add($id = 0){
		if ($id == 0 || $this->supplier_model->exists($id)){
			$this->data['page_title'] = "Dodávateľ";
			
			if ($id == 0){
				$this->data['supplier'] = $this->supplier_model->get_dummy();
			}
			else{
				$this->data['supplier'] = $this->supplier_model->get(array(), $id);
			}
			
			$this->form_validation->set_rules('supplier_name', 'Názov', 'required|max_length[30]');
			$this->form_validation->set_rules('supplier_email', 'email', '');
			$this->form_validation->set_rules('supplier_phone', 'telefón', '');
			$this->form_validation->set_rules('supplier_web', 'web stránka', '');
			$this->form_validation->set_rules('supplier_contact_person', 'kontaktná osoba', '');
			$this->form_validation->set_rules('supplier_priority', 'priorita', '');
			
			if ($this->form_validation->run() !== false){
				//undo data
				if ($id > 0){
					//cookies undo info
					$data = array(
							'data' => $this->supplier_model->get(array(), $id),
					);
					$this->set_undo('supplier', 'update', $data);
					$this->set_message('Dodávateľ zmenený, <a href="'.base_url().'cms/supplier/undo">undo</a>');
				}
				
				$table_data = $this->_get_td_add($id);
				$inventory_id = $this->supplier_model->save($table_data, $id);
				 
				//undo data
				if($id == 0){
					$data = array(
							'data' => $this->supplier_model->get(array(), $privelage_id),
					);
					$this->set_undo('supplier', 'add', $data);
					$this->set_message('Nový dodávateľ pridaný, <a href="'.base_url().'cms/supplier/undo">undo</a>');
				}
				
				redirect("cms/supplier");
			}
			
			$layout_data['title'] = "Trans Sklad - Dodávateľ";
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
				'supplier_name' => $this->input->post('supplier_name'),
				'supplier_email' => $this->input->post('supplier_email'),
				'supplier_phone' => $this->input->post('supplier_phone'),
				'supplier_web' => $this->input->post('supplier_web'),
				'supplier_contact_person' => $this->input->post('supplier_contact_person'),
				'supplier_priority' => $this->input->post('supplier_priority'),
				'supplier_active' => 1,
		);
		
		return $table_data;
	}
	
	public function remove($id){
		if ($this->supplier_model->exists($id)){
			
			//set message and undo
			$data = array(
					'data' => $this->supplier_model->get(array(), $id, true),
			);
			$this->set_undo('supplier', 'remove', $data);
			$this->set_message('Dodávateľ odstránený <a href="'.base_url().'cms/supplier/undo">undo</a>');
	
			$this->supplier_model->remove($id);
			redirect("cms/supplier");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function undo(){
		if ($this->session->userdata('undo') !== false){
			$undo = $this->session->userdata('undo');
			switch($undo['type']){
				case 'supplier':
					switch($undo['action']){
						case 'update':
							$this->supplier_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Dodávateľ zmenený späť');
							break;
						case 'add':
							$this->supplier_model->remove($undo['data']['id']);
							$this->set_message('[Undo action] Dodávateľ odstránený');
							break;
						case 'remove':
							$this->supplier_model->restore($undo['data']['id']);
							$this->set_message('[Undo action] Dodávateľ obnovený');
							break;
					}
					break;
			}
		}
		redirect("cms/supplier");
	}
	
}