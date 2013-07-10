{{ Form::open('form_submission', 'POST', array('id' => 'submission_form')) }}
@if (count($form['sections']) > 2)
 <div class="submit-button-block">
  {{ Form::submit('Submit', array('id' => 'form_submit_top', 'class' => 'button form-submit')) }}
 </div>
@endif
{{ Form::token() }}
{{ Form::hidden('form_id', $form['id']) }}
{{ Form::hidden('page_id', $page_id) }}
{{ Form::text('required_control', '', array('id' => 'required_control_input', 'class' => 'submission-control', 'size' => '10')) }}
<header>
 <h1>{{ $form['description'] }}</h1>
</header>

@if (isset($validation_errors)  && (strlen($validation_errors) > 0))
<div id="contact_validation_errors" class="error">
  {{ $validation_errors }}
</div>
@endif

@foreach ($form['sections'] as $section)
 @include('forms.section')
@endforeach

<div class="submit-button-block">
 {{ Form::submit('Submit', array('id' => 'form_submit_bottom', 'class' => 'button form-submit')) }}
</div>
{{ Form::close() }}