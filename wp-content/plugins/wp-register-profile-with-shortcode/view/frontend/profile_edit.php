<form name="profile" id="profile" method="post" <?php do_action('wprp_profile_form_tag');?>>
<input type="hidden" name="option" value="afo_user_edit_profile" />
<input type="hidden" name="redirect" value="<?php echo sanitize_text_field( wprp_register_process::curPageURL() ); ?>">

    <div class="reg-form-group">
    <label for="name"><?php _e('Username','wp-register-profile-with-shortcode');?> </label>
    <input type="text" required value="<?php echo $current_user->user_login;?>" disabled <?php do_action( 'wprp_profile_user_login_field' );?>/>
    </div>
    
    <div class="reg-form-group">
    <label for="name"><?php _e('User Email','wp-register-profile-with-shortcode');?> </label>
    <input type="email" name="user_email" value="<?php echo $current_user->user_email;?>" required <?php do_action( 'wprp_profile_user_email_field' );?>/>
    </div>

    <?php if($this->is_field_enabled('firstname_in_profile')){ ?>
    <div class="reg-form-group">
    <label for="name"><?php _e('First Name','wp-register-profile-with-shortcode');?> </label>
    <input type="text" name="first_name" <?php echo $this->is_field_required('is_firstname_required');?> placeholder="First Name" value="<?php echo $current_user->first_name;?>" <?php do_action( 'wprp_profile_first_name_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($this->is_field_enabled('lastname_in_profile')){ ?>
    <div class="reg-form-group">
    <label for="name"><?php _e('Last Name','wp-register-profile-with-shortcode');?> </label>
    <input type="text" name="last_name" <?php echo $this->is_field_required('is_lastname_required');?> placeholder="Last Name" value="<?php echo $current_user->last_name;?>" <?php do_action( 'wprp_profile_last_name_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($this->is_field_enabled('displayname_in_profile')){ ?>
    <div class="reg-form-group">
    <label for="name"><?php _e('Display Name','wp-register-profile-with-shortcode');?> </label>
    <input type="text" name="display_name" <?php echo $this->is_field_required('is_displayname_required');?> placeholder="Display Name" value="<?php echo $current_user->display_name;?>" <?php do_action( 'wprp_profile_display_name_field' );?>/>
    </div>
    <?php } ?>
    
    <?php if($this->is_field_enabled('userdescription_in_profile')){ ?>
    <div class="reg-form-group">
    <label for="name"><?php _e('About User','wp-register-profile-with-shortcode');?> </label>
    <textarea name="description" <?php echo $this->is_field_required('is_userdescription_required');?> <?php do_action( 'wprp_profile_description_field' );?>><?php echo get_the_author_meta( 'description', $user_id );?></textarea>
    </div>
    <?php } ?>
    
    <?php if($this->is_field_enabled('userurl_in_profile')){ ?>
    <div class="reg-form-group">
    <label for="name"><?php _e('Website','wp-register-profile-with-shortcode');?> </label>
    <input type="url" name="user_url" <?php echo $this->is_field_required('is_userurl_required');?> placeholder="User URL" value="<?php echo get_the_author_meta( 'user_url', $user_id );?>" <?php do_action( 'wprp_profile_user_url_field' );?>/>
    </div>
    <?php } ?>
    
    <div class="reg-form-group">
    <input name="profile" type="submit" value="<?php _e('Update','wp-register-profile-with-shortcode');?>" <?php do_action( 'wprp_profile_form_submit_tag' );?>/>
    </div>		
</form>