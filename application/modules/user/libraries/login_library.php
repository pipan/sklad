<?php
class Login_library{
    
    private $remember_model;
    private $user_model;
    private $session;
    
    public function __construct() {
        $this->remember_model = new Remember_me_model();
        $this->user_model = new Admin_model();
    }
    
    public function setSession($session){
        $this->session = $session;
    }
    
    public function autologin(){
        if (get_cookie('user_id') !== false){
            
            if ($this->remember_model->exists_full(get_cookie('user_id'), get_cookie('serial'), get_cookie('token'))){
                $id = get_cookie('user_id');
                $user = $this->user_model->get(array('privelage'), $id);
                if ($user['active']){
                    $full = $this->remember_model->get_full(get_cookie('user_id'), get_cookie('serial'), get_cookie('token'));
                    $data = array(
                        'token' => random_string('alnum', 20),
                        'last_login' => date("Y-n-d H:i:s"),
                    );
                    $id = $this->remember_model->save($data, $full['id']);
                    $full['token'] = $data['token'];
                    $full['last_login'] = $data['last_login'];
                    $this->set_cookie($full, 7*24*60*60);
                    $this->login($user);
                    return true;
                }
                else{
                    $this->remember_model->delete_by_user(get_cookie('user_id'));
                    $data = $this->remember_model->get_dummy();
                    $this->set_cookie($data, -1000);
                }
            }
            else if ($this->remember_model->exists_half(get_cookie('user_id'), get_cookie('serial'))){
                $this->remember_model->delete_by_user(get_cookie('user_id'));
                $data = $this->remember_model->get_dummy();
                $this->set_cookie($data, -1000);
            }
        }
        return false;
    }
    
    public function login($login){
        $this->session->set_userdata('admin_id', $login['id']);
        $this->session->set_userdata('user', $login);
        redirect("cms");
    }
    
    public function logout(){
        $this->remember_model->delete_full(get_cookie('user_id'), get_cookie('serial'), get_cookie('token'));
        $data = $this->remember_model->get_dummy();
        $this->set_cookie($data, -1000);
    }
    
    public function remember($user_id){
        $data = array(
            'user_id' => $user_id,
            'serial' => random_string('alnum', 20),
            'token' => random_string('alnum', 20),
            'last_login' => date("Y-n-d H:i:s"),
        );
        $id = $this->remember_model->save($data);
        $this->set_cookie($data, 7*24*60*60);
    }
    
    public function set_cookie($data, $time = 0){
        //$time += time();
        set_cookie(array('name' => 'user_id', 'value' => $data['user_id'], 'expire' => $time));
        set_cookie(array('name' => 'serial', 'value' => $data['serial'], 'expire' => $time));
        set_cookie(array('name' => 'token', 'value' => $data['token'], 'expire' => $time));
    }
}
