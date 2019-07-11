<?php 
/*
Plugin Name: Variables update
Plugin URI: none
Description: Plugin for update Variables
Version: 1.0
Author: webcodingstudio
Author URI: none
License: GPL2
*/

if ( ! function_exists( 'wp_handle_upload' ) ) 
  require_once( ABSPATH . 'wp-admin/includes/file.php' );

add_action( 'admin_menu', 'variables_update_add_admin_menu' );

function variables_update_add_admin_menu(  ) { 
  add_options_page( 'Update Variables', 'Update Variables', 'manage_options', 'variables_update', 'variables_update_options_page' );
}

function variables_update_options_page(){
  wp_register_script( 'variables-update-script', plugins_url( '/js/main.js', __FILE__ ), array( 'jquery' ) );
  wp_enqueue_script( 'variables-update-script' );

  ?>
    <h1>Variables Update</h1>
    <form method="post" id="update-script" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
      <div class="process" style="margin:10px 0 -10px 0; min-height: 20px;">&nbsp;</div>
      <?php submit_button("Update Variables",'button-primary','update');?>
    </form>
  <?php
}

add_action('wp_ajax_start_variables_update', 'variables_update_start_update');

function variables_update_start_update(){
  $result = variables_update_start_update_ajax($_POST['count']);
  print $result;
  wp_die();
}

function variables_update_start_update_ajax($count){
  global $wpdb;

  $type = 'variable';
  $limit = 10;
  if($count != 0){
    $count = $count * $limit; 
  }

  $post_ids = $wpdb->get_results($wpdb->prepare("SELECT $wpdb->posts.ID FROM $wpdb->posts WHERE $wpdb->posts.post_type = %s ORDER BY $wpdb->posts.ID DESC LIMIT %d,%d", $type, $count, $limit), ARRAY_A);
  if (!empty($post_ids)) {
    foreach ($post_ids as $key => $post) {
      $term_theme_order = 100;
      $theme_category = wp_get_post_terms( $post['ID'], 'theme_category');
      if (!empty($theme_category)) {
        if (isset($theme_category[0]->term_order)) {
          $term_theme_order = $theme_category[0]->term_order;
        }
      }

      update_field('theme_weight', $term_theme_order, $post['ID']);

      $term_scale_order = 100;
      $scale_category = wp_get_post_terms( $post['ID'], 'scale_category');
      if (!empty($scale_category)) {
        if (isset($scale_category[0]->term_order)) {
          $term_scale_order = $scale_category[0]->term_order;
        }
      }

      update_field('scale_weight', $term_scale_order, $post['ID']);

      $min_year = '';
      $max_year = '';
      $response = get_field('response', $post['ID']);

      if (!empty($response)) {
        foreach ($response as $response_key => $response_data) {
          if (empty($max_year)) {
            $max_year = $response_data['year'];
          }

          if ($max_year < $response_data['year']) {
            $max_year = $response_data['year'];
          }

          if (empty($min_year)) {
            $min_year = $response_data['year'];
          }

          if ($min_year > $response_data['year']) {
            $min_year = $response_data['year'];
          }
        }
      }

      update_field('min_year', $min_year, $post['ID']);
      update_field('max_year', $max_year, $post['ID']);
    }
    return 1;
  }
  else{
    return 0;
  }
}