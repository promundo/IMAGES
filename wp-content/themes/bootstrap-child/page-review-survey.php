<?php
/** 

 * Template Name: Review Survey Template

 * 
 * @package bootstrap-basic4
 */


// begins template. -------------------------------------------------------------------------
bootstrap_child_redirect_to_login_if_not_login();
get_header();
?> 
                <main id="main" class="col-md-12 site-main" role="main">
                    <?php
                    if (have_posts()) {
                        $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
                        while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/content', 'page-review-survey');
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
