<?php
$config['cms'] = array(
		'requires_privelage' => array('manage_supplier'),
		'text' => 'Dodávatelia',
		'link' => config_item('index_page')."cms/supplier",
		'submenu' => array(
				array(
						'text' => "nový dodávateľ",
						'link' => config_item('index_page')."cms/supplier/add",
				),
		),
);