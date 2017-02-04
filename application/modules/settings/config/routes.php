<?php
$route['(:any)/cms/settings/(:any)'] = "settings/cms/$2";
$route['cms/settings/(:any)'] = "settings/cms/$1";
$route['cms/settings'] = "settings/cms/index";