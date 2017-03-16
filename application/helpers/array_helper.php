<?php
if (!function_exists("array_match")){
	function array_match($a1, $a2){
		If (sizeof($a1) >= sizeof($a2)){
			for ($i = 0; $i < sizeof($a2); $i++){
				if ($a1[$i] != $a2[$i]){
					return false;
				}
			}
			return true;
		}
		return false;
	}
}