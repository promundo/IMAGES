<?php
/**
* Plugin Name: Print Survey
* Description: Export variables to docx file.
* Version: 1.0
**/

function print_survey_export_file($variables, $filename, $session_id){
  require_once 'PhpWord/bootstrap.php';


  $dir = plugin_dir_path( __FILE__ );
  $template = $dir . 'resources/template.docx';
  $upload_dir = wp_get_upload_dir();
  $resuts = $upload_dir['basedir'] . '/print-survey/' . $session_id . '/'. $filename;
  $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template);


  if (!empty($variables)) {
    $count = count($variables);
    $templateProcessor->cloneBlock('variableBlock', $count, true, true);
    foreach ($variables as $key => $id) {
      $answer = '';
      $response_options = get_field( "response_options", $id);
      $question = get_field( "question_text", $id);
      $scale_terms = get_the_term_list($id,'scale_category', 'SCALE: ',', ','');
      $scale = '';
      $baseSegment = get_field( "base_segment", $id); 

      if (!empty($baseSegment)) {
        $baseSegment .= "\r\n"."<w:br/>";
      }
      if (!empty($scale_terms)) {
        $scale = strip_tags($scale_terms);
      }
      if (!empty($response_options)) {
        $response_options_value = [];
        $count = 0;
        foreach ($response_options as $response_key => $response_option) {
          $count++;
          if (!empty($response_option['label'])) {
            $response_options_value[] = $count . ') ' . $response_option['label'];
          }
          
        }
        if (!empty($response_options_value)) {
          $answer = implode("\r\n"."<w:br/>", array_unique($response_options_value));
        }
      }
      $number = $key + 1;
      $templateProcessor->setValue('variableScale#'.$number, $scale);
      $templateProcessor->setValue('variableBaseSegment#'.$number, $baseSegment);
      $templateProcessor->setValue('variableQuestion#'.$number, $question);
      $templateProcessor->setValue('variableAnswer#'.$number, $answer);
    }
  }

  $templateProcessor->saveAs($resuts);

}

/*
if( ! wp_next_scheduled( 'print_survey_cron_export_file' ) ) {
  wp_schedule_event( time(), 'hourly', 'print_survey_cron_export_file');
}

add_action('print_survey_cron_export_file', 'print_survey_test_export_file');

function print_survey_test_export_file(){
  //$variables = get_field( 'variables', 'user_1' );
  //print_survey_export_file($variables,'cron-results.docx',1);
}
*/

if( ! wp_next_scheduled( 'print_survey_cron_delete_files' ) ) {
  wp_schedule_event( time(), 'hourly', 'print_survey_cron_delete_files');
}

add_action('print_survey_cron_delete_files', 'print_survey_delete_files');

function print_survey_delete_files(){
  $upload_dir = wp_get_upload_dir();
  $print_dir = $upload_dir['basedir'] . '/print-survey/';
  $files_dir = array_diff(scandir($print_dir), array('.', '..'));

  $time = time();

  foreach ($files_dir as $dir) {
    $dir_path = $upload_dir['basedir'] . '/print-survey/'.$dir.'/';
    $files = array_diff(scandir($dir_path), array('.', '..'));
    foreach ($files as $file) {
      $file_time = filemtime($dir_path.$file);
      if ($time - $file_time > 3600) {
        unlink($dir_path.$file);
      }
    }
  }
}


if (!function_exists('print_survey_enqueue_scripts')) {

  function print_survey_enqueue_scripts(){

    wp_enqueue_script( 'print-survey-js-main', plugin_dir_url( __FILE__ ) . 'js/plugin.js',array( 'jquery' ));

    wp_localize_script('print-survey-js-main', 'pluginSurveyAjax', 
      array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajaxNonce')
      )
    );

  }
}

add_action('wp_enqueue_scripts', 'print_survey_enqueue_scripts', 90);

add_action( 'wp_ajax_nopriv_export-survey-variables', 'print_survey_export_action' );
add_action( 'wp_ajax_export-survey-variables', 'print_survey_export_action' );

function print_survey_export_action(){

  check_ajax_referer( 'ajaxNonce', 'nonce_code' );
  $data = array();
  $data['url'] = '';
  session_start();
  $session_id = session_id();
  $variables = $_SESSION['user_variables'];

  if (!empty($session_id)) {
    $survey_name = $_POST['survey_name'];
    if (empty($survey_name)) $survey_name = 'survey-name';
    $survey_name = trim($survey_name);
    $survey_name = strtolower($survey_name);
    $filename = $survey_name . '.docx';
    
    $upload_dir = wp_get_upload_dir();
    $dir_path = $upload_dir['basedir'] . '/print-survey/' . $session_id;
    if (!file_exists($dir_path)) {
        mkdir($dir_path, 0755, true);
    }

    print_survey_export_file($variables, $filename, $session_id);
    //update_field( 'print_survey', 1, $id );
    //update_field( 'autoload_survey', 0, $id );
    //update_field( 'variables', [], $id );

    
    $data['url'] = $upload_dir['baseurl'] . '/print-survey/' . $session_id . '/'. $filename;
  }

  echo json_encode($data);
  wp_die();
}


