<?php
/*
Plugin Name: Login Widget With Shortcode
Plugin URI: https://wordpress.org/plugins/login-sidebar-widget/
Description: This is a simple login form in the widget. just install the plugin and add the login widget in the sidebar. Thats it. :)
Version: 6.0.4
Text Domain: login-sidebar-widget
Domain Path: /languages
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/**
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
**/

define( 'LSW_DIR_NAME', 'login-sidebar-widget' );
define( 'LSW_DIR_PATH', dirname( __FILE__ ) );

// CONFIG
include_once LSW_DIR_PATH . '/config/config_emails.php';
include_once LSW_DIR_PATH . '/config/config_default_fields.php';

function plug_install_lsw(){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'fb-login-widget-pro/login.php' ) || is_plugin_active( 'social-login-no-setup/login.php' ) ) {
	 	wp_die('It seems you have <strong>Facebook Login Widget (PRO)</strong> or <strong>Social Login No Setup</strong> plugin activated. Please deactivate that to continue.');
		exit;
	}
	
	include_once LSW_DIR_PATH . '/includes/class_settings.php';
	include_once LSW_DIR_PATH . '/includes/class_scripts.php';
	include_once LSW_DIR_PATH . '/includes/class_form.php';
	include_once LSW_DIR_PATH . '/includes/class_forgot_password.php';
	include_once LSW_DIR_PATH . '/includes/class_message.php';
	include_once LSW_DIR_PATH . '/includes/class_login_log_adds.php';
	include_once LSW_DIR_PATH . '/includes/class_security.php';
	include_once LSW_DIR_PATH . '/includes/class_login_log.php';
	include_once LSW_DIR_PATH . '/includes/class_paginate.php';
	include_once LSW_DIR_PATH . '/includes/class_login_form.php';
	include_once LSW_DIR_PATH . '/login_ap_widget.php';
	include_once LSW_DIR_PATH . '/process.php';
	include_once LSW_DIR_PATH . '/login_ap_widget_shortcode.php';
	include_once LSW_DIR_PATH . '/functions.php';
	
	new login_settings;
	new login_scripts;
	new ap_login_log;
	new ap_login_form;
}

class lsw_init_load {
	function __construct() {
		plug_install_lsw();
	}
}

new lsw_init_load;

add_action( 'widgets_init', function(){ register_widget( 'login_wid' ); } );

add_action( 'init', 'login_validate' );
add_action( 'init', 'forgot_pass_validate' );

add_shortcode( 'login_widget', 'login_widget_ap_shortcode' );
add_shortcode( 'forgot_password', 'forgot_password_ap_shortcode' );

add_action( 'plugins_loaded', 'security_init' );

add_action( 'plugins_loaded', 'login_widget_ap_text_domain' );

add_filter( 'lsw_login_errors', 'lsw_login_error_message', 10, 1 );

add_filter( 'lwws_user_captcha_field', 'lwws_user_captcha_field_no_auto', 10, 1 );

add_filter( 'lwws_admin_captcha_field', 'lwws_user_captcha_field_no_auto', 10, 1 );

add_action( 'template_redirect', 'start_session_if_not_started' );

register_activation_hook( __FILE__, 'lsw_setup_init' );