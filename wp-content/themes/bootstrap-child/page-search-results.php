<?php
/** 

 * Template Name: Search Results Template

 * 
 * @package bootstrap-basic4
 */

// begins template. -------------------------------------------------------------------------
get_header('hero');

$items_per_page = 10;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;

$key_word = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
$theme = isset($_REQUEST['theme']) ? $_REQUEST['theme'] : '';
$country = isset($_REQUEST['country']) ? $_REQUEST['country'] : '';
$year = isset($_REQUEST['years']) ? $_REQUEST['years'] : '';
$sort_by = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'name';

$query_arr['select'] = "SELECT DISTINCT $wpdb->posts.ID FROM $wpdb->posts";
$query_arr['where'][] = "$wpdb->posts.post_type='variable'";

if (!empty($key_word)) {
  $query_arr['where'][] = "$wpdb->posts.post_title LIKE '%" . $key_word . "%'";
}

if (!empty($theme)) {
  $query_arr['join'][] = "LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)";
  $query_arr['where'][] = "$wpdb->term_relationships.term_taxonomy_id IN (" . implode(',', $theme) . ")";
}
if (!empty($country)) {
  $query_arr['join'][] = "LEFT JOIN $wpdb->postmeta AS rs ON ($wpdb->posts.ID = rs.post_id)";
  $query_arr['where'][] = "(rs.meta_key LIKE 'response_%_country' AND rs.meta_value IN (" . implode(',', $country) . "))";
}
if (!empty($year)) {
  $query_arr['join'][] = "LEFT JOIN $wpdb->postmeta AS ry ON ($wpdb->posts.ID = ry.post_id)";
  $query_arr['where'][] = "(ry.meta_key LIKE 'response_%_year' AND ry.meta_value IN (" . implode(',', $year) . "))";
}

global $wpdb;
if (!empty($query_arr)) {
  $query = $query_arr['select'];
  if (isset($query_arr['join'])) {
    $query.= ' ' . implode(' ', $query_arr['join']);
  }
  if (isset($query_arr['where'])) {
    $query.= ' WHERE ' . implode(' AND ', $query_arr['where']);
  }

  switch ($sort_by) {
    case 'name':
      $order = " ORDER BY $wpdb->posts.post_title ASC";
      break;
    // case 'year':
    //   $order = " ORDER BY $wpdb->posts.post_title DESC";
    //   break;
  }

  $query.= $order;
}

$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total = $wpdb->get_var( $total_query );

$results = $wpdb->get_results( $query . ' LIMIT '. $offset . ', '. $items_per_page);
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
        <div class="header-search-result">
          <div class="count-result">
            <?php echo $total . ' ' . __('Results Matching Criteria', 'bootstrap-child'); ?>
          </div>
          <div class="sort-by">
            <label><?php echo __('Sort by', 'bootstrap-child') . ':';?></label>
            <div class="select-wrap">
              <select name="sort">
                <?php $default_sort = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'name'; ?>
                <?php echo bootstrap_child_get_sort_option($default_country); ?>
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
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($results)): ?>
              <?php foreach($results as $post): ?>
                <?php setup_postdata( $post ); ?>
                <?php get_template_part( 'template-parts/content', 'variable-search-result' );?>
              <?php endforeach;?>
              <?php wp_reset_postdata(); ?>
            <?php else: ?>
              <tr>
                <td class="text-center" colspan="4"><?php echo __('No results', 'bootstrap-child'); ?></td>
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
