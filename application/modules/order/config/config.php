<?php
$config['cms'] = array(
		'requires_privelage' => array('manage_order'),
		'text' => 'Objednávky',
		'link' => config_item('index_page')."cms/order",
		'submenu' => array(
				array(
						'text' => "nová objednávka",
						'link' => config_item('index_page')."cms/order/add",
				),
		),
);