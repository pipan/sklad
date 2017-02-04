<?php
$route['cms/login'] = "user/login/login";
$route['cms/logout'] = "user/login/logout";
$route['cms/user/login/logout'] = "user/login/logout";
$route['cms/user/login/login'] = "user/login/login";
$route['(:any)/cms/user/(:any)'] = "user/cms/$2";
$route['cms/user/(:any)'] = "user/cms/$1";
$route['cms/user'] = "user/cms/index";
$route['cms'] = "user/login/index";