(function ($) {
  $(document).ready(function() {
    initExportSurvey();
  });

  function initExportSurvey(){
    $('.export-survey').on('click', function(event) {
      event.preventDefault();
      var $link = $(this);
      var name = $link.parent().find('input[name="survey_name"]').val();
      //console.log(name);
      var ajaxdata = {
        action      : 'export-survey-variables',
        nonce_code  : pluginSurveyAjax.nonce,
        user_id     : $link.attr('data-user-id'),
        survey_name : name,
      };

      $.post(pluginSurveyAjax.url, ajaxdata, function( response ) {
        
        var data = $.parseJSON(response);
        //console.log(data.url);
        window.location.href = data.url;
      });
    });
  }

}(jQuery));
