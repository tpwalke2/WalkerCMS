<div id="submission_result_failure">
  <h3>We were unable to process your contact form submission.</h3>
  @if (isset($submission_errors) && (strlen($submission_errors) > 0))
  <p>We encountered the following problem(s):</p>
  <pre>
    {{ $submission_errors }}
  </pre>
  @endif
  <p>
    If you continue to have problems submitting the form, please notify
    <a href="mailto:{{ $admin_email }}" title="Administrator Email Address">the website administrator</a>.
  </p>
  <p>
    <a href="{{ $submitting_page_id }}" title="Return to the {{ $form_description }}">Return</a>
  </p>
</div>
