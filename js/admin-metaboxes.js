jQuery(document).ready(function($){
	$('#sor-table-components #new-comp-selector').change(function(){
		$('#sor-table-components tfoot tr.empty-row')
			.find('input.empty-row-title').val($(this).find('option:selected').text()).end()
			.find('.empty-row-value').val($(this).val()).end()
			.find('.clone-row-button').click()
	});
	
	$('.internal-page-selector').change(function(){
		$(this).closest('tr.empty-row')
			.find('input.empty-row-title').val($(this).find('option:selected').text()).end()
			.find('.empty-row-value').val($(this).val()).end()
			.find('.clone-row-button').click()
	});
	
	$('#sor-table-downloads .select-dld').click(function(){
		show_dld_select();
	});
	
});

function add_new_dld( title, url ) {
	jQuery('#sor-table-downloads tfoot tr.empty-row')
		.find('input.empty-row-title').val(title).end()
		.find('.empty-row-value').val(url).end()
		.find('.clone-row-button').click()
	;
}

