<?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
$shortcode = get_field( "shortcode" );
?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
    <div class="entry-content">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php the_content(); ?> 
        <div class="clearfix"></div>
        <?php if (!empty($shortcode)) : ?>
          <?php print do_shortcode($shortcode); ?>
        <?php endif;?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 
