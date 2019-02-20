<?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */

if (function_exists('get_field')) {
  $blue = get_field('blue_link');
  $orange = get_field('orange_link');
}

?>
<div id="hero" <?php post_class('build-survey'); ?>>
  <div class="image">
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('full'); ?>
    <?php endif; ?>
  </div>
  <div class="text-container">
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2 entry-content">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <?php if (!empty($blue) || !empty($orange)) : ?> 
            <div class="buttons">
              <?php if (!empty($blue)) : ?>
                <a class="blue" href="<?php print $blue; ?>">Add variables</a>
              <?php endif; ?>
              <?php if (!empty($orange)) : ?>
                <a href="<?php print $orange; ?>">Print the survey</a>
              <?php endif; ?>
            </div>
            <?php endif; ?>
        </div><!-- .entry-content -->
      </div>
    </div>
  </div>


</div><!-- #post-## -->
