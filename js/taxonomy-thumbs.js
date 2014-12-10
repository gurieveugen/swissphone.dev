var taxonomy_thumbs_callback_save = window.send_to_editor;

function taxonomy_thumbs_send_to_editor(h) {
	// close thickbox
	tb_remove();
	window.send_to_editor = taxonomy_thumbs_callback_save;
	//var url = self.taxonomy_thumb_url;
	url = jQuery('img',h).attr('src');
	//alert(url);	
	jQuery('#taxonomy_thumb_url').val(url);
	jQuery('#taxonomy_thumb_preview').attr('src', url).css('display','block');
}

function show_taxonomy_thumbs_select() {
	taxonomy_thumbs_callback_save = window.send_to_editor;
	window.send_to_editor = window.taxonomy_thumbs_send_to_editor;
	tb_show("Select Thumbnail", 'media-upload.php/?type=image&taxonomy_thumbs=true&TB_iframe=true', false);
	return false;
}

function remove_taxonomy_thumb() {
	jQuery('#taxonomy_thumb_url').val('');
	jQuery('#taxonomy_thumb_preview').hide('fast');
	return false;
}