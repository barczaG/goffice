<?php

/*
 * Dot Com Works Kft.
 * Minden jog fenntartva
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

$config['fb_appid'] = "328836420553212";
$config['fb_secret'] = "0b7efcaac0ec48c1024528d1c6f2b5bb";

//set application urls here

$config['fb_page_url'] = "https://www.facebook.com/biohair"; //"http://www.facebook.com/thinkdiff.net";
$config['fb_app_page_url'] = "{$config['fb_page_url']}?sk=app_{$config['fb_appid']}";

$config['game_url'] = 'https://apps.dcw.hu/biohair/';
?>
