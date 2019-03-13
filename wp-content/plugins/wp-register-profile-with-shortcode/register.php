<?php
/*
Plugin Name: WP Register Profile With Shortcode
Plugin URI: https://wordpress.org/plugins/wp-register-profile-with-shortcode/
Description: This is a simple registration form in the widget. just install the plugin and add the register widget in the sidebar. Thats it. :)
Version: 3.5.0
Text Domain: wp-register-profile-with-shortcode
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

// CONFIG

define( 'WPRPWS_DIR_NAME', 'wp-register-profile-with-shortcode' );

define( 'WPRPWS_DIR_PATH', dirname( __FILE__ ) );

include_once WPRPWS_DIR_PATH . '/config/config_emails.php';

include_once WPRPWS_DIR_PATH . '/config/config_default_fields.php';

// CONFIG

function wrrp_plug_install(){
	
	include_once WPRPWS_DIR_PATH . '/includes/class_settings.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_scripts.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_form.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_admin_security.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_edit_profile.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_password_update.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_register_process.php';
	include_once WPRPWS_DIR_PATH . '/includes/class_register_form.php';
	include_once WPRPWS_DIR_PATH . '/register_afo_widget.php';
	include_once WPRPWS_DIR_PATH . '/register_afo_widget_shortcode.php';
	include_once WPRPWS_DIR_PATH . '/functions.php';
	
	new register_settings;
	new register_scripts;
	new profile_edit_afo;
	new rwws_update_password;
	new register_admin_security;
	new wprp_register_process;
	
}

class wp_register_init {
	function __construct() {
		wrrp_plug_install();
	}
}
new wp_register_init;

function wp_register_profile_set_default_data() {
	global $wprw_mail_to_user_subject, $wprw_mail_to_user_body;
	
	if( get_option( 'new_user_register_mail_subject' ) == '' ){
		update_option( 'new_user_register_mail_subject', $wprw_mail_to_user_subject );
	}
	if( get_option( 'new_user_register_mail_body' ) == '' ){
		update_option( 'new_user_register_mail_body', $wprw_mail_to_user_body );
	}
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( !is_plugin_active( 'wp-user-subscription/subscription.php' ) ) {
	 	delete_option( 'enable_subscription' );
	}
	
}

register_activation_hook( __FILE__, 'wp_register_profile_set_default_data' );

add_shortcode( 'rp_register_widget', 'register_widget_pro_afo_shortcode' );

add_shortcode( 'rp_profile_edit', 'user_profile_edit_pro_afo_shortcode' );

add_shortcode( 'rp_update_password', 'user_password_afo_shortcode' );

add_shortcode( 'rp_user_data', 'get_user_data_afo' );

add_action( 'wprp_after_insert_user', 'wprp_set_user_flag', 1, 1 );

add_action( 'widgets_init', function(){ register_widget( 'register_wid' ); } );

add_action( 'plugins_loaded', 'wp_register_profile_text_domain' );
