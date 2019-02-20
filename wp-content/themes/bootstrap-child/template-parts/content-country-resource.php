  <?php
/** 
 * Display the page content for post type "page".
 * This will be use in full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
$pdf_record = get_field( "country_pdf" );
$pdf_detailed = get_field( "country_detailed_pdf" );
$resource_links = get_field( "resource_links" );
$resource_links = array();
if (!empty($resource_links)) $content_class = "col-md-8 col-lg-9"; else $content_class = "col-md-12";
?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">

        <?php if (!empty($pdf_record)) : ?>
          <?php if ($pdf_record['mime_type'] == 'application/pdf') : ?>
            <?php // echo do_shortcode('[pdf-embedder url="'.$pdf_record['url'].'" width="840"]'); ?>
          <?php endif; ?>
        <?php endif; ?>
        <div class="row">
          <div class="<?php print $content_class; ?>">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php the_content(); ?>
          </div>
        <?php if (!empty($resource_links)) : ?>
          <div class="col-md-4 col-lg-3 sidebar-section">
            <div class="sidebar-widget">
              <h2 class="widget-title">Resource links</h2>
              <ul class="links">
              <?php foreach ($resource_links as $link) : ?>
                <?php
                  $link_url = $link['link']['url'];
                  $link_title = $link['link']['title'];
                  $link_target = $link['link']['target'] ? $link['link']['target'] : '_self';
                ?>
                <li><a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a></li>
              <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        </div>
        <?php if (!empty($pdf_detailed)) : ?>
          <div class="buttons">
            <a class="download" target="_blank" href="<?php print $pdf_detailed['url']; ?>"><?php echo __('Download the Complete Country Profile', 'bootstrap-child'); ?></a>
          </div>
        <?php endif; ?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 
