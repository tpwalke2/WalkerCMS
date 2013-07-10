$(function() {
  $('img.showpopup').each(function() {
    var link = $(this).attr('src').replace(/\.(jpg|gif|png)$/, '_large.$1');
    var title = $(this).attr('alt');
    var rel = $.trim($(this).attr('rel'));
    if ((typeof rel === 'undefined') || (rel == '')) {
      rel = '';
    } else { rel = ' rel="' + rel + '"'; }

    $(this).wrap('<a href="' + link + '" title="' + title + '"' + rel + ' class="showpopup">');
  });

  $('a.showpopup').fancybox({
    fitToView: true,
    openEffect: 'fade',
    nextEffect: 'fade',
    prevEffect: 'fade',
    helpers : {
      title: {
        type: 'inside'
      },
      overlay : {
        opacity: 0.6,
        css : {
          'background-color' : '#000'
        }
      }
    }
  });

  $(".pdfviewer").click(function() {
    $.fancybox({
      width: 870,
      height: 500,
      autoSize: false,
      content: '<embed src="'+this.href+'#nameddest=self&page=1&view=FitH,0&zoom=80,0,0" type="application/pdf" height="99%" width="99%" />',
      onClosed: function() {
        $("#fancybox-inner").empty();
      }
    });
    return false;
  });
  
  var contactValidationErrors = $('#contact_validation_errors');
  contactValidationErrors.hide();
  
  $('#contact_form').ajaxForm({
    beforeSubmit: beforeContactSubmit,
    success:      contactSubmitSuccess,
    error:        contactSubmitFailed,
    dataType:     'json',
    data: {
      csrf_test_name: $("input[name=csrf_token]").val()
    }
  }).validate({
    debug: true,
    rules: {
      name: "required",
      email: {
        required: true,
        email: true
      },
      message: "required"
    },
    messages: {
      name: "Please specify your name",
      email: {
        required: "We need your email address to contact you",
        email: "Your email address must be in the format of name@domain.com"
      },
      message: "Please enter a message"
    }
  });
  
  setDateParts();
  
  $('select.month-input').change(function(event) {
    updateDate(this, 'month');
  });
  
  $('select.year-input').change(function(event) {
    updateDate(this, 'year');
  });
  
  $('select.day-input').change(function(event) {
    updateDate(this, 'day');
  });
});

function setDateParts() 
{
  var hiddenDateSelects = $('.date-select input[type=hidden]');
  
  for (var i = 0; i < hiddenDateSelects.length; i++)
  {
    var currentDateInput = $(hiddenDateSelects[i]);
    var currentDate = getDateFromISOValue(currentDateInput.val());
    $('#' + currentDateInput.attr('id') + '_month').val(currentDate.getMonth());
    $('#' + currentDateInput.attr('id') + '_day').val(currentDate.getDate());
    $('#' + currentDateInput.attr('id') + '_year').val(currentDate.getFullYear());
  }
}

function updateDate(jsObj, type)
{
  var jqObj = $(jsObj);
  var dateElementID = jqObj.attr('id').replace('_' + type, '');
  var dateElement = $('input#' + dateElementID);
  var currentDate = getDateFromISOValue(dateElement.val());
  if (type == 'month')
  {
    currentDate.setMonth(jqObj.val());
  } else if (type == 'year') {
    currentDate.setFullYear(jqObj.val());
  } else if (type == 'day') {
    currentDate.setDate(jqObj.val());
  }
  
  dateElement.val(getISODateString(currentDate));
}

function getDateFromISOValue(isoDateString)
{
  if ((typeof isoDateString === 'undefined') || (isoDateString == '')) {return new Date(); }
  
  var dateParts = isoDateString.split('-');
  return new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
}

function getISODateString(date)
{
  var year = date.getFullYear();
  var month = date.getMonth() + 1;
  var day = date.getDate();
  
  return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
}

function addValidationError(message)
{
  var contactValidationErrors = $('#contact_validation_errors');
  contactValidationErrors.append(message);
  contactValidationErrors.show();
}

function beforeContactSubmit(formData, jqForm, options)
{
  setElementEnabled($('input#contact_submission'), false);
  
  var requiredControl = $('input#required_control_input').val();
  if (requiredControl != '') {
    addValidationError('Invalid submission');
    setElementEnabled($('input#contact_submission'), true);
    return false;
  }
  
  return true;
}

function contactSubmitFailed(jqXHR, textStatus, errorThrown)
{
  addValidationError('Status: ' + textStatus);
  addValidationError('Error Thrown: ' + errorThrown);

  setElementEnabled($('input#contact_submission'), true);
}

function contactSubmitSuccess(data, textStatus, jqXHR)
{
  if (data.valid_input)
  {
    $('#contact_form').parent().html(data.content);
  }
  else
  {
    var contactValidationErrors = $('#contact_validation_errors');
    contactValidationErrors.html(data.validation_errors);
    contactValidationErrors.show();
  }
  setElementEnabled($('input#contact_submission'), true);
}

var disabled = 'disabled';

function setElementEnabled(element, enabled)
{
  if (enabled) {
    $(element).removeAttr(disabled);
  } else {
    $(element).attr(disabled, disabled);
  }
}