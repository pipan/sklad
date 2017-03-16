<?php
if (!function_exists("cms_header")){
	function cms_header($user = array()){
		$header = array();
		$modules = get_modules();
		foreach ($modules as $m){
			if (file_exists(MODPATH.$m."/config/config.php")){
				include(MODPATH.$m."/config/config.php");
				if (isset($config)){
					if (isset($config['cms']) && is_array($config['cms'])){
						if (!isset($config['cms']['requires_privelage']) || cmp_privelage($user, $config['cms']['requires_privelage']) == true){
							$header[] = $config['cms'];
						}
					}
					unset($config);
				}
			}
		}
		return $header;
	}
}
if (!function_exists("cms_subheader")){
	function cms_subheader($module){
		$subheader = array();
		$modules = get_modules();
		foreach ($modules as $m){
			if ($m == $module && file_exists(MODPATH.$m."/config/config.php")){
				include(MODPATH.$m."/config/config.php");
				if (isset($config)){
					if (isset($config['cms']) && is_array($config['cms']) && isset($config['cms']['submenu']) && is_array($config['cms']['submenu'])){
						$subheader = $config['cms']['submenu'];
					}
					unset($config);
				}
			}
		}
		return $subheader;
	}
}
if (!function_exists("cms_url")){
	function cms_url(){
		return base_url()."cms/";
	}
}