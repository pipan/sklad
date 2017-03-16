<?php
$route['(:any)/cms/system/(:any)'] = "system/$2";
$route['cms/system/(:any)'] = "system/$1";
$route['cms/system'] = "system/index";
//$route['cms'] = "static/cms/index";

$route['(:any)/(:any)'] = "system/view/view/$2/$1";
$route['(:any)'] = "system/view/view/$1";

$route['default_controller'] = "system/view";