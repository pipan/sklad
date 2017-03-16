<?php

class CMS extends Part {

    public function __construct($module) {
        parent::__construct($module, true, array('manage_inventory'));

        $this->load->library("form_validation");
        $this->load->library("History");

        //header
        $this->data['menu'] = cms_header($this->session->userdata('user'));

        //menu
        $this->data['submenu'] = cms_subheader('settings');

        $this->data['login'] = array(
            'link' => base_url() . "cms/logout",
            'text' => "odhlásenie",
        );

        //layout
        $this->layouts = "layouts/cms2";

        $this->paging_session = "page_order";
    }

    public function index($page = null) {

        $this->data['style'] = array('pop_up', 'center', 'slider', 'color_picker');
        $this->data['jscript'] = array('PopUp', 'elements/Base', 'elements/Range', 'elements/Label', 'elements/Color', 'elements/Slider', 'elements/ColorPicker', 'elements/ColorValue');		
            
        $category_model = new Inventory_category_model();
        $this->data['categories'] = $category_model->get(array());
        foreach ($this->data['categories'] as $c){
            $this->form_validation->set_rules('category_color_'.$c['id'], $c['category_name']." farba", '');
        }

        if ($this->form_validation->run() !== false){
            //cookies undo info
            $data = array(
                'data' => $this->options_model->get(array()),
            );
            $this->set_undo('settings', 'set', $data);
            $this->set_message('Nastavenia zmenené, <a href="'.base_url().'cms/settings/undo">undo</a>');

            $table_data = $this->_get_td();
            $this->options_model->save($table_data);
        }
        
        $this->data['settings'] = $this->options_model->get_indexed(array());
        
        $this->data['page_title'] = "Nastavenia";

        $layout_data['title'] = "Trans Sklad - Nastavenia";
        $layout_data['header'] = $this->load->view("cms/header", $this->data, true);
        $layout_data['body'] = $this->load->view("cms/body", $this->data, true);
        $layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
        $layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
        $layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);

        $this->model->load->view($this->layouts, $layout_data);
    }
    
    private function _get_td(){
        $data = array();
        foreach ($this->data['categories'] as $c){
            $data['category_color_'.$c['id']] = $this->input->post('category_color_'.$c['id']);
        }
        return $data;
    }

    public function undo() {
        if ($this->session->userdata('undo') !== false) {
            $undo = $this->session->userdata('undo');
            switch ($undo['type']) {
                case 'order':
                    switch ($undo['action']) {
                        case 'edit':
                            $hisotry_data = $this->history->get("order-log");
                            foreach ($hisotry_data as $data) {
                                if (is_array($data)) {
                                    $log = $this->inventory_log_model->get(array('inventory'), $data['id']);
                                    $this->inventory_model->set_amount($log['amount'] - $log['log_amount'], $log['inventory_id']);
                                    $this->inventory_log_model->remove($data['id']);
                                }
                            }

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
