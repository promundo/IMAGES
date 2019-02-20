<div id="hero" <?php post_class('search-results'); ?>>
  <div class="image">
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('full'); ?>
    <?php endif; ?>
  </div>
  <div class="text-container">
    <div class="container">
      <div class="row">
        <div class="col-md-12 entry-content">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php the_content(); ?> 
        </div><!-- .entry-content -->
      </div>
    </div>
  </div>


</div><!-- #post-## -->
