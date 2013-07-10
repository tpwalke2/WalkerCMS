<div id="section_{{ $section['id'] }}" class="form-section"{{ (isset($section['depends_on']) ? "data-dependson=\"{$section['depends_on']}\"" : '') }}>
 @if ($section['show_label'])
 <header>
  <h2>{{ $section['description'] }}</h2>
 </header>
 @endif
 @foreach ($section['items'] as $item)
 @include('forms.item')
 @endforeach
</div>