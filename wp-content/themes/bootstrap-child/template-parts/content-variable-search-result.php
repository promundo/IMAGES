<?php
$id = get_the_ID();

$user_id = get_current_user_id();
$selected_variables = [];
if ($user_id > 0) {
  $selected_variables = get_field( 'variables', "user_" . $user_id );
}

$theme_terms = wp_get_post_terms( $id, 'theme_category');
$scale_terms = wp_get_post_terms( $id, 'scale_category');

$response = get_field( "response", $id);
$countries = [];
$years = [];
if (!empty($response)) {
  foreach ($response as $key => $value) {
    if (!empty($value['country'])) {
      $country_resource_id = bootstrap_child_get_country_resource_id($value['country']->term_id);
      if (is_numeric($country_resource_id)) {
        $countries[] = '<a href="' . get_permalink( $country_resource_id ) . '">' . $value['country']->name . '</a>';
      }
      else{
        $countries[] = $value['country']->name;
      }
    }
    if (!empty($value['year'])) {
      $years[] = $value['year'];
    }
  }

  if (!empty($countries)) {
    $countries = array_unique($countries);
  }
  if (!empty($years)) {
    $years = array_unique($years);
    sort($years);
  }
}

?>
<tr>
  <td data-label="Variable Name">
    <div class="from-checkbox">
      <a class="name" href="<?php echo get_permalink($id); ?>"><?php echo get_the_title($id); ?></a>
    </div>
  </td>
  <td data-label="Years" class="align-right">
    <?php if(!empty($years)): ?>
      <div class="list-years">
      <?php foreach($years as $year): ?>
        <span class="label"><?php echo $year; ?></span>
      <?php endforeach; ?>
      </div>
    <?php else: ?>
      &nbsp;
    <?php endif; ?>
  </td>
  <td data-label="Countries">
    <?php if(!empty($countries)): ?>
      <div class="list-countries">
      <?php foreach($countries as $country): ?>
        <span class="label"><?php echo $country; ?></span>
      <?php endforeach; ?>
      </div>
    <?php else: ?>
      &nbsp;
    <?php endif; ?>
  </td>
  <td data-label="Theme">
    <?php if(!empty($theme_terms)): ?>
      <?php foreach($theme_terms as $term): ?>
        <span class="green-btn"><?php echo $term->name; ?></span>
      <?php endforeach; ?>
    <?php else: ?>
      &nbsp;
    <?php endif; ?>
  </td>
  <td data-label="Scale">
    <?php if(!empty($scale_terms)): ?>
      <?php foreach($scale_terms as $scale): ?>
        <span class="green-btn"><?php echo $scale->name; ?></span>
      <?php endforeach; ?>
    <?php else: ?>
      &nbsp;
    <?php endif; ?>
  </td>
  <?php if ($user_id > 0): ?>
    <td>
      <?php if(!empty($selected_variables)):?>
        <?php if(!in_array($id, $selected_variables)): ?>
          <a class="select-variable" data-post-id="<?php echo $id;?>" href="#">Select this variable</a>
        <?php endif;?>
      <?php else: ?>
        <a class="select-variable" data-post-id="<?php echo $id;?>" href="#">Select this variable</a>
      <?php endif;?>
    </td>
  <?php endif; ?>
</tr>