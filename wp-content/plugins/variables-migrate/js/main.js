(function ($) {
  $(document).ready(function() {
    count = 0;
    initScript();
  });
  
  function initScript(){
    $('#migrate-script').submit(function(e){
      var $btn = $(this).find('input[type=submit]:focus');
      if (($btn.attr('id')) == 'migrate') {
        e.preventDefault();
        $('#migrate-script .process').text("Importing: " + count);
        $('#migrate-script #submit').prop("disabled", true);
        sendAjax();
      }
    });
  }
  
  function sendAjax(){
    var data={
      action:'start_variables_migrate',
      count:count,
    }
    $.ajax({
      url: ajaxurl,
      data:data,
      type: 'POST',
      success: function(data){
        count++;
        if(data == 1){
          $('#migrate-script .process').text("Importing: " + 10*count);
          sendAjax();
        }
        else{
          $('#migrate-script .process').text("Import finished");
          $('#migrate-script #submit').prop("disabled", false);
          count = 0;
        }
      }
    });
  }

}(jQuery));
