<?php
// Google AdWords
$cptypes = array('post', 'page', 'News', 'product', 'service', 'solution', 'dealer', 'dealer');

add_action('add_meta_boxes', 'google_adwords_add_meta_boxes', 0);
function google_adwords_add_meta_boxes() {
	global $cptypes;
	foreach($cptypes as $cptype) {
		add_meta_box($cptype.'-google-adwords-box', 'Google Adwords Code', 'google_adwords_meta_box', $cptype, 'normal', 'low');
	}
}

function google_adwords_meta_box() {
	global $post;
	$google_adwords_code = get_post_meta($post->ID, 'google_adwords_code', true);

	echo '<input type="hidden" name="google_adwords_noncename" id="google_adwords_noncename" value="' . 
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';	
?>
	<textarea name="google_adwords_code" style="width:99%; height:200px;"><?php echo $google_adwords_code; ?></textarea>
<?php
}

add_action('save_post', 'save_google_adwords'); 
function save_google_adwords($post_id){
	global $post, $cptypes;
	
	if ( !wp_verify_nonce( $_POST['google_adwords_noncename'], plugin_basename(__FILE__) )) {
	    return $post_id;
	}
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	    return $post_id;
	}
	
	if(in_array($post->post_type, $cptypes) && $_SERVER['REQUEST_METHOD'] == 'POST' && strlen(trim($_POST["google_adwords_code"]))) {
		update_post_meta($post->ID, "google_adwords_code", trim($_POST["google_adwords_code"]));
	}
}

?>