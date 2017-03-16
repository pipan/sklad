<?php
$config['cms'] = array(
		'requires_privelage' => array('manage_user'),
		'text' => 'Používatelia',
		'link' => config_item('index_page')."cms/user",
		'submenu' => array(
				array(
						'text' => "nový používatel",
						'link' => config_item('index_page')."cms/user/add",
				),
		),
);