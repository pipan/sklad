<?php
if (!function_exists("get_foreign")){
	function get_foreign($id, $zero = false){
		if ($id != '' && ($zero || $id > 0)){
			return $id;
		}
		else{
			return null;
		}
	}
}
if (!function_exists("is_in_model_array")){
	function is_in_model_array($value, $array, $index){
		foreach ($array as $a){
			if ($a[$index] == $value){
				return true;
			}
		}
		return false;
	}
}
if (!function_exists("get_where")){
	function get_where($array, $index, $value){
		foreach ($array as $a){
			if ($a[$index] == $value){
				return $a;
			}
		}
		return false;
	}
}