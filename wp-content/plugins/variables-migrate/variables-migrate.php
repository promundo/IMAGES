<?php 
/*
Plugin Name: Variables migrate
Plugin URI: none
Description: Plugin for migrate Variables
Version: 1.0
Author: webcodingstudio
Author URI: none
License: GPL2
*/

if ( ! function_exists( 'wp_handle_upload' ) ) 
  require_once( ABSPATH . 'wp-admin/includes/file.php' );

add_action( 'admin_menu', 'variables_migrate_add_admin_menu' );

function variables_migrate_add_admin_menu(  ) { 
  add_options_page( 'Migrate Variables', 'Migrate Variables', 'manage_options', 'variables_migrate', 'variables_migrate_options_page' );
}

function variables_migrate_options_page(){
  wp_register_script( 'variables-migrate-script', plugins_url( '/js/main.js', __FILE__ ), array( 'jquery' ) );
  wp_enqueue_script( 'variables-migrate-script' );

  $dir = wp_upload_dir();
  if(file_exists($dir['basedir'] . '/custom-migrate') == FALSE){
    mkdir($dir['basedir'] . '/custom-migrate');
  }
  $path = $dir['basedir'] . '/custom-migrate/';
  $migrate_files = array_diff(scandir($path), array('.', '..'));
  ?>
    <h1>Variables Migrate</h1>
    
    <form method="post" id="migrate-script" enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
      <?php if(!empty($migrate_files)): ?>
        <p>Current File: <?php echo reset($migrate_files); ?></p>
      <?php endif; ?>
      <input type="file" name="file-upload" id="file-upload">
      <input type='hidden' name='action' value='upload_migrate_file'>
      <?php submit_button("Upload File",'button-primary','upload-file');?>
      <div class="process" style="margin:10px 0 -10px 0; min-height: 20px;">&nbsp;</div>
      <?php submit_button("Migrate Variables",'button-primary','migrate');?>
    </form>
  <?php
}

add_action('wp_ajax_start_variables_migrate', 'variables_migrate_start_migrate');

if (isset($_POST['action'])) {
  if ($_POST['action'] == 'upload_migrate_file' && isset($_FILES['file-upload'])) {
    if(is_uploaded_file($_FILES['file-upload']["tmp_name"])){
      $info = pathinfo($_FILES['file-upload']["name"]);
      if (isset($info['extension'])) {
        if ($info['extension'] == 'csv') {
          $dir = wp_upload_dir();
          if(file_exists($dir['basedir'] . '/custom-migrate') == FALSE){
            mkdir($dir['basedir'] . '/custom-migrate');
          }
          else{
            array_map('unlink', array_filter((array) glob($dir['basedir'] . "/custom-migrate/*")));
          }
          move_uploaded_file($_FILES['file-upload']["tmp_name"] , $dir['basedir'] . '/custom-migrate/' . $_FILES['file-upload']['name']);
        }
      }
    }
  }
}

function variables_migrate_start_migrate(){
  $result = variables_migrate_start_migrate_ajax($_POST['count']);
  print $result;
  wp_die();
}

function variables_migrate_get_term_id_by_name($name, $taxonomy){
  $term_id = null;
  $term = get_term_by('name', $name, $taxonomy);

  if (empty($term)) {
    $term = wp_insert_term($name, $taxonomy);
    $term_id = $term['term_id'];
  }
  else{
    $term_id = $term->term_id;
  }

  return (int)$term_id;
}

function variables_migrate_start_migrate_ajax($count){
  global $wpdb;

  $dir = wp_upload_dir();
  $path = $dir['basedir'] . '/custom-migrate/';
  $migrate_files = array_diff(scandir($path), array('.', '..'));

  if (!empty($migrate_files)) {
    $path_file = $path . reset($migrate_files);
    $count_rows = count(file($path_file, FILE_SKIP_EMPTY_LINES));

    $limit = 10;
    if($count != 0){
      $count = $count * $limit; 
    }

    $row = 0;
    if ($count_rows >= $count) {
      if (($handle = fopen($path_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
          if (($row >= ($count + 1)) && ($row < ($count + $limit + 1))) {
            $variable_name = trim($data[0]);
            $scale = trim($data[1]);
            $question = trim($data[2]);
            $theme = trim($data[3]);
            $cross_theme = trim($data[4]);
            $country = ucfirst(strtolower(trim($data[5])));
            $gender = strtolower(trim($data[6]));
            $year = trim($data[7]);
            $language = ucfirst(strtolower(trim($data[8])));
            $response_label = ucfirst(strtolower(str_replace("’", "'", trim($data[9]))));
            $response_value = trim($data[10]);
            $base_segment = ucfirst(strtolower(str_replace("’", "'", trim($data[11]))));
            //$flag = (!empty(trim($data[10]))) ? trim($data[10]) : 0;
            

            switch ($gender) {
              case 'men':
                $who_asked = 'all_men';
                break;
              case 'women':
                $who_asked = 'all_women';
                break;
              default:
                $who_asked = '';
                break;
            }

            $exist_variable = get_posts(array(
              'numberposts' => -1,
              'post_type'   => 'variable',
              'meta_key'    => 'question_text',
              'meta_value'  => $question
            ));

            if (!empty($exist_variable)) {
              $exist_variable = reset($exist_variable);
              $variable_id = $exist_variable->ID;
            }
            else{
              $variable_id = wp_insert_post(array('post_title' => $question, 'post_type' => 'variable', 'post_status' => 'publish'));
            }

            $theme_term_id = '';
            if (!empty($theme)) {
              $theme_term_id = variables_migrate_get_term_id_by_name($theme, 'theme_category');
            }

            $cross_theme_term_id = '';
            if (!empty($cross_theme)) {
              $cross_theme_term_id = variables_migrate_get_term_id_by_name($cross_theme, 'theme_category');
            }

            $scale_term_id = '';
            if (!empty($scale)) {
              $scale_term_id = variables_migrate_get_term_id_by_name($scale, 'scale_category');
            }

            $country_term_id = '';
            if (!empty($country)) {
              $country_term_id = variables_migrate_get_term_id_by_name($country, 'country_category');
            }

            $language_term_id = '';
            if (!empty($language)) {
              $language_term_id = variables_migrate_get_term_id_by_name($language, 'language_category');
            }

            $response = get_field('response', $variable_id);
            $exist_response = FALSE;
            if (!empty($response)) {
              foreach ($response as $key => $response_item) {
                if (($response_item['country']->term_id == $country_term_id) && ($response_item['year'] == $year) && ($response_item['language']->term_id == $language_term_id) && ($response_item['who_asked']['value'] == $who_asked) && ($response_item['gender']['value'] == $gender)) {
                  $exist_response = TRUE;
                }

                $response[$key]['country'] = $response_item['country']->term_id;
                $response[$key]['year'] = $response_item['year'];
                $response[$key]['language'] = $response_item['language']->term_id;
                $response[$key]['who_asked'] = $response_item['who_asked']['value'];
                $response[$key]['gender'] = $response_item['gender']['value'];
              }
            }
            if (!$exist_response) {
              $response[] = [
                "country" => $country_term_id,
                "year" => $year,
                "language" => $language_term_id,
                "who_asked" => $who_asked,
                "gender" => $gender
              ];
            }

            $response_options = get_field('response_options', $variable_id);
            $exist_response_option = FALSE;
            if (!empty($response_options)) {
              foreach ($response_options as $key => $response_option) {
                if ($response_option['label'] == $response_label && $response_option['value'] == $response_value) {
                  $exist_response_option = TRUE;
                  break;
                }
              }
            }

            if (!$exist_response_option) {
              $response_options[] = [
                "label" => $response_label,
                "value" => $response_value
              ];
            }

            update_field('response_options', $response_options, $variable_id);
            update_field('response', $response, $variable_id);
            update_field('question_text', $question, $variable_id);

            $current_cross_theme = get_field( "cross_theme", $variable_id );
            $cross_theme_new = [$cross_theme_term_id];
            if (!empty($current_cross_theme)) {
              $cross_theme_new = array_merge($cross_theme_new, $current_cross_theme);
            }

            update_field('cross_theme', $cross_theme_new, $variable_id);
            update_field('variable_name', $variable_name, $variable_id);
            update_field('base_segment', $base_segment, $variable_id);
            //update_field('survey_flag', $flag, $variable_id);
            
            wp_set_post_terms($variable_id, $theme_term_id, 'theme_category', true);
            wp_set_post_terms($variable_id, $scale_term_id, 'scale_category', true);
          }
          $row++;
        }
        return 1;
      }
      else{
        return 0;
      }
    }
    else{
      return 0;
    }
  }
  else{
    return 0;
  }
}