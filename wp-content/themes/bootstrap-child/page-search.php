<?php
/** 

 * Template Name: Search Template

 * 
 * @package bootstrap-basic4
 */

$countries = bootstrap_child_get_countries();
$themes = bootstrap_child_get_themes();
$search_result_url = get_permalink( get_page_by_path( 'search-results' ) );
// begins template. -------------------------------------------------------------------------
get_header('hero');
?> 
    

            <?php
            if (have_posts()) {
                $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
                while (have_posts()) {
                    the_post();
                    get_template_part('template-parts/content', 'page-search');
                }// endwhile;
            } 
            ?> 
            <div class="container search-container">
                <?php include('custom-search.php');?>
            </div>
            
            <div id="content" class="site-content">
                <div class="container">
                    <div class="row">
                        <main id="main" class="col-md-12 site-main" role="main">
                            <div id="tabs">
                                <ul class="tabs">
                                    <li><a href="#themes">Discover All Themes</a></li>
                                    <li><a href="#countries">Discover All Countries</a></li>
                                </ul>
                                <?php if (!empty($themes)) : ?>

                                <div class="tabs-content" id="themes">
                                    <?php $count =  count($themes); ?>
                                    <?php $size = intdiv($count,2); ?>
                                    <?php if(($size*2) < $count) $size = $size + 1;?>
                                    <?php $result = array_chunk($themes,$size); ?>
                                    <div class="row">
                                        <?php foreach ($result as $items) : ?>
                                            <div class="col-lg-3 col-md-6">
                                                <ul class="list">
                                                <?php foreach ($items as $theme) : ?>
                                                    <li><a href="<?php echo $search_result_url.'?theme[]='.$theme['id']; ?>">
                                                        <?php print $theme['name'] ?>
                                                </a></li>
                                                <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($countries)) : ?>
                                <div class="tabs-content" id="countries">
                                    <?php $count =  count($countries); ?>
                                    <?php $size = intdiv($count,4); ?>
                                    <?php if(($size*4) < $count) $size = $size + 1;?>
                                    <?php $result = array_chunk($countries,$size); ?>
                                    <div class="row">
                                        <?php foreach ($result as $items) : ?>
                                            <div class="col-lg-3 col-md-6">
                                                <ul class="list countries">
                                                <?php foreach ($items as $country) : ?>
                                                    <li><a href="<?php echo $search_result_url.'?country[]='.$country['id']; ?>">
                                                        <?php if (!empty($country['flag'])) : ?>
                                                            <img src="<?php print $country['flag']; ?>" width="25" height="16" />
                                                        <?php endif; ?>
                                                        <?php print $country['name'] ?>
                                                </a></li>
                                                <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </div>
                        </main>

                        
<?php
get_footer();
