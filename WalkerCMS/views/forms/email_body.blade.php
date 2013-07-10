The following data was submitted from the '{{ $form_description }}' form.

-------------------BEGIN SUBMITTED FORM DATA----------------

@foreach ($input_items as $item)
 {{ $item['description'] }} : {{ $item['value'] }}{{ "\n" }}
@endforeach

--------------------END SUBMITTED FORM DATA-----------------

---------------------BEGIN DEBUGGING INFO-------------------

Form ID: '{{ $form_id }}'
Spam prevention: '{{ $spam_control }}'
IP Address: '{{ $ip_address }}'
User-Agent: '{{ $user_agent }}'

----------------------END DEBUGGING INFO--------------------