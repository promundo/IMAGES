<?php
$response = get_field( "response" );
$country = [];
$year = [];
if (!empty($response)) {
  foreach ($response as $key => $value) {
    if (!empty($value['country'])) {
      $country[] = $value['country']->name;
    }
    if (!empty($value['year'])) {
      $year[] = $value['year'];
    }
  }

  if (!empty($country)) {
    $country = array_unique($country);
  }
  if (!empty($year)) {
    $year = array_unique($year);
    sort($year);
  }
}

?>
<div class="variable-item">
  <h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
  <div class="variable-content">
    <?php if(!empty($country)): ?>
      <?php echo implode(', ', $country) . ' | '; ?>
    <?php endif; ?>
    <?php if(!empty($year)): ?>
      <?php echo implode(', ', $year); ?>
    <?php endif; ?>
    <a class="more" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">More</a>
  </div>
</div>
