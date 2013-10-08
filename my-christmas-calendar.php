<?php
/*
Plugin Name: My-Christmas-Calendar
Plugin URI: http://advent-calendar.net
Description: The easy way to integrate your advent-calendar in Wordpress.
Version: 1.1
Author: Stian B Pedersen
Author URI: http://advent-calendar.net
*/

require("my-christmas-calendar_plugin.php");

function mcac_admin_menu() {
	Mcac::add_menu_page(); //kjør add_menu_page metode på init admin_menu
}
add_action('admin_menu','mcac_admin_menu');

function mcac_setup() {
	new Mcac;
}
add_action('admin_init', 'mcac_setup');

function mcac_add_calendar($atts) {
	$opt = get_option('mcac_plugin_options');
	$atts = shortcode_atts(
		array(
			'height' => '800',
			'width'  => '100',
			'border' => '0',
			'bordercolor' => 'fff'
		), $atts
	);

	extract($atts);
	extract($opt);

	if( strlen($mcac_subdomain) > 1 ) {
		( preg_match('/(%|px)/', $width) ) ? $c_width = $width : $c_width = $width . "%";
		( preg_match('/#/', $bordercolor) ) ? $c_bordercolor = $bordercolor : $c_bordercolor = "#" . $bordercolor;
		( preg_match('/(px)/', $border) ) ? $c_border = $border : $c_border = $border . "px";
		( preg_match('/(px)/', $height) ) ? $c_height = $height : $c_height = $height . "px";
		return "<iframe class='mcac_{$mcac_subdomain}' frameborder='0' style='width:{$c_width};height:{$c_height};border:{$c_border} solid {$c_bordercolor}' marginwidth='0' marginheight='0' src='https://{$mcac_subdomain}.julekalender.com'></iframe>";
	}else{
		return '';	
	}
}

add_shortcode('my_calendar', 'mcac_add_calendar');

register_deactivation_hook( __FILE__, array('Mcac','mcac_remove') );
?>