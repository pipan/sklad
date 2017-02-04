<?php
if (!function_exists("fill_zero")){
	function fill_zero($number, $max_length){
		$length = get_number_length($number);
		for ($length; $length < $max_length; $length++){
			$number = "0".$number;
		}
		return $number;
	}
}
if (!function_exists("get_number_length")){
	function get_number_length($number){
		$length = 1;
		$number = floor($number/10);
		while ($number > 0){
			$number = floor($number/10);
			$length++;
		}
		return $length;
	}
}