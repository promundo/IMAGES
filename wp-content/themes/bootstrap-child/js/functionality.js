(function ($) {
  $(document).ready(function() {
    initTabs();
    initMultipleSelect();
    resizeImage();
    printPage();
    selectVariable();
    myVariables();
    initSortBy();
  });

  function resizeImage(){
    $('#hero .image img').resizeToParent({'parent': '.image'});
  }

  function initTabs(){
    $( "#tabs" ).tabs();
  }

  function initSortBy(){
    $('select[name=sort]').on('change', function(event) {
      event.preventDefault();
      var value = $(this).val();
      $('input[name=sort_by]').val(value);
    });
  }

  function yearMinMax(options){
    var results = [];
    var selectedResult = [];
    var range = [];
    var currentValue = '';
    var optionsLenght = options.length;
    $(options).each(function(index, el) {
      var yearValue = el;
      if (!currentValue) {
        currentValue = yearValue;
      }
      if (currentValue == yearValue || (currentValue - 1) == yearValue) {
        range.push(yearValue);
        currentValue = yearValue;
        if (index + 1 == optionsLenght) {
          results.push(range);
          range = [];
        }
      }
      else{
        results.push(range);
        range = [];
        range.push(yearValue);
        if (index + 1 == optionsLenght) {
          results.push(range);
          range = [];
        }
        currentValue = yearValue;
      }
    });

    $(results).each(function(index, el) {
      if (el.length >= 2) {
        var minValue = Math.min.apply(null, el);
        var maxValue = Math.max.apply(null, el);
        var option = [];
        option['label'] = minValue + '-' + maxValue;
        option['all-value'] = el;
        selectedResult.push(option);
      }
      else{
        var option = [];
        option['label'] = el[0];
        option['all-value'] = el[0];
        selectedResult.push(option);
      }
    });

    return selectedResult;
  }

  function initMultipleSelect(){
    $('#year-select').multiselect({
      templates: {
        button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-placeholder">Year: </span><span class="multiselect-selected-text"></span> <b class="caret"></b></button>',
        ul: '<div class="multiselect-container dropdown-menu"></div>',
        filter: '<div class="multiselect-item multiselect-filter"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text" /></div></div>',
        filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="glyphicon glyphicon-remove-circle"></i></button></span>',
        li: '<li class="multiselect-item"><a tabindex="0"><label></label></a></li>',
        divider: '<div class="multiselect-item divider"></div>',
        liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>',
        resetButton: '<li class="multiselect-reset"><div class="input-group"><a class="btn btn-default btn-block"></a></div></li>'
      },
      buttonContainer: '<div class="year-container"></div>',
      buttonText: function(options, select) {
        var optionsLenght = options.length;
        if (optionsLenght === 0) {
          var $options_container = $('.year-container').find('.filter-options');
          $options_container.find('.option').remove();
          return 'All';
        }
        else if (optionsLenght >= 2) {
          var selectedYears = [];
          options.each(function(index, el) {
            var yearValue = parseInt($(el).val());
            selectedYears.push(yearValue);
          });

          var results = yearMinMax(selectedYears);
          var selectedOptions = [];
          $(results).each(function(index, el) {
            selectedOptions.push(el['label']);
          });

          return selectedOptions.join(', ');
        }
        else {
          var labels = [];
          options.each(function() {
            if ($(this).attr('label') !== undefined) {
              labels.push($(this).attr('label'));
            }
            else {
              labels.push($(this).html());
            }
          });
          return labels.join(', ') + '';
        }
      },
      onInitialized: function(select, container) {
          $(container).find('li.multiselect-item').wrapAll('<div class="item-conatiner"><ul class="list"></ul></div>');
          $(container).find('.multiselect-reset').wrap('<ul class="top-navigation"></ul>');
          $(container).find('.multiselect-reset').prepend('<div class="filter-options" />');
          var $options_container =  $(container).find('.filter-options');

          var selectedYears = [];
          $(select).find('option:selected').each(function(index, el) {
            var yearValue = parseInt($(el).val());
            selectedYears.push(yearValue);
          });

          var results = yearMinMax(selectedYears);
          $(results).each(function(){
            var option = this;
            if (option['all-value'].length >= 2) {
              $options_container.append('<a class="option" href="" data-value="' + option['all-value'].join(',') + '">' + option['label'] + '</a>');
            }
            else{
              $options_container.append('<a class="option" href="" data-value="' + option['label'] + '">' + option['label']+'</a>');
            }
          });

          $options_container.on('click','.option', function(event){
            var option = this;
            var selectedValues = $(option).attr('data-value');
            $(select).multiselect('deselect', selectedValues.split(','), true);
            $(option).remove();
            event.stopPropagation();
            event.preventDefault();
          });

          $(container).find('.item-conatiner').after('<div class="btn-actions"><a class="cancel" href="">Cancel</a><a class="submit" href="">Select</a></div>');

          $(container).find('.btn-actions').on('click','a',function(event){
            var class_name = $(this).attr('class');
            if (class_name == 'cancel') {
            } else if (class_name == 'submit') {
              event.stopPropagation();
              $(select).parents('form').find('.form-submit').trigger('click');
            }
            
            event.preventDefault();
          });
      },
      onChange: function(option, checked, select) {
        var $options_container = $('.year-container').find('.filter-options');
        $options_container.html('');
        var selectedYears = [];
        option.parents('select').find('option:selected').each(function(index, el) {
          var yearValue = parseInt($(el).val());
          selectedYears.push(yearValue);
        });

        var results = yearMinMax(selectedYears);
        $(results).each(function(){
          var option = this;
          if (option['all-value'].length >= 2) {
            $options_container.append('<a class="option" href="" data-value="' + option['all-value'].join(',') + '">' + option['label'] + '</a>');
          }
          else{
            $options_container.append('<a class="option" href="" data-value="' + option['label'] + '">' + option['label']+'</a>');
          }
        });
      },
      includeResetOption: true,
      resetText: "Clear all"
    });

    $('#country-select').multiselect({
      templates: {
        button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-placeholder">Country: </span><span class="multiselect-selected-text"></span> <b class="caret"></b></button>',
        ul: '<div class="multiselect-container dropdown-menu"></div>',
        filter: '<div class="multiselect-item multiselect-filter"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text" /></div></div>',
        filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="glyphicon glyphicon-remove-circle"></i></button></span>',
        li: '<li class="multiselect-item"><a tabindex="0"><label></label></a></li>',
        divider: '<div class="multiselect-item divider"></div>',
        liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>',
        resetButton: '<li class="multiselect-reset"><div class="input-group"><a class="btn btn-default btn-block"></a></div></li>'
      },
      buttonContainer: '<div class="country-container"></div>',
      buttonText: function(options, select) {

          if (options.length === 0) {
              var $options_container = $('.country-container').find('.filter-options');
              $options_container.find('.option').remove();
              return 'All';
          }
          else if (options.length > 2) {
              return $(options[0]).html() + ' + ' + (options.length - 1) + ' more';
          }
           else {
               var labels = [];
               options.each(function() {
                   if ($(this).attr('label') !== undefined) {
                       labels.push($(this).attr('label'));
                   }
                   else {
                       labels.push($(this).html());
                   }
               });
               return labels.join(', ') + '';
           }
      },
      onInitialized: function(select, container) {
          $(container).find('li.multiselect-item').wrapAll('<div class="item-conatiner"><ul class="list"></ul></div>');
          $(container).find('.multiselect-reset').wrap('<ul class="top-navigation"></ul>');
          $(container).find('.multiselect-reset').prepend('<div class="filter-options" />');
          var $options_container =  $(container).find('.filter-options');
          $(select).find('option:selected').each(function(){
            var option = this;
            $options_container.append('<a class="option" href="" data-value="' + $(option).val() + '">' + $(option).html()+'</a>');
          });

          $options_container.on('click','.option', function(event){
            var option = this;
            $(select).multiselect('deselect', $(option).attr('data-value'), true);
            $(option).remove();
            event.stopPropagation();
            event.preventDefault();
          });

          $(container).find('.item-conatiner').after('<div class="btn-actions"><a class="cancel" href="">Cancel</a><a class="submit" href="">Select</a></div>');

          $(container).find('.btn-actions').on('click','a',function(event){
            var class_name = $(this).attr('class');
            if (class_name == 'cancel') {
            } else if (class_name == 'submit') {
              event.stopPropagation();
              $(select).parents('form').find('.form-submit').trigger('click');
            }
            
            event.preventDefault();
          });
      },
      onChange: function(option, checked, select) {
          var $options_container = $('.country-container').find('.filter-options');
          if (checked === true) {
            $options_container.append('<a class="option" href="" data-value="' + $(option).val() + '">' + $(option).html()+'</a>');
          } else if (checked === false) {
            $options_container.find('a.option[data-value='+ $(option).val() +']').remove();
          }
      },
      includeResetOption: true,
      resetText: "Clear all"
    });

    $('#theme-select').multiselect({
      templates: {
        button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-placeholder">Theme: </span><span class="multiselect-selected-text"></span> <b class="caret"></b></button>',
        ul: '<div class="multiselect-container dropdown-menu"></div>',
        filter: '<div class="multiselect-item multiselect-filter"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text" /></div></div>',
        filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="glyphicon glyphicon-remove-circle"></i></button></span>',
        li: '<li class="multiselect-item"><a tabindex="0"><label></label></a></li>',
        divider: '<div class="multiselect-item divider"></div>',
        liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>',
        resetButton: '<li class="multiselect-reset"><div class="input-group"><a class="btn btn-default btn-block"></a></div></li>'
      },
      buttonContainer: '<div class="theme-container"></div>',
      buttonText: function(options, select) {

          if (options.length === 0) {
              var $options_container = $('.theme-container').find('.filter-options');
              $options_container.find('.option').remove();
              return 'All';
          }
          else if (options.length > 1) {
              return $(options[0]).html() + ' + ' + (options.length - 1) + ' more';
          }
           else {
               var labels = [];
               options.each(function() {
                   if ($(this).attr('label') !== undefined) {
                       labels.push($(this).attr('label'));
                   }
                   else {
                       labels.push($(this).html());
                   }
               });
               return labels.join(', ') + '';
           }
      },
      onInitialized: function(select, container) {
          $(container).find('li.multiselect-item').wrapAll('<div class="item-conatiner"><ul class="list"></ul></div>');
          $(container).find('.multiselect-reset').wrap('<ul class="top-navigation"></ul>');
          $(container).find('.multiselect-reset').prepend('<div class="filter-options" />');
          var $options_container =  $(container).find('.filter-options');
          $(select).find('option:selected').each(function(){
            var option = this;
            $options_container.append('<a class="option" href="" data-value="' + $(option).val() + '">' + $(option).html()+'</a>');
          });

          $options_container.on('click','.option', function(event){
            var option = this;
            $(select).multiselect('deselect', $(option).attr('data-value'), true);
            $(option).remove();
            event.stopPropagation();
            event.preventDefault();
          });

          $(container).find('.item-conatiner').after('<div class="btn-actions"><a class="cancel" href="">Cancel</a><a class="submit" href="">Select</a></div>');

          $(container).find('.btn-actions').on('click','a',function(event){
            var class_name = $(this).attr('class');
            if (class_name == 'cancel') {
            } else if (class_name == 'submit') {
              event.stopPropagation();
              $(select).parents('form').find('.form-submit').trigger('click');
            }
            
            event.preventDefault();
          });
      },
      onChange: function(option, checked, select) {
          var $options_container = $('.theme-container').find('.filter-options');
          if (checked === true) {
            $options_container.append('<a class="option" href="" data-value="' + $(option).val() + '">' + $(option).html()+'</a>');
          } else if (checked === false) {
            $options_container.find('a.option[data-value='+ $(option).val() +']').remove();
          }
      },
      includeResetOption: true,
      resetText: "Clear all"
    });
  }

  function printPage(){
    $('.print').on('click', function(event) {
      event.preventDefault();
      window.print();
      return false;
    });
  }

  function selectVariable(){
    $('#select-variable').on('click', function(event) {
      $('body').addClass('send-ajax');
      showLoader();
      event.preventDefault();
      var $link = $(this);
      var $menuItem = $('.variables-selected-menu-item.nav-link');
      var ajaxdata = {
        action     : 'select-variable',
        nonce_code : myajax.nonce,
        post_id    : $link.attr('data-post-id')
      };

      $.post( myajax.url, ajaxdata, function( response ) {
        $menuItem.text(response + ' Variables Selected');
        $('body').removeClass('send-ajax');
        hideLoader();
      });
    });
  }

  function updateCountVariable(){
    var $menuItem = $('.variables-selected-menu-item.nav-link');
    var ajaxdata = {
      action     : 'get-count-variable',
      nonce_code : myajax.nonce,
    };

    $.post( myajax.url, ajaxdata, function( response ) {
      $menuItem.text(response + ' Variables Selected');
    });
  }

  function myVariables(){
    $('#select-all').on('change', function(event) {
      var $checkboxes = $('.responsive-table tbody input[name="select-variable[]"]');
      if ($(this).prop('checked')) {
        $checkboxes.prop('checked', true);
      }
      else{
        $checkboxes.prop('checked', false);
      }
    });

    $('.remove-all').on('click', function(event) {
      event.preventDefault();
      var $checkboxes = $('.responsive-table tbody input[name="select-variable[]"]:checked');
      if ($checkboxes.get(0)) {
        $('body').addClass('send-ajax');
        showLoader();
        var $variables = $checkboxes.parents('tr');
        var ids = [];
        $checkboxes.each(function(index, el) {
          ids.push($(el).val());
        });

        var ajaxdata = {
          action     : 'remove-variable',
          nonce_code : myajax.nonce,
          var_ids    : ids
        };

        $.post( myajax.url, ajaxdata, function( response ) {
          if(response == 1){
            $variables.fadeOut(100, function(){
              updateCountVariable();
              $(this).remove();
              if (!$('.responsive-table tbody tr:not(.hide-block)').get(0)) {
                $('.responsive-table tbody tr.hide-block').show();
              }
            });
          }
          $('body').removeClass('send-ajax');
          hideLoader();
        });
      }
      
    });

    $('.remove-variable').on('click', function(event) {
      $('body').addClass('send-ajax');
      showLoader();
      event.preventDefault();
      var $variable = $(this).parents('tr');
      var id = $variable.attr('data-id');
      var ajaxdata = {
        action     : 'remove-variable',
        nonce_code : myajax.nonce,
        var_ids    : [id]
      };

      $.post( myajax.url, ajaxdata, function( response ) {
        if(response == 1){
          $variable.fadeOut(100, function(){
            updateCountVariable();
            $(this).remove();
            if (!$('.responsive-table tbody tr:not(.hide-block)').get(0)) {
              $('.responsive-table tbody tr.hide-block').show();
            }
          });
        }
        $('body').removeClass('send-ajax');
        hideLoader();
      });
    });
  }

  function showLoader(){
    if (!$('#fader').length) {
      $('body').after('<div id="fader"><div class="loader" /></div>');
    }
    $('#fader').show();
  }

  function hideLoader(){
    $('#fader').hide();
  }


}(jQuery));
