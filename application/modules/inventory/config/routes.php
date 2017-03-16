<?php
$route['(:any)/cms/inventory/(:any)'] = "inventory/cms/$2";
$route['cms/inventory/(:any)'] = "inventory/cms/$1";
$route['cms/inventory'] = "inventory/cms/index";

$route['(:any)/inventory/(:any)'] = "inventory/index/$2/$1";
$route['inventory/(:any)'] = "inventory/$1";
$route['(:any)/inventory'] = "inventory/view/index/$1";
$route['inventory'] = "inventory/view/index";

$route['default_controller'] = "inventory/view/index";