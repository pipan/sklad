<?php

class Options_model extends MY_Model {

    public $select_login;

    public function __construct() {
        parent::__construct('options');
    }

    public static function get_select() {
        return array('options.opt_name', 'options.opt_value', 'options.opt_other');
    }

    public static function get_select_id() {
        return array('options.id', 'options.opt_name', 'options.opt_value', 'options.opt_other');
    }

    public static function get_relation() {
        return array();
    }

    public function get_dummy() {
        return array(
            'id' => 0,
            'opt_name' => "",
            'opt_value' => "",
            'opt_other' => null,
        );
    }

    public function get_filter($filter) {
        
    }
    
    public function get_indexed($join = array(), $id = false, $positive = true){
        $tmp = $this->get($join, $id, $positive);
        $settings = array();
        foreach($tmp as $t){
            $settings[$t['opt_name']] = $t;
        }
        return $settings;
    }
    
    public function get_by_name($name){
        $query = $this->db->get_where($this->table, array($this->table.'.opt_name =' => $name));
        return $query->row_array();
    }
    
    public function save($data, $id = false){
        foreach ($data as $key => $val){
            $get = $this->get_by_name($key);
            if ($get != null){
                $this->db->where(array('id' => $get['id']));
                $this->db->update($this->table, array('opt_name' => $key, 'opt_value' => $val));
            }
            else{
                $this->db->insert($this->table, array('opt_name' => $key, 'opt_value' => $val));
            }
        }
    }

    public function _create_table() {
        $this->db->query("CREATE TABLE IF NOT EXISTS options(
            id int(9) NOT NULL AUTO_INCREMENT,
            opt_name varchar(100) NOT NULL,
            opt_value varchar(255) NOT NULL,
            opt_other varchar(255),
            PRIMARY KEY (id))
            COLLATE utf8_general_ci,
            ENGINE innoDB");
    }

}
