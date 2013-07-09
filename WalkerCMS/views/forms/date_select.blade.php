{{ Form::hidden($item['input_name'], $item_values[$item['input_name']], array('id' => $item['fully_qualified_id'])) }}

{{ Form::label("{$item['input_name']}_month_label", 'Month', array('for' => "{$item['fully_qualified_id']}_month", 'class' => 'month-label')) }}
{{ Form::select("{$item['input_name']}_month", $full_month_names, null, array('id' => "{$item['fully_qualified_id']}_month", 'class' => 'month-input')) }}

{{ Form::label("{$item['input_name']}_day_label", 'Day', array('for' => "{$item['fully_qualified_id']}_day", 'class' => 'day-label')) }}
{{ Form::select("{$item['input_name']}_day", $dates, null, array('id' => "{$item['fully_qualified_id']}_day", 'class' => 'day-input')) }}

{{ Form::label("{$item['input_name']}_year_label", 'Year', array('for' => "{$item['fully_qualified_id']}_year", 'class' => 'year-label')) }}
{{ Form::select("{$item['input_name']}_year", $years, null, array('id' => "{$item['fully_qualified_id']}_year", 'class' => 'year-input')) }}