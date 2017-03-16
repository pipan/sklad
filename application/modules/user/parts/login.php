<?php

class Login extends Part {

    public function __construct($module) {
        parent::__construct($module);

        $this->load->library("form_validation");
        $this->load->library("Bcrypt");
        $this->load->library("login_library");
        $this->login_library->setSession($this->session);

        $this->data['path'] = array(
            0 => array(
                'text' => "domov",
                'link' => base_url() . "cms",
            ),
        );

        $this->data['login'] = array(
            'link' => base_url() . "cms/login",
            'text' => "prihlásenie",
        );

        $this->layout = "layouts/cms2";
    }

    public function login_validation() {
        if (($user = $this->admin_model->get_login($this->input->post('name'))) != null) {
            if ($this->bcrypt->check_password($this->input->post('password') . $user['salt'], $user['password'])) {
                return true;
            }
        }
        $this->form_validation->set_message('login_validation', 'Nesprávne meno alebo heslo');
        return false;
    }

    public function index() {
        if (is_admin_login_redirect($this)) {
            //form validation rules
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('password', 'password', 'required|callback_login_validation');

            //form validation
            if ($this->form_validation->run() !== false) {
                $this->login_library->login($this->input->post('name'));
            }

            //header
            $this->data['menu'] = cms_header($this->session->userdata('user'));

            $this->data['login'] = array(
                'link' => base_url() . "cms/logout",
                'text' => "odhlásenie",
            );
            
            $this->data['session'] = $this->session->all_userdata();

            //form
            $layout_data['title'] = "CMS index";
            $layout_data['header'] = $this->load->view("cms/header", $this->data, true);
            $layout_data['body'] = $this->load->view("home", $this->data, true);
            $layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
            $layout_data['submenu'] = $this->load->view("cms/submenu", $this->data, true);
            $layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);

            //
            $this->model->load->view($this->layout, $layout_data);
        }
    }

    public function login() {
        if (!is_admin_login($this) && !$this->login_library->autologin()) {
            //form validation rules
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('password', 'password', 'required|callback_login_validation');

            //form validation
            if ($this->form_validation->run() !== false) {
                $user = $this->admin_model->get_login($this->input->post('name'));
                $this->form_validation->set_rules('remember', 'remember', 'required');
                if ($this->form_validation->run() !== false){
                    $this->login_library->remember($user['id']);
                }
                $this->login_library->login($user);
            }

            //form
            $layout_data['title'] = "Trans Sklad - login";
            $layout_data['header'] = $this->load->view("cms/header", $this->data, true);
            ;
            $layout_data['body'] = $this->load->view("login/body", $this->data, true);
            $layout_data['menu'] = $this->load->view("cms/menu", $this->data, true);
            $layout_data['submenu'] = "";
            $layout_data['footer'] = $this->load->view("cms/footer", $this->data, true);
            ;

            //
            $this->model->load->view($this->layout, $layout_data);
        } else {
            redirect("cms");
        }
    }

    public function logout() {
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('user');
        $this->login_library->logout();
        redirect("cms");
    }

}
