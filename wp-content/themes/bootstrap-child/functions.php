<?php


if (!function_exists('bootstrap_child_enqueue_scripts')) {

  function bootstrap_child_enqueue_scripts(){
    wp_enqueue_style( 'bootstrap-child-cabin-fonts', 'https://fonts.googleapis.com/css?family=Cabin:400,400i,500,500i,600,600i');
    wp_enqueue_style( 'bootstrap-child-montserrat-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,400i,500,500i,600,600i');
    wp_enqueue_style( 'bootstrap-child-bootstrap-multiselect', get_stylesheet_directory_uri() . '/css/bootstrap-multiselect.css' );
    wp_enqueue_style( 'bootstrap-child-main', get_stylesheet_directory_uri() . '/css/style.css' );
    
    //wp_enqueue_script( 'plugin-slick', get_stylesheet_directory_uri() . '/js/plugins/slick.min.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'plugin-bootstrap-multiselect', get_stylesheet_directory_uri() . '/js/plugins/bootstrap-multiselect.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'plugin-resizeimage', get_stylesheet_directory_uri() . '/js/plugins/jquery.resizeimagetoparent.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'bootstrap-child-js-main', get_stylesheet_directory_uri() . '/js/functionality.js',array( 'jquery' ));

    wp_localize_script('bootstrap-child-js-main', 'myajax', 
      array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('myajax-nonce')
      )
    );

  }
}

add_action('wp_enqueue_scripts', 'bootstrap_child_enqueue_scripts', 90);


if (!function_exists('bootstrap_child_widgets')) {

  function bootstrap_child_widgets()
  {

    unregister_sidebar( 'footer-left' );
    unregister_sidebar( 'footer-right' );
    unregister_sidebar( 'sidebar-left' );
    unregister_sidebar( 'sidebar-right' );
    unregister_sidebar( 'header-right' );
    unregister_sidebar( 'navbar-right' );


    register_sidebar(array(
      'name'          => __('Header', 'bootstrap_child'),
      'id'            => 'header',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ));

   register_sidebar(array(
      'name'          => __('Footer Left ', 'bootstrap_child'),
      'id'            => 'footer-left',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ));
    register_sidebar(array(
      'name'          => __('Footer Right', 'bootstrap_child'),
      'id'            => 'footer-right',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ));


  }
}
add_action( 'widgets_init', 'bootstrap_child_widgets', 20 );


if (!function_exists('bootstrap_child_theme_setup')) {

  function bootstrap_child_theme_setup()
  {

    add_image_size( 'page_image', 390, 510, true);
  }

}
add_action( 'after_setup_theme', 'bootstrap_child_theme_setup' );

add_filter('body_class','bootstrap_child_add_class');

function bootstrap_child_add_class( $classes ) {
  $post_id = get_the_ID();
  $user_id = get_current_user_id();
  if ($user_id > 0) {
    $id = "user_" . $user_id;
    $variables = get_field( 'variables', $id );
    if (empty($variables)) {
      $classes[] = 'hide-print-survey';
    }
  }

  if ($post_id == '2342') {
    $classes[] = 'contact-form';
  }
  return $classes;
}

/**
 * [current_year_with_copyright_symbol]
 * 
 * Returns the Current Year with a copyright symbol
 */
function get_current_year_with_copyright_symbol() {
  $current_date = getdate();
  return '<span>&copy; ' . $current_date["year"] . '</span>';
}
  
add_shortcode('current_year_with_copyright_symbol', 'get_current_year_with_copyright_symbol');


function bootstrap_child_print_menu_shortcode($atts, $content = null) {
  extract(shortcode_atts(array( 'name' => null, ), $atts));
  return wp_nav_menu( array( 'menu' => $name, 'echo' => false ) );
}
add_shortcode('menu', 'bootstrap_child_print_menu_shortcode');

add_action('lwws_fp_userusername_field', 'bootstrap_child_userusername_field');
add_action('lwws_userusername_field', 'bootstrap_child_userusername_field');

function bootstrap_child_userusername_field(){
  echo 'placeholder="Email"';
}

add_action('lwws_userpassword_field', 'bootstrap_child_userpassword_field');

function bootstrap_child_userpassword_field(){
  echo 'placeholder="Password"';
}

function bootstrap_child_get_countries(){
  $countries = array();
  $terms = get_terms( array(
      'taxonomy' => 'country_category',
      'hide_empty' => true,
      'orderby' => 'name',
      'order' => 'ASC' 
  ) );
  foreach ($terms as $key=>$term) {
    $flag_icon = get_field('flag_icon',$term);
    if (!empty($flag_icon)) {
        $flag = $flag_icon['url'];
    } else $flag = '';
    $countries[$key] = array(
      'id' => $term->term_id,
      'name' => $term->name,
      'flag' => $flag,
    );
  }
  return $countries;
}


add_filter('bootstrap_child_dynamicselect_themes','bootstrap_child_dynamicselect_get_themes',10,2);

function bootstrap_child_dynamicselect_get_themes(){
  $themes = array();
  $terms = get_terms( array(
      'taxonomy' => 'theme_category',
      'hide_empty' => true,
      'orderby' => 'name',
      'order' => 'ASC' 
  ) );
  foreach ($terms as $key=>$term) {
    $themes[$term->name] = $term->name;
  }
  return $themes;
}

function bootstrap_child_get_themes(){
  $themes = array();
  $terms = get_terms( array(
      'taxonomy' => 'theme_category',
      'hide_empty' => true,
      'orderby' => 'name',
      'order' => 'ASC' 
  ) );
  foreach ($terms as $key=>$term) {
    $themes[] = array(
      'id' => $term->term_id,
      'name' => $term->name,
    );
  }
  return $themes;
}

function bootstrap_child_get_scales(){
  $scales = array();
  $terms = get_terms( array(
      'taxonomy' => 'scale_category',
      'hide_empty' => true,
      'orderby' => 'name',
      'order' => 'ASC' 
  ) );
  foreach ($terms as $key=>$term) {
    $scales[] = array(
      'id' => $term->term_id,
      'name' => $term->name,
    );
  }
  return $scales;
}

function bootstrap_child_get_themes_option($default = []){
  $result = '';
  $themes = bootstrap_child_get_themes();
  if (!empty($themes)) {
    foreach ($themes as $key => $theme) {
      $result.= '<option ' . (in_array($theme['id'], $default) ? 'selected ': '') . 'value="' . $theme['id'] . '">' . $theme['name'] . '</option>';
    }
  }

  return $result;
}

function bootstrap_child_get_scales_option($default = []){
  $result = '';
  $scales = bootstrap_child_get_scales();
  if (!empty($scales)) {
    foreach ($scales as $key => $scale) {
      $result.= '<option ' . (in_array($scale['id'], $default) ? 'selected ': '') . 'value="' . $scale['id'] . '">' . $scale['name'] . '</option>';
    }
  }

  return $result;
}


add_filter('bootstrap_child_dynamicselect_countries','bootstrap_child_dynamicselect_get_countries',10,2);

function bootstrap_child_dynamicselect_get_countries(){
  $countries = array();
  $terms = get_terms( array(
      'taxonomy' => 'country_category',
      'hide_empty' => true,
      'orderby' => 'name',
      'order' => 'ASC' 
  ) );
  foreach ($terms as $key=>$term) {
    $countries[$term->name] = $term->name;
  }
  return $countries;
}

function bootstrap_child_get_countries_option($default = []){
  $result = '';
  $countries = bootstrap_child_get_countries();
  if (!empty($countries)) {
    foreach ($countries as $key => $country) {
      $result.= '<option ' . (in_array($country['id'], $default) ? 'selected ' : '') . 'value="' . $country['id'] . '">' . $country['name'] . '</option>';
    }
  }

  return $result;
}

function bootstrap_child_get_sort_option($default = ''){
  $result = '';
  $options = [
    [
      'id' => 'scale',
      'name' => __('Scale', 'bootstrap-child')
    ],
    [
      'id' => 'theme',
      'name' => __('Theme', 'bootstrap-child')
    ],
    [
      'id' => 'year',
      'name' => __('Year', 'bootstrap-child')
    ],
    [
      'id' => 'name_asc',
      'name' => __('Name (A-Z)', 'bootstrap-child')
    ],
    [
      'id' => 'name_desc',
      'name' => __('Name (Z-A)', 'bootstrap-child')
    ]
  ];

  foreach ($options as $key => $option) {
    $result.= '<option ' . (($option['id'] == $default) ? 'selected ' : '') . 'value="' . $option['id'] . '">' . $option['name'] . '</option>';
  }

  return $result;
}

function bootstrap_child_get_years_option($default = []){
  $result = '';
  $years = range(2000, date('Y'));
  rsort($years);
  foreach ($years as $key => $year) {
    $result.= '<option ' . (in_array($year, $default) ? 'selected ' : '') . 'value="' . $year . '">' . $year . '</option>';
  }

  return $result;
}

add_action( 'wp_ajax_nopriv_select-variable', 'bootstrap_child_select_variable' );
add_action( 'wp_ajax_select-variable', 'bootstrap_child_select_variable' );
function bootstrap_child_select_variable(){
  check_ajax_referer( 'myajax-nonce', 'nonce_code' );
  $user_id = get_current_user_id();
  $post_id = $_POST['post_id'];
  $count = 0;
  if ($user_id > 0 && $post_id > 0) {
    $id = "user_" . $user_id;
    $variables_id = [$post_id];
    $variables = get_field( 'variables', $id );
    if (!empty($variables)) {
      foreach ($variables as $key => $value) {
        $variables_id[] = $value;
      }
    }

    $variables_id = array_unique($variables_id);
    update_field( 'variables', $variables_id, $id );
    $count = count($variables_id);
  }

  usleep(300000);

  echo $count;
  wp_die();
}

add_action( 'wp_ajax_nopriv_get-count-variable', 'bootstrap_child_get_count_variable' );
add_action( 'wp_ajax_get-count-variable', 'bootstrap_child_get_count_variable' );
function bootstrap_child_get_count_variable(){
  check_ajax_referer( 'myajax-nonce', 'nonce_code' );
  $user_id = get_current_user_id();
  $count = 0;
  if ($user_id > 0) {
    $id = "user_" . $user_id;
    $variables = get_field( 'variables', $id );
    if (!empty($variables)) {
      $count = count($variables);
    }
  }

  echo $count;
  wp_die();
}

add_action( 'wp_ajax_nopriv_remove-variable', 'bootstrap_child_remove_variable' );
add_action( 'wp_ajax_remove-variable', 'bootstrap_child_remove_variable' );
function bootstrap_child_remove_variable(){
  check_ajax_referer( 'myajax-nonce', 'nonce_code' );
  $result = false;
  $user_id = get_current_user_id();
  $remove_ids = $_POST['var_ids'];
  if ($user_id > 0 && !empty($remove_ids)) {
    $id = "user_" . $user_id;
    $variable_ids = [];
    $variables = get_field( 'variables', $id );
    if (!empty($variables)) {
      $new_ids = array_diff($variables, $remove_ids);
      update_field( 'variables', $new_ids, $id );
      $result = true;
    }
  }

  usleep(300000);

  echo $result;
  wp_die();
}

function bootstrap_child_shortcode_selected_variables(){
  $count = 0;
  $user_id = get_current_user_id();
  if ($user_id > 0) {
    $id = "user_" . $user_id;
    $variables = get_field( 'variables', $id );
    if (!empty($variables)) {
      $count = count($variables);
    }
  }
  
  return $count;
}
add_shortcode('count_variables', 'bootstrap_child_shortcode_selected_variables');

function bootstrap_child_get_survey_variables(){
  $variables = [];
  $user_id = get_current_user_id();
  if ($user_id > 0) {
    $id = "user_" . $user_id;
    $variables = get_field( 'variables', $id );
    if (empty($variables)) {
      $variables = [];
    }
    $survey_variables = get_post_id_by_meta_key_and_value('survey_flag', 1);
    
    if (empty($survey_variables)) {
      $survey_variables = [];
    }

    $autoload_survey = get_field( 'autoload_survey', $id );
    $print_survey = get_field( 'print_survey', $id );

    if ($print_survey) {
      $variables = $survey_variables;
      update_field( 'variables', $survey_variables, $id );
      update_field( 'print_survey', 0, $id );
    }
    elseif (!$autoload_survey) {
      $variables = array_merge($variables, $survey_variables);
      $variables = array_unique($variables);
      update_field( 'variables', $variables, $id );
      update_field( 'autoload_survey', 1, $id );
    }
  }

  return $variables;
}

if (!function_exists('get_post_id_by_meta_key_and_value')) {
  /**
   * Get post id from meta key and value
   * @param string $key
   * @param mixed $value
   * @return int|bool
   */
  function get_post_id_by_meta_key_and_value($key, $value) {
    global $wpdb;
    $post_ids = [];

    $results = $wpdb->get_results( $wpdb->prepare( "SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
    if (!empty($results)) {
      foreach ($results as $key => $value) {
        $post_ids[] = $value->post_id;
      }
    }

    return $post_ids;
  }
}

function bootstrap_child_custom_pagination($param, $total, $current, $prev_text, $next_text){
  $output = '';
  $pagination_align_class = 'justify-content-center';
  $pagination_array = paginate_links( array(
      'base' => add_query_arg( $param, '%#%' ),
      'format' => '',
      'prev_text' => $prev_text,
      'next_text' => $next_text,
      'type' => 'array',
      'total' => $total,
      'current' => $current
  ));

  if (is_array($pagination_array) && !empty($pagination_array)) {
    $output .= '<nav class="pagination-nav-container" aria-label="'.esc_attr__('Page navigation', 'bootstrap-basic4').'">';
    $output .= '<ul class="pagination ' . $pagination_align_class . '">';
    foreach ($pagination_array as $page) {
      $output .= '<li';
      if (strpos($page, '<a') === false && strpos($page, '&hellip;') === false) {
        $output .= ' class="page-item active"';
      } else {
        $output .= ' class="page-item"';
      }
      $output .= '>';
      if (strpos($page, '<a') === false && strpos($page, '&hellip;') === false) {
        $output .= '<a class="page-link">' . $page . '</a>';
      } else {
        if (strpos($page, 'class=') === false) {
          $page = str_ireplace('<a', '<a class="page-link"', $page);
        } else {
          $page = str_ireplace('class="', 'class="page-link ', $page);
          $page = str_ireplace('class=\'', 'class=\'page-link ', $page);
        }
        $output .= $page;
      }
      $output .= '</li>';
    }
    $output .= '</ul>';
    $output .= '</nav>';
  }

  return $output;
}

//add_action( 'save_post_variable', 'bootstrap_child_save_variable', 10, 3 );
function bootstrap_child_save_variable( $post_ID, $post, $update ){
  $post_type = get_post_type($post_ID);
  if ($post_type != 'variable') {
    return;
  }
  else{
    $response = get_field('response', $post_ID);
    $min_year = '';
    $max_year = '';

    if (!empty($response)) {
      foreach ($response as $key => $item) {
        if (empty($min_year)) {
          $min_year = $item['year'];
        }
        if (empty($max_year)) {
          $max_year = $item['year'];
        }

        if ($min_year > $item['year']) {
          $min_year = $item['year'];
        }

        if ($max_year < $item['year']) {
          $max_year = $item['year'];
        }
      }
    }

    update_post_meta($post_ID, 'min_year', $min_year);
    update_post_meta($post_ID, 'max_year', $max_year);
  }
}

function bootstrap_child_set_min_year( $value, $post_id, $field  ) {
  $response = get_field('response', $post_id);
  $min_year = $value;

  if (!empty($response)) {
    foreach ($response as $key => $item) {
      if (empty($min_year)) {
        $min_year = $item['year'];
      }

      if ($min_year > $item['year']) {
        $min_year = $item['year'];
      }
    }
  }
  
  return $min_year;
}

add_filter('acf/update_value/name=min_year', 'bootstrap_child_set_min_year', 10, 3);

function bootstrap_child_set_max_year( $value, $post_id, $field  ) {
  $response = get_field('response', $post_id);
  $max_year = $value;

  if (!empty($response)) {
    foreach ($response as $key => $item) {
      if (empty($max_year)) {
        $max_year = $item['year'];
      }

      if ($max_year < $item['year']) {
        $max_year = $item['year'];
      }
    }
  }
  
  return $max_year;
}

add_filter('acf/update_value/name=max_year', 'bootstrap_child_set_max_year', 10, 3);

function bootstrap_child_get_country_resource_id($term_id){
  $country_resource_id = null;

  $args = [
    'posts_per_page' => -1,
    'post_type'      => 'country_resource',
    'post_status'    => 'publish',
    'meta_key'       => 'country',
    'meta_value'     => $term_id,
  ];

  $query = new WP_Query( $args );
  $country_resource = $query->posts;

  if (!empty($country_resource)) {
    $country_resource_id = $country_resource[0]->ID;
  }

  return $country_resource_id;
}

function bootstrap_child_redirect_to_login_if_not_login(){
  $redirect = true;

  if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    if (in_array("administrator", $user->roles) || in_array("promundo", $user->roles) || in_array("public", $user->roles)) {
      $redirect = false;
    }
  }

  if ($redirect) {
    wp_redirect( 'login' );
    exit;
  }
}
