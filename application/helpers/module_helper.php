<?php
if (!function_exists("get_modules")){
	function get_modules(){
		if (file_exists(APPPATH."config/module.php")){
			include(APPPATH."config/module.php");
			if (isset($config)){
				if (isset($config['module']) && sizeof($config['module']) > 0){
					return $config['module'];
				}
			}
		}
		return arra();
	}
}