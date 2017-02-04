<?php
$config['cms'] = array(
		'requires_privelage' => array('manage_privelage'),
		'text' => 'Oprávnenia',
		'link' => config_item('index_page')."cms/privelage",
		'submenu' => array(
				array(
						'text' => "nové oprávnenie",
						'link' => config_item('index_page')."cms/privelage/add",
				),
		),
);