<?php $sort_by = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : 'scale'; ?>
<div class="row">
  <div class="col-md-12">
    <div class="filter-form">
      <form>
        <div class="row">
          <div class="form-item col-md-12 text-item">
            <input type="text" class="form-text" placeholder="Search by key word, variable name, specific question or country" name="search" value="<?php echo isset($_REQUEST['search']) ? $_REQUEST['search'] : ''?>">
            <input type="submit" value="Search" class="form-submit" />
          </div>
          <div class="form-item col-md-4">
            <div class="select">
              <select name="theme[]" multiple="multiple" id="theme-select" class="form-select">
                <?php $default_theme = isset($_REQUEST['theme']) ? $_REQUEST['theme'] : []; ?>
                <?php echo bootstrap_child_get_themes_option($default_theme); ?>
              </select>
            </div>
          </div>
          <div class="form-item col-md-4">
            <div class="select">
              <select name="scale[]" multiple="" id="scale-select" class="form-select">
                <?php $default_scale = isset($_REQUEST['scale']) ? $_REQUEST['scale'] : []; ?>
                <?php echo bootstrap_child_get_scales_option($default_scale); ?>
              </select>
             </div>
          </div>
          <div class="form-item col-md-4">
            <div class="select">
              <select name="country[]" multiple="" id="country-select" class="form-select">
                <?php $default_country = isset($_REQUEST['country']) ? $_REQUEST['country'] : []; ?>
                <?php echo bootstrap_child_get_countries_option($default_country); ?>
              </select>
             </div>
          </div>
          <!--<div class="form-item col-md-4">
            <div class="select">
              <select name="years[]" multiple="" id="year-select" class="form-select">
                <?php $default_years = isset($_REQUEST['years']) ? $_REQUEST['years'] : []; ?>
                <?php echo bootstrap_child_get_years_option($default_years); ?>
              </select>
             </div>
          </div>-->
          <input type="hidden" name="sort_by" value="<?php echo $sort_by; ?>">
        </div>
      </form>
    </div>
  </div>
</div>
