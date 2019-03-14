<?php
/** 

 * Template Name: Build Survey Template

 * 
 * @package bootstrap-basic4
 */


// begins template. -------------------------------------------------------------------------
bootstrap_child_redirect_to_login_if_not_login();
$variables = bootstrap_child_get_survey_variables();
get_header('hero');
?>

<?php
if (have_posts()) {
    $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content', 'page-build-survey');
    }// endwhile;
} 
?> 
<div id="content" class="site-content">
  <div class="container">
    <div class="row">
      <main id="main" class="col-md-12 site-main" role="main">
        <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
        <table class="responsive-table">
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
            <?php if(!empty($variables)): ?>
              <?php foreach($variables as $post): ?>
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
      </main>
<?php
get_footer();
