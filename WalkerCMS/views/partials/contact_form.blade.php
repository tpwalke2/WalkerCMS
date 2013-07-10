{{ Form::open('contact_submission', 'POST', array('id' => 'contact_form')) }}
@if (isset($validation_errors)  && (strlen($validation_errors) > 0))
<div id="contact_validation_errors" class="error">
  {{ $validation_errors }}
</div>
@endif
<p>
  {{ Form::token() }}
  
  <input id="page_id" name="page_id" type="hidden" value="{{ $submitting_page_id }}" />
  <input id="required_control_input" name="required_control" type="text" size="10" class="submission-control" />
  <label for="name_input">Name</label>
  <input id="name_input" name="name" title="Your Name" type="text" size="50" value="{{ $submitter_name }}" />
  @if (isset($name_validation_error))
  <label class="error" for="name_input">{{ $name_validation_error }}</label>
  @endif
  <label for="email_input">Email</label>
  <input id="email_input" name="email" title="Your Email" type="text" size="50" maxlength="100" value="{{ $submitter_email }}" />
  @if (isset($email_validation_error))
  <label class="error" for="email_input">{{ $email_validation_error }}</label>
  @endif
  <label for="message_input">Message</label>
  <textarea id="message_input" rows="5" cols="5" name="message">{{ $message }}</textarea>
  @if (isset($message_validation_error))
  <label class="error" for="message_input">{{ $message_validation_error }}</label>
  @endif
  <br />
  <span class="contact-legend">All fields required.</span><br />
  <input id="contact_submission" class="button" type="submit" value="Send" />
</p>
{{ Form::close() }}