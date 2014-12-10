<div class="hv-metabox-sortable-block">
	<h4>${title}</h4>
	${before_table}
	<table id="sor-table-${table_id}">
		<tbody>
			${table_body}
		</tbody>
		<tfoot>
			<tr>
				<th>${th_title}</th>
				<th>${th_value}</th>
				<th>${th_target}</th>
				<th>${th_additional}</th>
			</tr>
			<tr class="empty-row">
				<td>
					<input type="text" 		class="empty-row-title" name="meta_${name}_titles[]" value="" /><br />
					<span class="additional_buttons">${additional_buttons}</span>
					<input type="button" 	class="button clone-row-button" value="add" />
				</td>
				<td>
					<textarea class="empty-row-value" name="meta_${name}_values[]"></textarea>
				</td>
				<td style="${target_style}">
					<input type="checkbox" 		name="meta_${name}_targets[]" value="_blank" /> Open in new window<br />
				</td>
				<td>
					${additional_cell}
				</td>
			</tr>
			${table_footer}
		</tfoot>						
	</table>
	
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var he	= ${hide_empty};
			if ( he ) {
				$("#sor-table-${table_id} tfoot .empty-row").css('display','none');
			}
			$("#sor-table-${table_id} tbody").sortable();
			$("#sor-table-${table_id} tbody tr .remove-row-button").live( 
				"click", 
				function(){
					$(this).closest('tr').animate({opacity: '0'}, 300,function(){
						$(this).remove();
					});
				}
			);
			$("#sor-table-${table_id} tfoot tr .clone-row-button").click(function(){
				$(this).closest('tr').clone().hide()
					.appendTo($('#sor-table-${table_id} tbody'))
					.removeClass('empty-row')
					.find('textarea').each(function(index){
						$(this).val($("#sor-table-${table_id} tfoot tr textarea").eq(index).val());
					})
					.end()
					.find('span.additional_buttons').remove().end()
					.find('.clone-row-button')
						.val('delete')
						.removeClass('clone-row-button')
						.addClass('remove-row-button')
					.end()				
					.show(400);
				$("#sor-table-${table_id} tbody").sortable("refresh");
				$(this).closest('tr')
					.find('input.empty-row-title').val('').end()
					.find('textarea').val('').end();
			});
		});	
	</script>
</div>