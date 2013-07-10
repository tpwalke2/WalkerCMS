<div id="{{ $section['id'] }}_{{ $item['id'] }}_row">
 @include('forms.label')
 <div class="form-row-item {{str_replace('_', '-', $item['type'])}}">
  @include('forms.' . $item['type'])
  @if (isset($item_errors[$item['fully_qualified_id']]))
   <label class="error" for="{{$item['fully_qualified_id']}}">{{ $item_errors[$item['fully_qualified_id']] }}</label>
  @endif
 </div>
</div>