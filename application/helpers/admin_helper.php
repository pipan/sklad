<?php

if (!function_exists("is_admin_login_redirect")) {

    function is_admin_login_redirect($controller) {
        if ($controller->session->userdata('admin_id') != false) {
            return true;
        }
        redirect("cms/login");
    }
}

if (!function_exists("is_admin_login")) {

    function is_admin_login($controller) {
        if ($controller->session->userdata('admin_id') != false) {
            return true;
        }
        return false;
    }

}
