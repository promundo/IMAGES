<?php
/**
* Plugin Name: Print Survey
* Description: Export variables to docx file.
* Version: 1.0
**/

function print_survey_export_file($variables, $filename){
  require_once 'PhpWord/bootstrap.php';


  $dir = plugin_dir_path( __FILE__ );
  $template = $dir . 'resources/template.docx';
  $upload_dir = wp_upload_dir('temp');
  $resuts = $upload_dir['path'] . $filename;
  $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template);


  if (!empty($variables)) {
    $count = count($variables);
    $templateProcessor->cloneBlock('variableBlock', $count, true, true);
    foreach ($variables as $key => $id) {
      $answer = '';
      $response_options = get_field( "response_options", $id);
      $question = get_field( "question_text", $id);
      if (!empty($response_options)) {
        $response_options_value = [];
        $count = 0;
        foreach ($response_options as $response_key => $response_option) {
          //$response_options_value[] = '1) ' . $response_option['label'].' '.$response_option['value'];
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
      $templateProcessor->setValue('variableQuestion#'.$number, $question);
      $templateProcessor->setValue('variableAnswer#'.$number, $answer);
    }
  }

  $templateProcessor->saveAs($resuts);

}


if( ! wp_next_scheduled( 'print_survey_cron' ) ) {
  wp_schedule_event( time(), 'hourly', 'print_survey_cron');
}

add_action('print_survey_cron', 'print_survey_test_export_file');

function print_survey_test_export_file(){
  //$variables = get_field( 'variables', 'user_1' );
  //print_survey_export_file($variables,'cron-results.docx');
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
  $user_id = $_POST['user_id'];
  if ($user_id > 0) {
    $survey_name = $_POST['survey_name'];
    if (empty($survey_name)) $survey_name = 'survey-name';
    $survey_name = trim($survey_name);
    $survey_name = strtolower($survey_name);
    $filename = $survey_name.'.docx';
    
    $id = "user_" . $user_id;
    $variables = get_field( 'variables', $id );

    print_survey_export_file($variables,$filename);
    update_field( 'print_survey', 1, $id );
    update_field( 'autoload_survey', 0, $id );
    update_field( 'variables', [], $id );

    $upload_dir = wp_upload_dir('temp');
    $data['url'] = $upload_dir['url'] . $filename;
  }

  echo json_encode($data);
  wp_die();
}
