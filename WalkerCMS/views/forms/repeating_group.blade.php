 <div id="template_{{ $item['fully_qualified_id'] }}" class="repeating-group-template">
 @foreach ($item['items'] as $item)
  @include('forms.item')
 @endforeach
 </div>
 {{ Form::button('Add', array('id' => "add_item_{$item['fully_qualified_id']}", 'class' => 'add-repeating-group-item')) }}
 
 {{-- TODO: handle multiple entries --}}