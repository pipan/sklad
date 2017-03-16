<?php
class View extends Part{
	
	public function __construct($module){
		parent::__construct($module, false, array());
	
		//email class
		$this->load->library("email");
		
		//layout
		$this->layouts = "layouts/view";
		
		$this->data['jscript'] = array('inventory/Item', 'view/events', 'PopUp');
		
		$this->data['style'] = array('pop_up', 'center');
	}
	
	public function login(){
		$uid = $this->input->post('uid');
		if ($user = $this->admin_model->get_login_by_uid($uid)){
			echo $user['admin_name']." ".$user['admin_surname'];
			$this->session->set_userdata("login", $user);
			return;
		}
		echo "false";
	}
	
	public function index(){
		
		$this->data['inventories'] = $this->inventory_model->get();
		
		$this->data['storage_change'] = $this->session->userdata('storage_change');
		if ($this->data['storage_change'] !== false){
			$this->session->unset_userdata('storage_change');
		}
		
		$this->data['warnings'] = $this->inventory_model->get_warnings();
		$this->data['warnings_count'] = $this->inventory_model->count_warnings();
		$this->data['header_categories'] = $this->inventory_category_model->get();
		
		$this->data['page_title'] = "Inventár";
		
		$layout_data['title'] = "Trans Sklad - Inventár";
		$layout_data['header'] = $this->load->view("view/header", $this->data, true);
		$layout_data['body'] = $this->load->view("view/body", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function edit(){
		$user = $this->session->userdata("login");
		$datetime = date("Y-n-d-H-i-s");
		$notify = array();
		if ($this->input->post("item_id") != false && is_array($this->input->post("item_id"))){
			foreach ($this->input->post("item_id") as $key => $val){
				$amount = $this->input->post("amount")[$key];
				if ($amount != 0)
				{
					$table_data = array(
							'inventory_id' => $val,
							'user_id' => $user['id'],
							'log_amount' => $amount,
							'log_date' => $datetime,
					);
					$this->inventory_log_model->save($table_data);
					
					$item = $this->inventory_model->get(array(), $val);
					
					$this->inventory_model->set_amount($item['amount'] + $amount, $val);
					
					//if one of the items is lower then min
					if ($item['amount'] + $amount < $item['min_amount']){
						$notify[] = $item['id'];
					}
				}
			}
			if (sizeof($notify) > 0){
				//get email to send to
				$users = $this->admin_model->get_notify();
				$to = array();
				foreach ($users as $u){
					if ($u['email'] != ""){
						$to[] = $u['email'];
					}
				}
				
				if (sizeof($to) > 0){
					//send email notification
					$data['warnings'] = $this->inventory_model->get_warnings();
					$data['notify'] = $notify;
					$data['taken'] = array();
					foreach ($data['warnings'] as $item){
						if (in_array($item['id'], $notify)){
							$data['taken'][] = $item;
						}
					}
					$body = $this->load->view("view/body_email", $data, true);
					
					/*
					 * GMAIL
					*/
					/*
					$config = Array(
							'protocol' => 'smtp',
							'smtp_host' => 'ssl://smtp.googlemail.com',
							'smtp_port' => '465',
							'smtp_user' => 'gasparik.spam@gmail.com',
							'smtp_pass' => 'spam8347',
							'mailtype'  => 'html',
							'charset'   => 'utf-8',
					);
					*/
					$config = array(
							'protocol' => 'smtp',
							'smtp_host' => 'mail.translata.sk',
							'smtp_port' => '25',
							'smtp_user' => 'sklad',
							'smtp_pass' => 'pisankA357',
							'mailtype'  => 'html',
							'charset'   => 'utf-8',
					);
					$this->email->initialize($config);
					
					$this->email->set_newline("\r\n");
					
					$this->email->from("sklad@translata.sk", "Trans-Sklad");
					$this->email->to($to);
					$this->email->subject("Trans-Sklad warning");
					$this->email->message($body);
					
					
					$this->email->send();
				}
			}
		}
		$this->session->set_userdata('storage_change', $user);
		$this->session->unset_userdata("login");
		redirect("");
	}
	
	public function search(){
		$this->data['inventories'] = $this->inventory_model->search(array(), $this->input->post("search"), $this->input->post("category"));
		$this->data['search_count'] = $this->inventory_model->count_search($this->input->post("search"), $this->input->post("category"));
		$this->data['search'] = $this->input->post("search");
		
		echo $this->load->view("view/search", $this->data, true);
	}
} 