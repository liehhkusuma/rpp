<tr>
	<td colspan="99" class="text-center">
		@if (get('keyword'))
		  <h4>{{ lang_var("gen.search_notfound", ['type' => $page['type'], 'keyword' => get('keyword')]) }}</h4>
		@else
		  <h4>{{ lang_var("gen.data_notfound", ['type' => $page['type']]) }}</h4>
		@endif
	</td>
</tr>