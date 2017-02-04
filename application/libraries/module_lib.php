<?php
class Module_lib{
	
	protected $config;
	
	protected $error_message;
	
	public function __construct(){
		if (file_exists(APPPATH."config/module.php")){
			include(APPPATH."config/module.php");
			$this->config = $config; 
			unset($config);
		}
		else{
			die ("module config file not found");
		} 
	}
	
	public function required($module){
		$ret = true;
		if (!is_array($module)){
			$module = array($module);
		}
		foreach ($module as $m){
			if (in_array($m, $this->config['module'])){
				if (file_exists(MODPATH.$m)){
					if (file_exists(MODPATH.$m."/config") && file_exists(MODPATH.$m."/config/autoload.php")){
						include(MODPATH.$m."/config/autoload.php");
						if (isset($autoload)){
							if (isset($autoload['module']) && sizeof($autoload['module']) > 0){
								$ret = $this->required($autoload['module']);
								if ($ret == false){
									unset($autoload);
									return $ret;
								}
							}
							unset($autoload);
						}
					}
				}
				else{
					$this->error_message = $m." folder does not exists";
					return false;
				}
			}
			else{
				$this->error_message = $m." module does not exists in config";
				return false;
			}
		}
		return $ret;
	}
	
	public function get_error_message(){
		return $this->error_message;
	}
}