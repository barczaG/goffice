<?php

/*
 * Dot Com Works Kft.
 * Minden jog fenntartva
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//Facebook helper Osztály :)

if (!function_exists('d')) {

    function d($d) {
        echo '<pre>';
        print_r($d);
        echo '</pre>';
    }

}

if (!function_exists('j_redirect')) {

    function j_redirect($url) {
        echo "<script type='text/javascript'>top.location.href = '$url';</script>";
        exit;
    }

}

if (!function_exists('fgc_curl')) {

    function fgc_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}
if (!function_exists('app_page_url')) {

    function app_page_url($uri="") {
        $ci = &get_instance();
        $ci->load->config('fotokeszito/facebook');
        $url = $ci->config->item('fb_app_page_url');
        if ($uri) {
            $url.="&app_data=$uri";
        }
        return $url;
    }

}
if (!function_exists('fb_api')) {

    function fb_api($request, $token="") 
    {
        if ($token) {
            if (substr_count($request, '?') == 0) {
                $request.="?access_token=$token";
            } else {
                $request.="&access_token=$token";
            }
        }
        return json_decode(fgc_curl("https://graph.facebook.com/$request"), TRUE);
    }

}
?>
