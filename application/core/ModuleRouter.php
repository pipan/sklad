<?php
class CI_ModuleRouter{
	
	protected $module;
	
	protected $routes = array();
	
	protected $default_dontroller;
	
	protected $segments = array();
	
	protected $uri;
	
	public function __construct(){
		$this->uri =& load_class('URI', 'core');
	}
	
	public function initialize($module){
		$this->module = $module;
		
		//load module routes file
		if ($module != false && file_exists(MODPATH.$module."/config") && file_exists(MODPATH.$module."/config/routes.php")){
			include(MODPATH.$module."/config/routes.php");
		}
		
		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);
	}
	
	public function parse_route(){
		$this->parse_uri();
		
		// Turn the segment array into a URI string
		$uri = implode('/', $this->segments);

		// Is there a literal match?  If so we're done
		if (isset($this->routes[$uri]))
		{
			$this->segments = explode('/', $this->routes[$uri]);
			$this->validate_segment();
			return ;
		}

		// Loop through the route array looking for wild-cards
		foreach ($this->routes as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri))
			{
				// Do we have a back-reference?
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				$this->segments = explode('/', $val);
				$this->validate_segment();
				return ;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		if (isset($this->routes['default_controller'])){
			$uri = $this->routes['default_controller'];
		}
		
		$this->validate_route($uri);
		//add index values for method params and module 
		$this->validate_segment();
	}
	
	public function parse_uri(){
		$this->uri->_fetch_uri_string();
		$tmp = $this->uri->uri_string();
		$this->segments = explode("/", $tmp);
	}

	public function validate_route($uri){
		if ($uri != ""){
			$tmp = explode("/", $uri);
			$this->segments = $tmp;
		}
	}
	
	public function validate_segment(){
		if (!is_array($this->segments)){
			$this->segments = array();
		}
		if (sizeof($this->segments) < 1){
			$this->segments[0] = "index";
		}
		if (sizeof($this->segments) < 2){
			$this->segments[1] = "index";
		}
		if (sizeof($this->segments) < 3){
			$this->segments[2] = "index";
		}
	}
	
	public function fetch_class(){
		if (sizeof($this->segments) > 0){
			return $this->segments[0];
		}
	}
	public function fetch_method(){
		if (sizeof($this->segments) > 1){
			return $this->segments[1];
		}
	}
	public function fetch_params(){
		if (sizeof($this->segments) > 2){
			return array_slice($this->segments, 2);
		}
		return array();
	}
}