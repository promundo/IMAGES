<?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */

global $post;
$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
$session = Session::getInstance();
$variables = $session->user_variables;

?> 
<article id="post-<?php the_ID(); ?>" <?php post_class('selected-variables'); ?>>
  <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
  <header class="entry-header">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <div class="buttons">
      <a href="<?php echo get_permalink(177); ?>" ><?php echo __('Print the Survey', 'bootstrap-child'); ?></span>
      <a href="" class="export-csv"><?php echo __('Download CSV', 'bootstrap-child'); ?></a>
    </div>
    
  </header>

  <div class="entry-content">
    <div class="filters">
      <div class="from-checkbox">
        <input type="checkbox" id="select-all" name="select-all">
        <label for="select-all"><?php echo __('Select All', 'bootstrap-child'); ?></label>
      </div>
      <a class="remove-all" href=""><?php echo __('Remove Selected', 'bootstrap-child'); ?></a>
    </div>
    <table class="responsive-table variable-edit-table">
      <thead>
        <tr>
          <th class="title">Variable Name</th>
          <th class="align-right">Years</th>
          <th>Countries</th>
          <th>Theme</th>
          <th class="remove"></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($variables)): ?>
          <?php foreach($variables as $post): ?>
            <?php setup_postdata( $post ); ?>
            <?php get_template_part( 'template-parts/content', 'variable-edit-table' );?>
          <?php endforeach;?>
          <?php wp_reset_postdata(); ?>
          <tr class="hide-block">
            <td class="text-center" colspan="5"><?php echo __('No results', 'bootstrap-child'); ?></td>
          </tr>
        <?php else: ?>
          <tr>
            <td class="text-center" colspan="5"><?php echo __('No results', 'bootstrap-child'); ?></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="clearfix"></div>
  </div><!-- .entry-content -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 
