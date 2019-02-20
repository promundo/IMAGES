<?php
$id = get_the_ID();

$theme_terms = wp_get_post_terms( $id, 'theme_category');
$response = get_field( "response", $id);
$countries = [];
$years = [];
if (!empty($response)) {
  foreach ($response as $key => $value) {
    if (!empty($value['country'])) {
      $countries[] = $value['country']->name;
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
<tr data-id="<?php echo $id;?>">
  <td data-label="Variable Name">
    <div class="from-checkbox">
      <input type="checkbox"  id="select-variable-<?php echo $id; ?>" name="select-variable[]" value="<?php echo $id; ?>">
      <label for="select-variable-<?php echo $id; ?>"><a class="name" href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></label>
    </div>
  </td>
  <td data-label="Years" class="align-right">
    <?php if(!empty($years)): ?>
      <?php foreach($years as $year): ?>
        <span class="label"><?php echo $year; ?></span>
      <?php endforeach; ?>
    <?php endif; ?>
  </td>
  <td data-label="Countries">
    <?php if(!empty($countries)): ?>
      <?php foreach($countries as $country): ?>
        <span class="label"><?php echo $country; ?></span>
      <?php endforeach; ?>
    <?php endif; ?>
  </td>
  <td data-label="Theme">
    <?php if(!empty($theme_terms)): ?>
      <?php foreach($theme_terms as $term): ?>
        <span class="green-btn"><?php echo $term->name; ?></span>
      <?php endforeach; ?>
    <?php endif; ?>
  </td>
  <td>
    <a class="remove-variable" href="">remove</span>
  </td>
</tr>
