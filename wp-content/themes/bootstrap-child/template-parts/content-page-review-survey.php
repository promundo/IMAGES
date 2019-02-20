<?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
$user_id = get_current_user_id();

?> 
<article id="post-<?php the_ID(); ?>" <?php post_class('review-survey'); ?>>
    <div class="entry-content">
      <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
      <a data-user-id="<?php echo $user_id;?>" class="export-survey" href=""><?php echo __('Print', 'bootstrap-child'); ?></a>
      <form action="">
        <div class="form-item">
          <input type="text" placeholder="<?php echo __('Survey Name', 'bootstrap-child'); ?>" name="survey_name">
        </div>
        <div class="form-item">
          <textarea name="survey_info" placeholder="<?php echo __('Some info here', 'bootstrap-child'); ?>"></textarea>
        </div>
      </form>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 
