<?php
/** 

 * Template Name: Search Results Template

 * 
 * @package bootstrap-basic4
 */

// begins template. -------------------------------------------------------------------------
get_header('hero');

$sort_by = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'theme';
$items_per_page = 10;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$user_id = get_current_user_id();

$args = [
  'solr_integrate' => true,
  'posts_per_page' => $items_per_page,
  'post_type'      => 'variable',
  'post_status'    => 'publish',
  'paged' => $page
];

if ($sort_by == 'theme') {
  $args['meta_key'] = 'theme_weight';
  $args['orderby'] = 'meta_value_num';
  $args['order'] = 'ASC';
}

if ($sort_by == 'year') {
  $args['meta_key'] = 'max_year';
  $args['orderby'] = 'meta_value_num';
  $args['order'] = 'DESC';
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
        <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
        <div class="header-search-result">
          <div class="count-result">
            <?php echo $total . ' ' . __('Results Matching Criteria', 'bootstrap-child'); ?>
          </div>
          <div class="sort-by">
            <label><?php echo __('Sort by', 'bootstrap-child') . ':';?></label>
            <div class="select-wrap">
              <select name="sort">
                <?php $default_sort = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'theme'; ?>
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
              <?php if ($user_id > 0):?>
                <th></th>
              <?php endif; ?>
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
