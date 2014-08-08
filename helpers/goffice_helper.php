<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('osszeg_formaz')) {

    function osszeg_formaz($osszeg) {
        return number_format($osszeg,0,',','.');
    }

}

/* End of file users/helpers/user_helper.php */