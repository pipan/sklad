<?php
if (!function_exists("convert_float")){
	function convert_float($float){
		$float = trim($float);
		$float = str_replace(".", ",", $float);
		$exp = explode(",", $float);
		$last = $exp[sizeof($exp) - 1];
		unset($exp[sizeof($exp) - 1]);
		$float = implode("", $exp).".".$last;
		return $float;
	}
}

if (!function_exists("convert_to_float")){
	function convert_to_float($float){
		$float = trim($float);
		$float = str_replace(".", ",", $float);
		return $float;
	}
}