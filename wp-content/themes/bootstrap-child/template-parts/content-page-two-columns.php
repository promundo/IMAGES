<?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */


if (function_exists('get_field')) {
  $next = get_field('next_link');
  $skip = get_field('skip_link');
}

if (!empty($next) || !empty($skip)) {
  $class_name = 'with-navigation';
}

?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="row">
    <div class="col-md-5">
      <div class="text">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?> 
      </div>
    </div><!-- .entry-content -->
    <div class="col-md-7 image-container">
      <?php if (has_post_thumbnail()) : ?>
        <div class="image">
          <div class="image-inner">
              <?php the_post_thumbnail('page_image_large'); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if (!empty($next) || !empty($skip)) : ?>
  <div class="navigation">
    <div class="row">
      <div class="col-6">
        <?php if (!empty($skip)) : ?>
          <a href="<?php print $skip; ?>" class="skip">Skip</a>
        <?php endif; ?>
      </div>
      <div class="col-6">
        <?php if (!empty($next)) : ?>
          <a href="<?php print $next; ?>" class="next">Next</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
</article><!-- #post-## -->

