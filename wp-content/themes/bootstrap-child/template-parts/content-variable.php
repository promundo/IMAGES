<?php
/**
 * Display the post content in "generic" or "standard" format.
 * This will be use in the loop and full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
$post_id = get_the_ID();
$theme_category_list = get_the_terms( $post_id, 'theme_category');
$scale_category_list = get_the_terms( $post_id, 'scale_category');
$question_text = get_field( "question_text" );
$original_variable_number = get_field( "original_variable_number" );
$response_options = get_field( "response_options" );
$response_type = get_field( "response_type" );
//$notes = get_field( "notes" );
$notes = [];
$response = get_field( "response" );
$languages = [];
$user = wp_get_current_user();

$selected_variables = [];
if ($user_id > 0) {
  $selected_variables = get_field( 'variables', "user_" . $user_id );
}

if (!empty($response)) {
  foreach ($response as $key => $value) {
    if (!empty($value['language'])) {
      $languages[] = $value['language']->name;
    }
  }

  if (!empty($languages)) {
    $languages = array_unique($languages);
  }
}

$theme_terms = wp_get_post_terms( $post_id, 'theme_category', array('fields' => 'ids'));

$related_variables = [];
if (!empty($theme_terms)) {
  $args = array(
    'post_type' => 'variable',
    'posts_per_page' => 5,
    'post__not_in' => [$post_id],
    'tax_query' => array(
      array(
        'taxonomy' => 'theme_category',
        'field'    => 'term_id',
        'terms'    => $theme_terms,
        'operator' => 'IN',
      ),
    ),
  );
  $query = new WP_Query( $args );
}

?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="row">
    <div class="col-md-8 col-lg-9">
      <a class="back-btn" href="javascript:history.back()"><?php echo __('Back', 'bootstrap-child'); ?></a>
      <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php if ( in_array( 'administrator', (array) $user->roles ) || in_array( 'promundo', (array) $user->roles) || in_array( 'public', (array) $user->roles)): ?>
          <div class="links">
            <a class="select-variable" data-post-id="<?php echo $post_id; ?>" href="#"><?php echo __('Select this variable', 'bootstrap-child'); ?></a>
          </div>
        <?php else: ?>
          <div class="links">
            <a class="select-variable-no-login" href="<?php echo home_url('/login')?>"><?php echo __('Select this variable', 'bootstrap-child'); ?></a>
          </div>
        <?php endif; ?>
        
      </header><!-- .entry-header -->

      <div class="entry-content">
        <?php if(!empty($theme_category_list)): ?>
          <div class="theme-categories">
            <?php foreach ($theme_category_list as $key => $theme_item): ?>
              <?php echo "<span>" . $theme_item->name . "</span>"; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if(!empty($scale_category_list)): ?>
          <div class="theme-categories">
            <?php foreach ($scale_category_list as $key => $scale_item): ?>
              <?php echo "<span>" . $scale_item->name . "</span>"; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if(!empty($question_text)): ?>
          <div class="inner-section">
            <h3><?php echo __('English wording', 'bootstrap-child'); ?></h3>
            <?php echo $question_text; ?>
          </div>
        <?php endif; ?>
        <?php if(!empty($original_variable_number)): ?>
          <div class="inner-section">
            <h3><?php echo __('Original Variable Number', 'bootstrap-child'); ?></h3>
            <?php echo $original_variable_number; ?>
          </div>
        <?php endif; ?>
        <?php if(!empty($response_options)): ?>
          <div class="inner-section">
            <h3><?php echo __('Response Options', 'bootstrap-child'); ?></h3>
            <ul>
              <?php foreach($response_options as $option): ?>
                <?php if (!empty($option['label'])): ?>
                  <li><?php echo $option['label']; ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <?php if(!empty($response_type)): ?>
          <div class="inner-section">
            <h3><?php echo __('Type of response', 'bootstrap-child'); ?></h3>
            <?php echo $response_type; ?>
          </div>
        <?php endif; ?>
        <?php if(!empty($notes)): ?>
          <div class="inner-section">
            <h3><?php echo __('Notes', 'bootstrap-child'); ?></h3>
            <?php foreach($notes as $note): ?>
              <div class="note-item">
                <span class="note-date"><?php echo date('d.m.Y', strtotime($note->post_date)); ?></span>
                <p><?php echo $note->post_content; ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if(!empty($response)): ?>
          <div class="response">
            <table class="responsive-table variable">
              <thead>
                <tr>
                  <th scope="col"><?php echo __('Countries it was asked in', 'bootstrap-child'); ?></th>
                  <th scope="col"><?php echo __('Year', 'bootstrap-child'); ?></th>
                  <th scope="col"><?php echo __('Language', 'bootstrap-child'); ?></th>
                  <th scope="col"><?php echo __('Who was asked', 'bootstrap-child'); ?></th>
                  <th scope="col"><?php echo __('Gender of who was asked', 'bootstrap-child'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($response as $response_item): ?>
                  <?php $country_resource_id = bootstrap_child_get_country_resource_id($response_item['country']->term_id);
                    if (is_numeric($country_resource_id)) {
                      $country = '<a href="' . get_permalink( $country_resource_id ) . '">' . $response_item['country']->name . '</a>';
                    }
                    else{
                      $country = $response_item['country']->name;
                    }
                  ?>

                  <tr>
                    <td data-label="Countries"><?php echo $country; ?></td>
                    <td data-label="Year"><?php echo isset($response_item['year']) ? $response_item['year']: ''; ?></td>
                    <td data-label="Language"><?php echo isset($response_item['language']->name) ? $response_item['language']->name: ''; ?></td>
                    <td data-label="Who was asked"><?php echo isset($response_item['who_asked']['value']) ? $response_item['who_asked']['label']: ''; ?></td>
                    <td data-label="Gender"><?php echo isset($response_item['gender']['label']) ? $response_item['gender']['label']: ''; ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
        <div class="clearfix"></div>
      </div><!-- .entry-content -->
    </div>
    <div class="col-md-4 col-lg-3 sidebar-section">
      <?php if(!empty($languages)): ?>
        <div class="sidebar-widget">
          <h2 class="widget-title"><?php echo __('Languages question was asked in:', 'bootstrap-child'); ?></h2>
          <div class="widget-content">
            <ul class="list">
              <?php foreach($languages as $language): ?>
                <li><?php echo $language; ?></li>
              <?php endforeach; ?>
            </ul>
            <p>See the <a href="<?php echo get_permalink(198); ?>">country resource page</a> for the survey in these languages</p>
          </div>
        </div>
      <?php endif; ?>
      <?php if(isset($query)): ?>
        <?php if ( $query->have_posts() ) : ?>
          <div class="sidebar-widget">
            <h2 class="widget-title"><?php echo __('Related Variables', 'bootstrap-child'); ?></h2>
            <div class="widget-content">
              <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'variable-related' );?>
              <?php endwhile;?>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 
