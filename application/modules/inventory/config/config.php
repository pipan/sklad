<?php
$config['cms'] = array(
		'requires_privelage' => array('manage_inventory'),
		'text' => 'Inventár',
		'link' => config_item('index_page')."cms/inventory",
		'submenu' => array(
				array(
						'text' => "nová vec",
						'link' => config_item('index_page')."cms/inventory/add",
				),
				array(
						'text' => "kategórie",
						'link' => config_item('index_page')."cms/inventory/category",
				),
				array(
						'text' => "nová kategória",
						'link' => config_item('index_page')."cms/inventory/category_add",
				),
				array(
						'text' => "log",
						'link' => config_item('index_page')."cms/inventory/log",
				),
				array(
						'text' => "export",
						'link' => config_item('index_page')."cms/inventory/export",
				),
		),
);