<?php
class CMS extends Part{
	
	public function __construct($module){
		parent::__construct($module, true, array('manage_inventory'));
		
		$this->load->library("form_validation");
		$this->load->library("History");
		$this->load->library('PHPExcel');
		
		//header
		$this->data['menu'] = cms_header($this->session->userdata('user'));
		
		//menu
		$this->data['submenu'] = cms_subheader('order');
		
		$this->data['login'] = array(
				'link' => base_url()."cms/logout",
				'text' => "odhlásenie",
		);
		
		//layout
		$this->layouts = "layouts/cms2";
		
		$this->paging_session = "page_order";
	}
	
	public function index($page = null){
		
		$this->data['style'] = array('cms/order');
		
		//load filter library
		$this->load->library('filter', array('name' => 'order'));
		//set elements of filter
		$this->filter->add_element(array('name' => 'accepted', 'default_value' => 0, 'form_name' => 'filter_accepted'));
		
		//save filter if needed
		$this->form_validation->set_rules('filter', 'filter', 'required');
		
		if ($this->form_validation->run() !== false){
			$this->session->set_userdata($this->paging_session, false);
			if ($this->input->post('filter') == 'filter'){
				$this->filter->form_validation($this->form_validation);
				$this->form_validation->run();
		
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
		$this->data['page_last'] = ceil($this->order_model->count_allf($this->data['filter']) / $this->limit);
		$this->data['page_link'] = base_url()."cms/order/index/%p";
		
		$this->data['orders'] = $this->order_model->get_listf(array(), ($page - 1) * $this->limit, $this->limit, $this->data['filter']);
		
		$this->data['page_title'] = "Objednávky";
		
		$layout_data['title'] = "Trans Sklad - Objednávky";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function add(){
		$this->data['style'] = array('tab_style', 'cms/content_submenu', 'cms/order');
		$this->data['jscript'] = array('Tab', 'cms/Order');
		
		$this->form_validation->set_rules('order_supplier', 'dodávateľ', 'required|callback_id_exists[supplier_model]');
		$this->form_validation->set_rules('generate', 'generate', 'required');
		
		if ($this->form_validation->run() !== false){
			
			//save order
			$table_data_order = $this->_get_td_add();
			$order_id = $this->order_model->save($table_data_order);
			
			//save items to order
			foreach ($this->input->post("item_id") as $key => $value){
				$amount = $this->input->post("amount")[$key];
				$amount_info = $this->input->post("amount_info")[$key];
				$table_data = array(
						'order_id' => $order_id,
						'inventory_id' => $value,
						'order_amount' => intval($amount),
						'order_amount_orig' => $amount, 
						'order_amount_info' => $amount_info,
				);
				
				$this->inventory_in_order_model->save($table_data);
			}
			
			
			// Set document properties
			$this->phpexcel = PHPExcel_IOFactory::load("config/template.xlsx");
			$this->phpexcel->getProperties()->setCreator("Trans Sklad")
				->setLastModifiedBy("Trans Sklad")
				->setTitle("Translata Objednavka")
				->setSubject("Translata Objednavka")
				->setDescription("Objednavka pre firmu Translata");
			
			//load excel config
			$this->phpexcel->setActiveSheetIndex(1);
			$excel_config['title'] = $this->phpexcel->getActiveSheet()->getCell('B1')->getValue();
			$excel_config['first_line'] = $this->phpexcel->getActiveSheet()->getCell('B2')->getValue();
			$excel_config['start'] = $this->phpexcel->getActiveSheet()->getCell('B3')->getValue();
			$excel_config['end'] = $this->phpexcel->getActiveSheet()->getCell('B4')->getValue();
			$iter = $excel_config['start'];
			$i = 5;
			while (true){
				$excel_config['table'][$iter] = $this->phpexcel->getActiveSheet()->getCell('B'.$i)->getValue();
				if ($iter == $excel_config['end']){
					break;
				}
				$iter = chr(ord($iter) + 1);
				$i++;
			}
			
			$this->phpexcel->setActiveSheetIndex(0);
			
			//fill order name
			$this->phpexcel->getActiveSheet()->setCellValue($excel_config['title'], $table_data_order['order_number']);
			//fill table => order data
			$order_data = $this->inventory_in_order_model->get_excel_data($order_id, $this->input->post("order_supplier"));
			$i = $excel_config['first_line'];
			$prev = null;
                        $settings = $this->options_model->get_indexed(array());
			foreach ($order_data as $data){
				if ($prev == null || $prev['category_id'] != $data['category_id']){
					$i++;
					$this->phpexcel->getActiveSheet()->setCellValue('A'.$i, $data['category_name']);
                                        //bold
                                        $this->phpexcel->getActiveSheet()->getStyle("A".$i)->getFont()->setBold(true);
                                        //background color
                                        $this->phpexcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFill()->applyFromArray(array(
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                            'startcolor' => array(
                                                 'rgb' => setting_value($settings, "category_color_".$data['category_id'])
                                            )
                                        ));
					$i++;
				}
				foreach ($excel_config['table'] as $key => $value){
					$this->phpexcel->getActiveSheet()->setCellValue($key.$i, $data[$value]);
				}
				$i++;
				$prev = $data;
			}
			
			
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
			$objWriter->save("orders/".$table_data_order['order_number'].".xlsx");
			
			$data = array(
					'data' => $this->order_model->get(array(), $order_id),
			);
			$this->set_undo('order', 'add', $data);
			$this->set_message('Nová objednávka vytvorená <a href="'.base_url().'cms/order/undo">undo</a>');
			redirect("cms/order");
		}
		
		$this->data['inventories'] = $this->inventory_model->get();
		$this->data['warnings'] = $this->inventory_model->get_warnings();
		$this->data['suppliers'] = $this->supplier_model->get();
		
		$this->data['page_title'] = "Objednávka";
		$this->data['content_menu_title'] = "Inventár";
		
		$layout_data['title'] = "Trans Sklad - Objednávka";
		$layout_data['header'] = $this->load->view("cms/header", $this->data, true);
		$layout_data['body'] = $this->load->view("cms/body_add", $this->data, true);
		$layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
		$layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
		$layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
			
		$this->model->load->view($this->layouts, $layout_data);
	}
	
	public function view($id){
            if ($this->order_model->exists($id)){

                $this->form_validation->set_rules('order_accepted', 'accepted', '');
                $this->form_validation->set_rules('edit', 'edit', 'required');

                if ($this->form_validation->run() !== false){

                    $table_data_order = $this->order_model->get(array(), $id);
                    
                    $undo_data = array(
                            'data' => $table_data_order,
                    );

                    $table_data = $this->_get_td_accept();
                    $this->order_model->save($table_data, $id);

                    $items = $this->inventory_in_order_model->get_by_order($id, array('inventory'));
                    $user = $this->session->userdata("admin_id");
                    $datetime = date("Y-n-d-H-i-s");
                    $j = 0;
                    foreach ($items as $i){
                        $amount = intval($this->input->post("inv_".$i['id']));
                        if ($this->input->post("order_accepted") !== false){
                            if ($amount > 0){
                                $table_data = array(
                                    'inventory_id' => $i['inventory_id'],
                                    'user_id' => $user,
                                    'log_amount' => $amount,
                                    'log_date' => $datetime,
                                    'log_note' => "objednávka",
                                );
                                $log_ids[$j]['id'] = $this->inventory_log_model->save($table_data);

                                $this->inventory_model->set_amount($i['amount'] + $amount, $i['inventory_id']);
                                $j++;
                            }
                        }
                        
                        $table_data = array(
                            'order_amount_orig' => $this->input->post("inv_".$i['id']),
                            'order_amount' => $amount
                        );
                        
                        $this->inventory_in_order_model->save($table_data, $i['id']);
                    }
                    
                    //history backup
                    copy("orders/".$table_data_order['order_number'].".xlsx", "orders/history.xlsx");
                    
                    $this->phpexcel = PHPExcel_IOFactory::load("config/template.xlsx");
                    $this->phpexcel->getProperties()->setCreator("Trans Sklad")
                            ->setLastModifiedBy("Trans Sklad")
                            ->setTitle("Translata Objednavka")
                            ->setSubject("Translata Objednavka")
                            ->setDescription("Objednavka pre firmu Translata");

                    //load excel config
                    $this->phpexcel->setActiveSheetIndex(1);
                    $excel_config['title'] = $this->phpexcel->getActiveSheet()->getCell('B1')->getValue();
                    $excel_config['first_line'] = $this->phpexcel->getActiveSheet()->getCell('B2')->getValue();
                    $excel_config['start'] = $this->phpexcel->getActiveSheet()->getCell('B3')->getValue();
                    $excel_config['end'] = $this->phpexcel->getActiveSheet()->getCell('B4')->getValue();
                    $iter = $excel_config['start'];
                    $i = 5;
                    while (true){
                            $excel_config['table'][$iter] = $this->phpexcel->getActiveSheet()->getCell('B'.$i)->getValue();
                            if ($iter == $excel_config['end']){
                                    break;
                            }
                            $iter = chr(ord($iter) + 1);
                            $i++;
                    }

                    $this->phpexcel->setActiveSheetIndex(0);
                    
                    //fill order name
                    $this->phpexcel->getActiveSheet()->setCellValue($excel_config['title'], $table_data_order['order_number']);
                    //fill table => order data
                    $order_data = $this->inventory_in_order_model->get_excel_data($id, $table_data_order['supplier_id']);
                    $i = $excel_config['first_line'];
                    $prev = null;
                    $settings = $this->options_model->get_indexed(array());
                    foreach ($order_data as $data){
                        if ($prev == null || $prev['category_id'] != $data['category_id']){
                            $i++;
                            $this->phpexcel->getActiveSheet()->setCellValue('A'.$i, $data['category_name']);
                            //bold
                            $this->phpexcel->getActiveSheet()->getStyle("A".$i)->getFont()->setBold(true);
                            //background color
                            $this->phpexcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFill()->applyFromArray(array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'startcolor' => array(
                                     'rgb' => setting_value($settings, "category_color_".$data['category_id'])
                                )
                            ));
                            $i++;
                        }
                        foreach ($excel_config['table'] as $key => $value){
                            $this->phpexcel->getActiveSheet()->setCellValue($key.$i, $data[$value]);
                        }
                        $i++;
                        $prev = $data;
                    }
                    
                    // Save Excel 2007 file
                    $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
                    $objWriter->save("orders/".$table_data_order['order_number'].".xlsx");

                    if ($this->input->post("order_accepted") !== false){
                        $this->history->set("order-log", $log_ids, true);
                        
                        $this->set_undo('order', 'accepted', $undo_data);
                        $this->set_message('Objednávka prijatá, na základe objednávky boli upravená aj množstvá <a href="'.base_url().'cms/order/undo">undo</a>');
                    }
                    else{
                        $this->set_undo('order', 'edit', $undo_data);
                        $this->set_message('Objednávka upravená <a href="'.base_url().'cms/order/undo">undo</a>');
                    }
                    
                    $this->history->set("order-iio", $items, true);
                
                    redirect("cms/order");
                }

                $this->data['order'] = $this->order_model->get(array(), $id);
                $this->data['iio'] = $this->inventory_in_order_model->get_by_order($id, array('inventory'));

                $this->data['page_title'] = "Objednávka ".$this->data['order']['order_number'];

                $layout_data['title'] = "Trans Sklad - Objednávka";
                $layout_data['header'] = $this->load->view("cms/header", $this->data, true);
                $layout_data['body'] = $this->load->view("cms/body_view", $this->data, true);
                $layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
                $layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
                $layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);

                $this->model->load->view($this->layouts, $layout_data);
            }
            else{
                $this->_error_message("Nesprávna stránka");
            }
	}
	
	public function download($id){
		if ($this->order_model->exists($id)){
			$order = $this->order_model->get(array(), $id);
			$filename = $order['order_number'].".xlsx";
			if (file_exists('orders/'.$filename)){
				$quoted = sprintf('"%s"', addcslashes(basename($filename), '"\\'));
				$size   = filesize('orders/'.$filename);
					
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $quoted);
				header('Content-Transfer-Encoding: binary');
				header('Connection: Keep-Alive');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . $size);
					
				readfile('orders/'.$filename);
			}
		}
	}
	
	public function _get_td_add(){
		$supplier = $this->supplier_model->get(array(), $this->input->post('order_supplier'));
		
		$number = "objednavka_".$supplier['supplier_name']."_".date("Y-m-d");
		
		$table_data = array(
				'order_number' => $number,
				'supplier_id' => $supplier['id'],
				'order_date' => date("Y-n-d H:i:s"),
				'order_accepted' => 0,
		);
		
		return $table_data;
	}
	public function _get_td_accept(){
		$table_data = array(
				'order_accepted' => $this->input->post("order_accepted") !== false,
		);
	
		return $table_data;
	}
	public function _get_td_add_supplier($inventory_id, $supplier_id){
		$table_data = array(
				'inventory_id' => $inventory_id,
				'supplier_id' => $supplier_id,
				'info' => $this->input->post('sup_'.$supplier_id.'_info'),
				'code' => $this->input->post('sup_'.$supplier_id.'_code'),
		);
		
		return $table_data;
	}
	
	public function remove($id){
		if ($this->order_model->exists($id)){
			
			//set message and undo
			$data = array(
					'data' => $this->order_model->get(array(), $id, true),
			);
			$this->set_undo('order', 'remove', $data);
			$this->set_message('Objednávka odstránená <a href="'.base_url().'cms/order/undo">undo</a>');
	
			$this->order_model->remove($id);
			redirect("cms/order");
		}
		else{
			$this->_error_message("Nesprávna stránka");
		}
	}
	
	public function undo(){
		if ($this->session->userdata('undo') !== false){
			$undo = $this->session->userdata('undo');
			switch($undo['type']){
				case 'order':
					switch($undo['action']){
						case 'edit':
							$history_data = $this->history->get("order-iio");
							foreach ($history_data as $data){
								if (is_array($data)){
                                                                    $table_data = array(
                                                                        'order_amount_orig' => $data['order_amount_orig'],
                                                                        'order_amount' => $data['order_amount']
                                                                    );
                                                                    $log = $this->inventory_in_order_model->save($table_data, $data['id']);
								}
							}
							
							$this->set_message('[Undo action] Objednávka zmenená späť');
                                                        
                                                        copy("orders/history.xlsx", "orders/".$undo['data']['order_number'].".xlsx");
							break;
                                                case 'accepted':
                                                        $history_data = $this->history->get("order-log");
							foreach ($history_data as $data){
								if (is_array($data)){
									$log = $this->inventory_log_model->get(array('inventory'), $data['id']);
									$this->inventory_model->set_amount($log['amount'] - $log['log_amount'], $log['inventory_id']);
									$this->inventory_log_model->remove($data['id']);
								}
							}
                                                        
                                                        $history_data = $this->history->get("order-iio");
							foreach ($history_data as $data){
								if (is_array($data)){
                                                                    $table_data = array(
                                                                        'order_amount_orig' => $data['order_amount_orig'],
                                                                        'order_amount' => $data['order_amount']
                                                                    );
                                                                    $log = $this->inventory_in_order_model->save($table_data, $data['id']);
								}
							}
                                                        
                                                        copy("orders/history.xlsx", "orders/".$undo['data']['order_number'].".xlsx");
							
							$this->order_model->save($undo['data'], $undo['data']['id']);
							$this->set_message('[Undo action] Objednávka zmenená späť');
                                                        break;
						case 'add':
							$this->inventory_in_order_model->remove_by_order($undo['data']['id']);
							
							$this->order_model->remove($undo['data']['id']);
							$this->set_message('[Undo action] Objednávka odstránená');
							break;
						case 'remove':
							$this->order_model->save($undo['data']);
							$this->set_message('[Undo action] Objednávka obnovená');
							break;
					}
					break;
			}
		}
		redirect("cms/order");
	}
	
}