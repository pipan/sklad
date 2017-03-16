<?php
if (!function_exists("cmp_privelage")){
	function cmp_privelage($user_privelage, $requires){
		foreach ($requires as $r){
			if (!isset($user_privelage[$r]) || $user_privelage[$r] == 0){
				return false;
			}
		}
		return true;
	}
}