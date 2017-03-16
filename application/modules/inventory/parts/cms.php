<?php
class CMS extends Part{
	
	public function __construct($module){
		parent::__construct($module, true, array('manage_inventory'));
		
		$this->load->library("form_validation");
		$this->load->library("Bcrypt");
		$this->load->library("History");
		
		//header
		$this->data['menu'] = cms_header($this->session->userdata('user'));
		
		//menu
		$this->data['submenu'] = cms_subheader('inventory');
		
		$this->data['login'] = array(
				'link' => base_url()."cms/logout",
				'text' => "odhlásenie",
		);
		
		//layout
		$this->layouts = "layouts/cms2";
		
		$this->paging_session = "page_inventory";
	}
	
	public function index($page = null){
		
		//load filter library
		$this->load->library('filter', array('name' => 'inventory'));
		//set elements of filter
		$this->filter->add_element(array('name' => 'search', 'default_value' => "", 'form_name' => 'filter_search'));
		$this->filter->add_element(array('name' => 'remove', 'default_value' => 0, 'form_name' => 'filter_remove'));
		$this->filter->add_element(array('name' => 'category_id', 'default_value' => null, 'form_name' => 'filter_category_id'));
		
		//save filter if needed
		$this->filter->form_validation($this->form_validation);
		$this->form_validation->set_rules('filter', 'filter', 'required');
		
		if ($this->form_validation->run() !== false){
			$this->session->set_userdata($this->paging_session, false);
			//filter
			if ($this->input->post('filter') == 'filter'){
		
				$this->filter->save_post($this->input);
			}
			//reset filter
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
		$this->data['filter']['categories'] = $this->inventory_category_model->get();
		
		if ($page == null && $this->session->userdata($this->paging_session) !== false){
			$page = $this->session->userdata($this->paging_session);
		}
		else if ($page == null){
			$page = 1;
		}
		$this->session->set_userdata($this->paging_session, $page);
		$this->data['page'] = $page;
		$this->data['page_offset'] = 2;
		$this->data['page_last'] = ceil($this->inventory_model->count_allf($this->data['filter']) / $this->limit);
		$this->data['page_link'] = base_url()."cms/inventory/index/%p";
		
		$this->data['inventories'] = $this->inventory_model->get_listf(array(), ($page - 1) * $this->limit, $this->limit, $this->data['filter']);
		
		$this->data['page_title'] = "Inventár";
		
		$layout_data['title'] = "Trans Sklad - Inventár";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function log($page = 1){
		//load filter library
		$this->load->library('filter', array('name' => 'inventory-log'));
		//set elements of filter
		$this->filter->add_element(array('name' => 'inventory_id', 'default_value' => 0, 'form_name' => 'filter_inventory'));
		
		//save filter if needed
		$this->form_validation->set_rules('filter', 'filter', 'required');
		$this->filter->form_validation($this->form_validation);
		
		if ($this->form_validation->run() !== false){
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
		
		$this->data['page'] = $page;
		$this->data['page_offset'] = 3;
		$this->data['page_last'] = ceil($this->inventory_log_model->count_allf($this->data['filter']) / $this->limit);
		$this->data['page_link'] = base_url()."cms/inventory/log/%p";
		
		$this->data['filter']['inventories'] = $this->inventory_model->get();
		
		$this->data['log'] = $this->inventory_log_model->get_listf(array('inventory', 'user'), ($page - 1) * $this->limit, $this->limit, $this->data['filter']);
		
		$this->data['page_title'] = "Inventár log";
		
		$layout_data['title'] = "Trans Sklad - Inventár log";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body_log", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function add($id = 0){
		if ($id == 0 || $this->inventory_model->exists($id)){
			$this->data['style'] = array('tab_style');
			$this->data['jscript'] = array('Tab');
			
			$this->data['page_title'] = "Inventár";
			
			$this->data['suppliers'] = $this->supplier_model->get_active();
			$this->data['categories'] = $this->inventory_category_model->get();
			
			if ($id == 0){
				$this->data['inventory'] = $this->inventory_model->get_dummy();
			}
			else{
				$this->data['inventory'] = $this->inventory_model->get(array(), $id);
			}
			$this->data['iis'] = array();
			foreach ($this->data['suppliers'] as $s){
				$this->data['iis'][$s['id']] = $this->inventory_in_supplier_model->get_uniq($id, $s['id']);
			}
			
			$this->form_validation->set_rules('inventory_name', 'Názov', 'required|max_length[100]');
			$this->form_validation->set_rules('min_amount', 'minimálne množtvo', 'required|greater_then[0]');
			$this->form_validation->set_rules('location', 'miesto', '');
			$this->form_validation->set_rules('description', 'popis', '');
			$this->form_validation->set_rules('category_id', 'kategoria', '');
			foreach ($this->data['suppliers'] as $s){
				$this->form_validation->set_rules('sup_'.$s['id'].'_code', 'kód pre '.$s['supplier_name'], '');
				$this->form_validation->set_rules('sup_'.$s['id'].'_info', 'info pre '.$s['supplier_name'], '');
				$this->form_validation->set_rules('sup_'.$s['id'].'_price', 'cena pre '.$s['supplier_name'], '');
			}
			
			if ($this->form_validation->run() !== false){
				//supplier undo data
				$history_data = $this->inventory_in_supplier_model->get_by_inventory($id);
				$this->history->set("inventory-supplier", $history_data, true);
				
				//undo data
				if ($id > 0){
					//cookies undo info
					$data = array(
							'data' => $this->inventory_model->get(array(), $id),
					);
					$this->set_undo('inventory', 'update', $data);
					$this->set_message('Inventár zmenený, <a href="'.base_url().'cms/inventory/undo">undo</a>');
				}
				
				$table_data = $this->_get_td_add($id);
				$inventory_id = $this->inventory_model->save($table_data, $id);
				
				//remove old suppliers
				$this->inventory_in_supplier_model->remove_by_inventory($inventory_id);
				
				//suppliers
				foreach ($this->data['suppliers'] as $s){
					$table_data = $this->_get_td_add_supplier($inventory_id, $s['id']);
					$this->inventory_in_supplier_model->save($table_data);
				}
				 
				//undo data
				if($id == 0){
					$data = array(
							'data' => $this->inventory_model->get(array(), $inventory_id),
					);
					$this->set_undo('inventory', 'add', $data);
					$this->set_message('Nový inventár pridaný, <a href="'.base_url().'cms/inventory/undo">undo</a>');
				}
				
				redirect("cms/inventory");
			}
			
			$layout_data['title'] = "Trans Sklad - Inventár";
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

	public function edit($id){
		if ($this->inventory_model->exists($id)){
			
			$this->data['page_title'] = "Inventár";
			
			$this->data['inventory'] = $this->inventory_model->get(array(), $id);
			
			$this->form_validation->set_rules('amount', 'mnozstvo', 'required');
			$this->form_validation->set_rules('note', 'poznámka', '');
				
			if ($this->form_validation->run() !== false){
				
				//undo data
				//cookies undo info
				$data = array(
						'data' => $this->inventory_model->get(array(), $id),
				);
				$this->set_undo('inventory', 'edit', $data);
				$this->set_message('Inventár zmenený, <a href="'.base_url().'cms/inventory/undo">undo</a>');
			
				$amount = $this->input->post("amount");
				$this->inventory_model->set_amount($this->data['inventory']['amount'] + $amount, $id);
				
				//log
				$user = $this->session->userdata("admin_id");
				$datetime = date("Y-n-d-H-i-s");
				$table_data = array(
						'inventory_id' => $id,
						'user_id' => $user,
						'log_amount' => $amount,
						'log_date' => $datetime,
						'log_note' => $this->input->post("note"),
				);
				$log_ids[0]['id'] = $this->inventory_log_model->save($table_data);
				$this->history->set("inventory-log", $log_ids, true);
			
				redirect("cms/inventory");
			}
			
			$layout_data['title'] = "Trans Sklad - Inventár";
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
	
	public function _get_td_add($id){
		$table_data = array(
				'inventory_name' => $this->input->post('inventory_name'),
				'min_amount' => $this->input->post('min_amount'),
				'category_id' => $this->input->post('category_id'),
				'location' => $this->input->post('location'),
				'description' => $this->input->post('description'),
				'inventory_active' => 1,
		);
		if ($id == 0){
			$table_data['amount'] = 0;
		}
		
		return $table_data;
	}
	public function _get_td_add_supplier($inventory_id, $supplier_id){
		$table_data = array(
				'inventory_id' => $inventory_id,
				'supplier_id' => $supplier_id,
				'info' => $this->input->post('sup_'.$supplier_id.'_info'),
				'code' => $this->input->post('sup_'.$supplier_id.'_code'),
				'price' => convert_float($this->input->post('sup_'.$supplier_id.'_price')),
		);
		
		return $table_data;
	}
	
	public function remove($id){
		if ($this->inventory_model->exists($id)){
			
			//set message and undo
			$data = array(
					'data' => $this->inventory_model->get(array(), $id, true),
			);
			$this->set_undo('inventory', 'remove', $data);
			$this->set_message('Inventár odstránený <a href="'.base_url().'cms/inventory/undo">undo</a>');
	
			$this->inventory_model->remove($id);
			redirect("cms/inventory");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function category($page = 1){
		
		$this->data['page'] = $page;
		$this->data['page_offset'] = 3;
		$this->data['page_last'] = ceil($this->inventory_category_model->count_all() / $this->limit);
		$this->data['page_link'] = base_url()."cms/inventory/category/%p";
		
		$this->data['categories'] = $this->inventory_category_model->get_list(array(), ($page - 1) * $this->limit, $this->limit);
		
		$this->data['page_title'] = "Inventár kategórie";
		
		$layout_data['title'] = "Trans Sklad - Inventár kategórie";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body_category", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function category_add($id = 0){
		if ($id == 0 || $this->inventory_category_model->exists($id)){
				
			$this->data['page_title'] = "Inventár categória";
				
			if ($id == 0){
				$this->data['category'] = $this->inventory_category_model->get_dummy();
			}
			else{
				$this->data['category'] = $this->inventory_category_model->get(array(), $id);
			}
				
			$this->form_validation->set_rules('category_name', 'Názov', 'required|max_length[50]');
				
			if ($this->form_validation->run() !== false){
		
				//undo data
				if ($id > 0){
					//cookies undo info
					$data = array(
							'data' => $this->inventory_category_model->get(array(), $id),
					);
					$this->set_undo('category', 'update', $data);
					$this->set_message('Kategória zmenená, <a href="'.base_url().'cms/inventory/undo">undo</a>');
				}
		
				$table_data = $this->_get_td_category_add($id);
				$inventory_id = $this->inventory_category_model->save($table_data, $id);
					
				//undo data
				if($id == 0){
					$data = array(
							'data' => $this->inventory_category_model->get(array(), $inventory_id),
					);
					$this->set_undo('category', 'add', $data);
					$this->set_message('Nová kategória pridaná, <a href="'.base_url().'cms/inventory/undo">undo</a>');
				}
		
				redirect("cms/inventory/category");
			}
				
			$layout_data['title'] = "Trans Sklad - Inventár kategória";
			$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
			$layout_data['body'] = $this->load->view("cms/body_category_add", $this->data, true);
			$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
			$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
			$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
		
			$this->model->load->view($this->layouts, $layout_data);
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	public function _get_td_category_add($id){
		$table_data = array(
				'category_name' => $this->input->post('category_name'),
		);
	
		return $table_data;
	}
	
	public function category_remove($id){
		if ($this->inventory_category_model->exists($id)){

			$inv = $this->inventory_model->get_by_category($id);
			$history_data = array();
			$j = 0;
			foreach ($inv as $i){
				$history_data[$j]['id'] = $i['id'];
				$j++;
			}
			$this->history->set("inventory-category", $history_data, true);
			
			//set message and undo
			$data = array(
					'data' => $this->inventory_category_model->get(array(), $id, true),
			);
			$this->set_undo('category', 'remove', $data);
			$this->set_message('Kategória odstránená <a href="'.base_url().'cms/inventory/undo">undo</a>');
	
			$this->inventory_category_model->remove($id);
			redirect("cms/inventory/category");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function export(){
		$this->data['page_title'] = "Inventár Export";
			
		$this->data['categories'] = $this->inventory_category_model->get();
			
		$this->form_validation->set_rules('category_id', 'Kategória', 'required');
		
		if ($this->form_validation->run() !== false){
			$this->load->library('PHPExcel');
			
			$category = $this->inventory_category_model->get(array(), $this->input->post("category_id"));
			$export = $this->inventory_model->get_by_category($this->input->post("category_id"));
			
			$name = "export_".url_title(convert_accented_characters($category['category_name'])).".xlsx";
			
			$this->set_message('Export úspešný <a href="'.base_url().'cms/inventory/download/'.$name.'" target="_blank">download</a>');
			
			// Set document properties
			$this->phpexcel->getProperties()->setCreator("Trans Sklad")
				->setLastModifiedBy("Trans Sklad")
				->setTitle("Translata Export")
				->setSubject("Translata Export")
				->setDescription("Export");
			
			$this->phpexcel->setActiveSheetIndex(0);
				
			$i = 1;
			foreach ($export as $data){
				$this->phpexcel->getActiveSheet()->setCellValue("A".$i, $data['inventory_name']);
				$this->phpexcel->getActiveSheet()->setCellValue("B".$i, $data['amount']);
				$i++;
			}
				
			// Save Excel 2007 file
				
			$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
			$objWriter->save("exports/".$name);
				
			redirect("cms/inventory");
		}
			
		$layout_data['title'] = "Trans Sklad - Inventár Export";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body_export", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function download($filename){
		if (file_exists('exports/'.$filename)){
			$quoted = sprintf('"%s"', addcslashes(basename($filename), '"\\'));
			$size   = filesize('exports/'.$filename);
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $quoted);
			header('Content-Transfer-Encoding: binary');
			header('Connection: Keep-Alive');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . $size);
			
			readfile('exports/'.$filename);
		}
	}
	
	public function undo(){
		if ($this->session->userdata('undo') !== false){
			$undo = $this->session->userdata('undo');
			switch($undo['type']){
				case 'inventory':
					switch($undo['action']){
						case 'update':
							//remove all inv suppliers
							$this->inventory_in_supplier_model->remove_by_inventory($undo['data']['id']);
							$hisotry_data = $this->history->get("inventory-supplier");
							foreach ($hisotry_data as $data){
								if (is_array($data)){
									$this->inventory_in_supplier_model->save($data);
								}
							}
							
							$this->inventory_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Inventár zmenený späť');
							break;
						case 'edit':
							$hisotry_data = $this->history->get("inventory-log");
							foreach ($hisotry_data as $data){
								if (is_array($data)){
									$log = $this->inventory_log_model->get(array('inventory'), $data['id']);
									$this->inventory_model->set_amount($log['amount'] - $log['log_amount'], $log['inventory_id']);
									$this->inventory_log_model->remove($data['id']);
								}
							}
							
							$this->inventory_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Inventár zmenený späť');
							break;
						case 'add':
							//remove all inv suppliers
							$this->inventory_in_supplier_model->remove_by_inventory($undo['data']['id']);
							$hisotry_data = $this->history->get("inventory-supplier");
							foreach ($hisotry_data as $data){
								if (is_array($data)){
									$this->inventory_in_supplier_model->save($data);
								}
							}
							
							$this->inventory_model->remove($undo['data']['id']);
							$this->set_message('[Undo action] Inventár odstránený');
							break;
						case 'remove':
							$this->inventory_model->restore($undo['data']['id']);
							$this->set_message('[Undo action] Inventár obnovený');
							break;
					}
					break;
			}
			switch($undo['type']){
				case 'category':
					switch($undo['action']){
						case 'update':
							$this->inventory_cateogyr_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Kategória zmenená späť');
							break;
						case 'add':							
							$this->inventory_category_model->remove($undo['data']['id']);
							$this->set_message('[Undo action] Kategória odstránená');
							break;
						case 'remove':
							$this->inventory_category_model->save($undo['data']);
							
							$hisotry_data = $this->history->get("inventory-category");
							foreach ($hisotry_data as $data){
								if (is_array($data)){
									$this->inventory_model->save(array('category_id' => $undo['data']['id']), $data['id']);
								}
							}
							
							$this->set_message('[Undo action] Kategória obnovená');
							break;
					}
					redirect("cms/inventory/category");
					break;
			}
		}
		redirect("cms/inventory");
	}
	
}