<?php
foreach ($nav_items as $index => $nav_item):
 $inner = $nav_item['description'];
 if ($nav_item['has_custom_content']):
  $inner = $nav_item['custom_content'];
  $nav_item['is_active_section'] = false;
 elseif ($nav_item['generate_link']):
  $externalIndicator = ($nav_item['is_external'] ? ' (external link)' : '');
  $externalTarget = ($nav_item['is_external'] ? ' target="_blank"' : '');
  $inner = "<a href=\"{$nav_item['url']}\" title=\"$organization_name - {$nav_item['tooltip']}$externalIndicator\"$externalTarget>$inner</a>";
 endif;
 $selected_class = ($nav_item['is_active_section'] ? 'currently-selected' : 'unselected'); ?>
 <li id="nav_{{ $nav_item['page_id'] }}" class="item{{ $index + 1 }} {{ $selected_class }}">{{ $inner }}</li>
@endforeach