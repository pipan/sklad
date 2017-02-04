<?php
$route['(:any)/cms/supplier/(:any)'] = "supplier/cms/$2";
$route['cms/supplier/(:any)'] = "supplier/cms/$1";
$route['cms/supplier'] = "supplier/cms/index";