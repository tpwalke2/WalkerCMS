var submitClicked = false;

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

  var contactValidationErrors = $('#contact_validation_errors')
  contactValidationErrors.hide();

  $('input#contact_submission').click(function(event) {
    contactValidationErrors.html('');
    contactValidationErrors.hide();

    var requiredControl = $('input#required_control_input').val();
    if (requiredControl != '') {
      addValidationError('Invalid submission');
      return false;
    }

    if (submitClicked) { return false; }

    submitClicked = true;
    setElementEnabled($('input#contact_submission'), false);

    // TODO: when CAPTCHA gets added, make sure to add it here
    doAJAX($('form#contact_form').attr('action'),
           contactSubmitSuccess,
           contactSubmitFailed,
           {required_control: requiredControl,
            name: $('input#name_input').val(),
            email: $('input#email_input').val(),
            message: $('textarea#message_input').val(),
            ajax: true,
            csrf_test_name: $("input[name=csrf_test_name]").val()});

    event.preventDefault();
  });
});

function doAJAX(postURL, successCallback, errorCallback, postData)
{
  $.ajax({
    url: postURL,
    cache: false,
    dataType: 'json',
    type: 'POST',
    success: successCallback,
    error: errorCallback,
    data: postData
  });
}

function addValidationError(message)
{
  var contactValidationErrors = $('#contact_validation_errors');
  contactValidationErrors.append(message);
  contactValidationErrors.show();
}

function contactSubmitFailed(jqXHR, textStatus, errorThrown)
{
  addValidationError('Status: ' + textStatus);
  addValidationError('Error Thrown: ' + errorThrown);

  submitClicked = false;
  setElementEnabled($('input#contact_submission'), true);
}

function contactSubmitSuccess(data, textStatus, jqXHR)
{
  if (data.valid_input)
  {
    $('#contact_form_container').html(data.content);
  }
  else
  {
    var contactValidationErrors = $('#contact_validation_errors');
    contactValidationErrors.html(data.validation_errors);
    contactValidationErrors.show();
  }
  submitClicked = false;
  setElementEnabled($('input#contact_submission'), true);
}

function setElementEnabled(element, enabled)
{
  if (enabled) {
    $(element).removeAttr('disabled');
  } else {
    $(element).attr('disabled', 'disabled');
  }
}