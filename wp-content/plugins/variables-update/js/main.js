(function ($) {
  $(document).ready(function() {
    count = 0;
    initScript();
  });
  
  function initScript(){
    $('#update-script').submit(function(e){
      var $btn = $(this).find('input[type=submit]:focus');
      if (($btn.attr('id')) == 'update') {
        e.preventDefault();
        $('#update-script .process').text("Importing: " + count);
        $('#update-script #submit').prop("disabled", true);
        sendAjax();
      }
    });
  }
  
  function sendAjax(){
    var data={
      action:'start_variables_update',
      count:count,
    }
    $.ajax({
      url: ajaxurl,
      data:data,
      type: 'POST',
      success: function(data){
        count++;
        if(data == 1){
          $('#update-script .process').text("Importing: " + 10*count);
          sendAjax();
        }
        else{
          $('#update-script .process').text("Import finished");
          $('#update-script #submit').prop("disabled", false);
          count = 0;
        }
      }
    });
  }

}(jQuery));
