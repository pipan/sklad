<?php
$route['(:any)/cms/order/(:any)'] = "order/cms/$2";
$route['cms/order/(:any)'] = "order/cms/$1";
$route['cms/order'] = "order/cms/index";