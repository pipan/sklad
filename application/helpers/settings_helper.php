<?php
if (!function_exists('setting_value')){
    function setting_value($data, $key){
        if (isset($data[$key]) && isset($data[$key]['opt_value'])){
            return $data[$key]['opt_value'];
        }
        return "";
    }
}