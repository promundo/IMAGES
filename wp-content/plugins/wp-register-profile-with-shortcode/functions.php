<?php

if(!function_exists( 'start_session_if_not_started' )){
	function start_session_if_not_started(){
		if(!session_id()){
			@session_start();
		}
	}
}

if(!function_exists('wprp_set_user_flag')){
	function wprp_set_user_flag( $user_id = '' ){
		if( $user_id ){
			update_user_meta( $user_id, 'user_reg_with_wprp', 'Yes' );
		}
	}
}

if(!function_exists('wprw_set_html_content_type')){
	function wprw_set_html_content_type() {
		return 'text/html';
	}
}

if(!function_exists('wp_register_profile_text_domain')){
	function wp_register_profile_text_domain(){
		load_plugin_textdomain('wp-register-profile-with-shortcode', FALSE, basename( WPRPWS_DIR_PATH ) .'/languages');
	}
}