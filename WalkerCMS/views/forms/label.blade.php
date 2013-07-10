<div class="form-row-label">
@if ($item['show_label'])
 {{ Form::label("{$item['fully_qualified_id']}_label", $item['description'], array('for' => $item['fully_qualified_id'])) }}
@endif
</div>