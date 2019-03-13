<div class="row"><div class="col-md-10 offset-md-1 col-xl-8 offset-xl-2">
<div class="search-form">
<form action="<?php echo get_permalink( get_page_by_path( 'search-results' ) );?>">
  <div class="row">
    <div class="form-item col-md-12">
      <input type="text" class="form-text" placeholder="Search by key word, variable name, specific question or country" name="search">
    </div>
    <div class="form-item col-md-6">
        <select name="theme[]" multiple="multiple" id="theme-select" class="form-select">
          <?php echo bootstrap_child_get_themes_option(); ?>
        </select>
    </div>
<!--     <div class="form-item col-md-4">
        <select name="scale[]" multiple="multiple" id="scale-select" class="form-select">
          <?php echo bootstrap_child_get_scales_option(); ?>
        </select>
    </div> -->
    <div class="form-item col-md-6">
        <select name="country[]" multiple="multiple" id="country-select" class="form-select">
          <?php echo bootstrap_child_get_countries_option(); ?>
        </select>
    </div>
    <input type="submit" value="Search" class="form-submit" />
  </div>
</form>
</div>
</div></div>
