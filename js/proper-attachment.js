var global_send_to_editor_callback = window.send_to_editor;

function hv_send_to_editor(h) {
	// close thickbox
	tb_remove();
	window.send_to_editor = global_send_to_editor_callback;
	add_new_dld( self.HV_sent_title, self.HV_sent_url );
}


function show_dld_select() {
	global_send_to_editor_callback = window.send_to_editor;
	window.send_to_editor = window.hv_send_to_editor;
	tb_show("Add download link", 'media-upload.php/?hv_add_attachment=true&amp;type=file&amp;TB_iframe=true', false);
}