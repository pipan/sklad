<?php
$config['cms'] = array(
    'requires_privelage' => array('manage_settings'),
    'text' => 'Nastavenia',
    'link' => config_item('index_page')."cms/settings",
    'submenu' => array(),
);