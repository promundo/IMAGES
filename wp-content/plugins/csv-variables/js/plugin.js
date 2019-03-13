(function ($) {
  $(document).ready(function() {
    initExportCsv();
  });

  function initExportCsv(){
    $('.export-csv').on('click', function(event) {

      event.preventDefault();
      var $link = $(this);
      var ajaxdata = {
        action     : 'export-csv-variables',
        nonce_code : pluginCsvAjax.nonce,
        user_id    : $link.attr('data-user-id')
      };

      $.post(pluginCsvAjax.url, ajaxdata, function( response ) {
        saveData(response, 'variables.csv');
      });
    });
  }
  function saveData(data, fileName){
    if (!$('a.export-csv-link').get(0)) {
      var link = document.createElement("a");
      document.body.appendChild(link);
      link.style = "display: none";
      link.class = 'export-csv-link';
    }
    else{
      var link = $('a.export-csv-link');
    }

    var blob = new Blob([data], {type: "octet/stream"});
    var url = window.URL.createObjectURL(blob);
    link.href = url;
    link.download = fileName;
    link.click();
    window.URL.revokeObjectURL(url);
  }
}(jQuery));
