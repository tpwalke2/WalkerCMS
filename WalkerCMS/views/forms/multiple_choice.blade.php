@if (isset($item['choices']))
 @if ((count($item['choices']) > 5) || (isset($item['force_list']) && $item['force_list']))
  {{ Form::select($item['input_name'], array_merge(array('Select one'), $item['choices']), $item_values[$item['input_name']], array('id' => $item['fully_qualified_id'])) }}
 @else
  @foreach ($item['choices'] as $choice_value => $choice_desc)
   <span>{{ Form::radio($item['input_name'], $choice_value, ($choice_value == $item_values[$item['input_name']]), array('id' => $item['fully_qualified_id'])) }} {{ $choice_desc }}</span>
  @endforeach
 @endif
@endif