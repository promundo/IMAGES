<form name="update-password" id="update-password" method="post" action="" <?php do_action('wprp_update_pass_form_tag');?>>
<input type="hidden" name="option" value="rpws_user_update_password" />
<input type="hidden" name="redirect" value="<?php echo sanitize_text_field( wprp_register_process::curPageURL() ); ?>">

    
    <div class="reg-form-group">
    <label for="name"><?php _e('New Password','wp-register-profile-with-shortcode');?> </label>
    <input type="password" name="user_new_password" required <?php do_action( 'wprp_update_pass_user_new_password_field' );?>/>
    </div>
    
    <div class="reg-form-group">
    <label for="name"><?php _e('Retype Password','wp-register-profile-with-shortcode');?> </label>
    <input type="password" name="user_retype_password" required <?php do_action( 'wprp_update_pass_user_retype_password_field' );?>/>
    </div>

    <div class="reg-form-group">
    <input name="profile" type="submit" value="<?php _e('Update','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_update_pass_form_submit_tag' );?>/>
    </div>	
</form>