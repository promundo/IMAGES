<?php
/**
* Plugin Name: CSV Variables
* Description: Export variables to csv file.
* Version: 1.0
**/

if (!function_exists('csv_variables_enqueue_scripts')) {

  function csv_variables_enqueue_scripts(){
    wp_enqueue_script( 'csv-variables-js-main', plugin_dir_url( __FILE__ ) . '/js/plugin.js',array( 'jquery' ));

    wp_localize_script('csv-variables-js-main', 'pluginCsvAjax', 
      array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajaxNonce')
      )
    );

  }
}

add_action('wp_enqueue_scripts', 'csv_variables_enqueue_scripts', 90);

add_action( 'wp_ajax_nopriv_export-csv-variables', 'csv_variables_export_action' );
add_action( 'wp_ajax_export-csv-variables', 'csv_variables_export_action' );
function csv_variables_export_action(){
  check_ajax_referer( 'ajaxNonce', 'nonce_code' );
  $data = [];
  session_start();
  $variables = $_SESSION['user_variables'];
  if (!empty($variables)) {
    $data[] = ["New Variable Name", "Variable Name", "Response Options", "Countries", "Years", "Languages", "Theme", "Scale"];
    foreach ($variables as $key => $variable_id) {
      $value = get_post($variable_id);
      $response = get_field( "response", $variable_id);
      $row = [];
      $row['New Variable Name'] = get_field( "variable_name", $variable_id);
      $row['Variable Name'] = get_field( "question_text", $variable_id);
      $row['Response Options'] = '';
      $row['Countries'] = '';
      $row['Years'] = '';
      $row['Languages'] = '';
      $row['Theme'] = '';
      $row['Scale'] = '';

      $response_options = get_field( "response_options", $variable_id);
      if (!empty($response_options)) {
        $response_options_value = [];
        foreach ($response_options as $response_key => $response_option) {
          $response_options_value[] = $response_option['value'] . ' = ' . $response_option['label'];
        }
        if (!empty($response_options_value)) {
          $row['Response Options'] = implode(';', array_unique($response_options_value));
        }
      }

      $theme_category = wp_get_post_terms( $variable_id, 'theme_category', ['fields' => 'names']);
      if (!empty($theme_category)) {
        $row['Theme'] = implode(';', $theme_category);
      }

      $scale_category = wp_get_post_terms( $variable_id, 'scale_category', ['fields' => 'names']);
      if (!empty($scale_category)) {
        $row['Scale'] = implode(';', $scale_category);
      }

      if (!empty($response)) {
        $country_codes = [];
        $language = [];
        $years = [];
        foreach ($response as $response_value) {
          if (!empty($response_value['country'])) {
            $country_code = get_field('code', 'country_category_' . $response_value['country']->term_id);
            $country_codes[] = $country_code;
          }

          if (!empty($response_value['language'])) {
            $language[] = $response_value['language']->name;
          }

          if (!empty($response_value['year'])) {
            $years[] = $response_value['year'];
          }
        }
        if (!empty($country_codes)) {
          $row['Countries'] = implode(';', array_filter(array_unique($country_codes)));
        }
        if (!empty($language)) {
          $row['Languages'] = implode(';', array_filter(array_unique($language)));
        }
        if (!empty($years)) {
          $row['Years'] = implode(';', array_filter(array_unique($years)));
        }
      }
      if (!empty($row)) {
        $data[] = $row;
      }
    }
  }

  echo csv_variables_generate_csv($data, "\t");
  wp_die();
}

function csv_variables_generate_csv($data, $delimiter = ',', $enclosure = '"') {
  $handle = fopen('php://temp', 'r+');
  foreach ($data as $line) {
    fputcsv($handle, $line, $delimiter, $enclosure);
  }
  rewind($handle);
  while (!feof($handle)) {
    $contents .= fread($handle, 18192);
  }
  fclose($handle);
  
  return $contents;
}