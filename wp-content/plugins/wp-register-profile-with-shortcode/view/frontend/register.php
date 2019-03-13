<form name="register" id="register" method="post" action="" <?php do_action('wprp_register_form_tag');?>>
<input type="hidden" name="option" value="afo_user_register" />
<input type="hidden" name="redirect" value="<?php echo sanitize_text_field( wprp_register_process::curPageURL() ); ?>" />
    
    <?php if($wprp_p->is_field_enabled('username_in_registration')){ ?>
    <div class="reg-form-group">
        <label for="username"><?php _e('Username','wp-register-profile-with-shortcode');?> </label>
        <input type="text" name="user_login" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['user_login']);?>" required placeholder="<?php _e('Username','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_login_field' );?>/>
    </div>
    <?php } ?>
    
    <div class="reg-form-group">
        <label for="useremail"><?php _e('User Email','wp-register-profile-with-shortcode');?> </label>
        <input type="email" name="user_email" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['user_email']);?>" required placeholder="<?php _e('User Email','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_email_field' );?>/>
    </div>
    
    <?php if($wprp_p->is_field_enabled('password_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="password"><?php _e('Password','wp-register-profile-with-shortcode');?> </label>
    <input type="password" name="new_user_password" required placeholder="<?php _e('Password','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_new_user_password_field' );?>/>
    </div>
    
    <div class="reg-form-group">
    <label for="retypepassword"><?php _e('Retype Password','wp-register-profile-with-shortcode');?> </label>
    <input type="password" name="re_user_password" required placeholder="<?php _e('Retype Password','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_re_user_password_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($wprp_p->is_field_enabled('firstname_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="firstname"><?php _e('First Name','wp-register-profile-with-shortcode');?> </label>
    <input type="text" name="first_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['first_name']);?>" <?php echo $wprp_p->is_field_required('is_firstname_required');?> placeholder="<?php _e('First Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_first_name_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($wprp_p->is_field_enabled('lastname_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="lastname"><?php _e('Last Name','wp-register-profile-with-shortcode');?> </label>
    <input type="text" name="last_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['last_name']);?>" <?php echo $wprp_p->is_field_required('is_lastname_required');?> placeholder="<?php _e('Last Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_last_name_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($wprp_p->is_field_enabled('displayname_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="displayname"><?php _e('Display Name','wp-register-profile-with-shortcode');?> </label>
    <input type="text" name="display_name" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['display_name']);?>" <?php echo $wprp_p->is_field_required('is_displayname_required');?> placeholder="<?php _e('Display Name','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_display_name_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($wprp_p->is_field_enabled('userdescription_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="aboutuser"><?php _e('About User','wp-register-profile-with-shortcode');?> </label>
    <textarea name="description" <?php echo $wprp_p->is_field_required('is_userdescription_required');?> <?php do_action( 'wprp_description_field' );?>><?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['description']);?></textarea>
    </div>
    <?php } ?>
    
    <?php if($wprp_p->is_field_enabled('userurl_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="website"><?php _e('Website','wp-register-profile-with-shortcode');?> </label>
    <input type="url" name="user_url" value="<?php echo sanitize_text_field(@$_SESSION['wp_register_temp_data']['user_url']);?>" <?php echo $wprp_p->is_field_required('is_userurl_required');?> placeholder="<?php _e('Website','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_user_url_field' );?>/>
    </div>
    <?php } ?>
    
    <?php do_action('wp_register_profile_subscription'); ?>
    
    <?php if($wprp_p->is_field_enabled('captcha_in_registration')){ ?>
    <div class="reg-form-group">
    <label for="captcha"><?php _e('Captcha','wp-register-profile-with-shortcode');?> </label>
    <?php $this->captcha_image();?>
    <input type="text" name="user_captcha" autocomplete="off" required <?php do_action( 'wprp_user_captcha_field' );?>/>
    </div>
    <?php } ?>
    
    <?php $default_registration_form_hooks == 'Yes'?do_action('register_form'):'';?>
    
    <?php do_action('wp_register_profile_form');?>
    
    <div class="reg-form-group"><input name="register" type="submit" value="<?php _e('Register','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_register_form_submit_tag' );?>/></div>

</form>