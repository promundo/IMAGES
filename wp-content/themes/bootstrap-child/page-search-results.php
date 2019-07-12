<?php
/** 

 * Template Name: Search Results Template

 * 
 * @package bootstrap-basic4
 */

// begins template. -------------------------------------------------------------------------
get_header('hero');

$session = Session::getInstance();

$sort_by = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'scale';
$items_per_page = 10;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;

$args = [
  'solr_integrate' => true,
  'posts_per_page' => $items_per_page,
  'post_type'      => 'variable',
  'post_status'    => 'publish',
  'paged' => $page
];

if ($sort_by == 'scale') {
  $args['meta_key'] = 'scale_weight';
  $args['orderby'] = 'meta_value_num';
  $args['order'] = 'ASC';
}

if ($sort_by == 'theme') {
  $args['meta_key'] = 'theme_weight';
  $args['orderby'] = 'meta_value_num';
  $args['order'] = 'ASC';
}

if ($sort_by == 'year') {
  $args['meta_key'] = 'min_year';
  $args['orderby'] = 'meta_value_num';
  $args['order'] = 'ASC';
}

if ($sort_by == 'name_asc') {
  $args['meta_key'] = 'question_text';
  $args['orderby'] = 'meta_value';
  $args['order'] = 'ASC';
}

if ($sort_by == 'name_desc') {
  $args['meta_key'] = 'question_text';
  $args['orderby'] = 'meta_value';
  $args['order'] = 'DESC';
}

$key_word = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
if (!empty($_REQUEST['search'])) {
  $args['s'] = $key_word;
}

if (!empty($_REQUEST['theme'])) {
  $args['tax_query'] = [
    'relation' => 'AND',
    [
      'taxonomy' => 'theme_category',
      'field'    => 'term_id',
      'operator' => 'IN',
      'terms'    => $_REQUEST['theme'],
    ]
  ];
}

if (!empty($_REQUEST['country'])) {
  if (!isset($args['tax_query'])) {
    $args['tax_query'] = [
      'relation' => 'AND'
    ];
  }

  $args['tax_query'][] = [
    'taxonomy' => 'country_category',
    'field'    => 'term_id',
    'operator' => 'IN',
    'terms'    => $_REQUEST['country'],
  ];
}

if (!empty($_REQUEST['scale'])) {
  if (!isset($args['tax_query'])) {
    $args['tax_query'] = [
      'relation' => 'AND'
    ];
  }

  $args['tax_query'][] = [
    'taxonomy' => 'scale_category',
    'field'    => 'term_id',
    'operator' => 'IN',
    'terms'    => $_REQUEST['scale'],
  ];
}

$query = new WP_Query( $args );

$query_total = new WP_Query( $args );
$total = $query_total->found_posts;

if (isset($_REQUEST['select-all-variable'])) {
  if ($_REQUEST['select-all-variable'] == true && $total > 0) {
    $all_variable_ids = [];
    $number_page = ceil($total/100);
    $args['posts_per_page'] = 100;

    for ($i=1; $i <= $number_page; $i++) { 
      $args['paged'] = $i;
      $query_item = new WP_Query( $args );
      $query_item_post = $query_item->posts;
      if (!empty($query_item_post)) {
        foreach ($query_item_post as $key => $post_item) {
          $all_variable_ids[] = $post_item->ID;
        }
      }
    }

    if (!empty($all_variable_ids)) {
      $new_selected_variables = $all_variable_ids;
      $current_selected_variables = $session->user_variables;
      if (!empty($current_selected_variables)) {
        $new_selected_variables = array_merge($new_selected_variables, $current_selected_variables);
        $new_selected_variables = array_unique($new_selected_variables);
      }

      $session->user_variables = $new_selected_variables;
    }
  }
}


?>

<?php
  if (have_posts()) {
    $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
    while (have_posts()) {
      the_post();
      get_template_part('template-parts/content', 'page-search-results');
    }
  }
?>
<div class="filter-container">
  <div class="container">
    <?php include('custom-filter.php');?>
  </div>
</div>

<div id="content" class="site-content">
  <div class="container">
    <div class="row">
      <main id="main" class="col-md-12 site-main" role="main">
        <div class="btn-container">
          <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
          <button class="btn-orange select-all-variable"><?php echo __('Select All', 'bootstrap-child'); ?></button>
        </div>
        <div class="header-search-result">
          <div class="count-result">
            <?php echo $total . ' ' . __('Results Matching Criteria', 'bootstrap-child'); ?>
          </div>
          <div class="sort-by">
            <label><?php echo __('Sort by', 'bootstrap-child') . ':';?></label>
            <div class="select-wrap">
              <select name="sort">
                <?php $default_sort = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'scale'; ?>
                <?php echo bootstrap_child_get_sort_option($default_sort); ?>
              </select>
            </div>
          </div>
        </div>
        <table class="responsive-table variable-edit-table">
          <thead>
            <tr>
              <th class="title">Variable Name</th>
              <th class="align-right">Years</th>
              <th>Countries</th>
              <th>Theme</th>
              <th>Scale</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if ( $query->have_posts() ): ?>
              <?php while ( $query->have_posts() ):?>
                <?php $query->the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'variable-search-result' );?>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo __('No results', 'bootstrap-child'); ?></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
        <?php 
          $total_pagination = ceil($total / $items_per_page);
          $current = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
          echo bootstrap_child_custom_pagination('cpage', $total_pagination, $current, '&laquo;', '&raquo;');
        ?>
      </main>

<?php
get_footer();
