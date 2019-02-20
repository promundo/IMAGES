<?php
/**
 * The theme header.
 * 
 * @package bootstrap-basic4
 */

$container_class = apply_filters('bootstrap_basic4_container_class', 'container');
if (!is_scalar($container_class) || empty($container_class)) {
    $container_class = 'container';
}
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

        <!--wordpress head-->
        <?php wp_head(); ?> 
        <!--end wordpress head-->
    </head>
    <body <?php body_class(); ?>>
        <div class="page-container">
            <header class="page-header">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 site-branding">
                            <h1 class="site-title-heading">
                                <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                            </h1>
                        </div>
                        <?php if (has_nav_menu('primary')) : ?> 
                        <div class="col-sm-12 col-md-8 main-navigation">
                            <nav class="navbar navbar-expand-lg">
                                <div id="primary-menu-container" class="navbar-collapse">
                                    <?php 
                                    wp_nav_menu(
                                        array(
                                            'depth' => '2',
                                            'theme_location' => 'primary', 
                                            'container' => false, 
                                            'menu_id' => 'primary-menu',
                                            'menu_class' => 'navbar-nav ml-auto', 
                                            'walker' => new \BootstrapBasic4\BootstrapBasic4WalkerNavMenu()
                                        )
                                    ); 
                                    ?> 
                                    <div class="clearfix"></div>
                                </div><!--.navbar-collapse-->
                                <div class="clearfix"></div>
                            </nav>
                        </div><!--.main-navigation-->
                    <?php endif; ?>
                    </div><!--.site-branding-->
                    
              </div>
            </header><!--.page-header-->

            <div id="content" class="site-content">
                <div class="container">
                    <div class="row">
               
