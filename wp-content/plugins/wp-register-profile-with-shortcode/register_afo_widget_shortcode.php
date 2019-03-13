<?php

function register_widget_pro_afo_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$rf = new register_form;
	$rf->registration_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function user_profile_edit_pro_afo_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	$pea = new profile_edit_afo;
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$pea->profile_edit();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function user_password_afo_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	$up_afo = new rwws_update_password;
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$up_afo->update_password_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function get_user_data_afo( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'field' => '',
		  'user_id' => '',
     ), $atts ) );
     
	 $error = false;
	 if( empty($atts['user_id']) and is_user_logged_in()){
	 	$user_id = get_current_user_id();
	 } elseif( !empty($atts['user_id'])){
	 	$user_id = $atts['user_id'];
	 } else if( empty($atts['user_id']) and !is_user_logged_in() ){
	 	$error = true;
	 }
	 if(!$error){
	 	$ret = get_the_author_meta( $atts['field'], $user_id );
	 } else {
	 	$ret = __('Sorry. no user was found!','wp-register-profile-with-shortcode');
	 }
		
	 return $ret;
}

function rp_user_data_func($field='',$user_id=''){
	echo do_shortcode('[rp_user_data field="'.$field.'" user_id="'.$user_id.'"]');
}