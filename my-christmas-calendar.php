<?php
/*
Plugin Name: My-Christmas-Calendar
Plugin URI: http://www.julekalender.com
Description: Adds your christmas advent calendar to your Wordpress website.
Version: 1.0
Author: Stian B Pedersen
Author URI: http://www.julekalender.com
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
			'height' => '600',
			'width'  => '100',
			'border' => '0',
			'bordercolor' => 'fff'
		), $atts
	);
	
	extract($atts);
	extract($opt);
		
	if( strlen($mcac_subdomain) > 1 ) {
		return "<iframe class='mcac_{$mcac_subdomain}' frameborder='0' style='width:{$width}%;height:{$height}px;border:{$border}px solid #{$bordercolor}' marginwidth='0' marginheight='0' src='https://{$mcac_subdomain}.julekalender.com'></iframe>";
	}else{
		return '';	
	}
}
add_shortcode('my_calendar', 'mcac_add_calendar');

register_deactivation_hook( __FILE__, array('Mcac','mcac_remove') );
?>
