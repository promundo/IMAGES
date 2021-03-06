<?php
/** 

 * Template Name: Two columns Template

 * 
 * @package bootstrap-basic4
 */


// begins template. -------------------------------------------------------------------------
get_header();
?> 

                <main id="main" class="col-md-12 site-main two-columns" role="main">
                    <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
                    <?php
                    if (have_posts()) {
                        $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
                        while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/content', 'page-two-columns');
                            echo "\n\n";

                        }// endwhile;

                        unset($Bsb4Design);

                    } else {
                        get_template_part('template-parts/section', 'no-results');
                    }// endif;
                    ?> 
                </main>
<?php
get_footer();
