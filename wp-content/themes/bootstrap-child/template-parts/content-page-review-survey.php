<?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();

?> 
<article id="post-<?php the_ID(); ?>" <?php post_class('review-survey'); ?>>
    <div class="entry-content">
      <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
      <?php the_content(); ?> 
     </div><!-- .entry-content -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?>