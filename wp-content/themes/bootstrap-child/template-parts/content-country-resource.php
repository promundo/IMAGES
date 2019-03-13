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
$text_blocks = get_field( "text_blocks" );
$report_cover = get_field( "report_cover" );
//$resource_links = array();
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
            <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <?php if (!empty($text_blocks)) : ?>
              <?php foreach ($text_blocks as $key=>$item) : ?>
                <div class="text-item">
                  <?php 
                    $count = $key+1;
                    $title = $item['title'];
                    $text = $item['text'];
                  ?>
                  <?php if (!empty($title)) : ?>
                     <h2 class="collapsed" data-toggle="collapse" data-target="#text-block-<?php print $count?>" aria-expanded="false" aria-controls="text-block-<?php print $count?>"><?php print $title; ?></h2>
                  <?php endif; ?>
                  <div id="text-block-<?php print $count?>" class="collapse">
                    <?php print $text; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        <?php if (!empty($resource_links) || !empty($report_cover)) : ?>
          <div class="col-md-4 col-lg-3 sidebar-section">
            <?php if (!empty($resource_links)) : ?>
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
            <?php endif; ?>
            <?php if (!empty($report_cover)) : ?>
              <div class="sidebar-widget-image">
                <img src="<?php print $report_cover['sizes']['medium'] ?>" alt="" />
                <?php //pa($report_cover,1); ?>
              </div>
            <?php endif; ?>
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
