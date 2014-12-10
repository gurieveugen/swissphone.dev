<?php
class HV_HTML_Excerpts {
	
	private function __construct() {}
	
	static $types 	=	array();
	
	static function init( $ptypes = array() ) {
		self::$types= 	$ptypes;
		add_action( 'admin_init', array(__CLASS__, 'init_metaboxes'));
		add_action( 'save_post', array( __CLASS__, 'save_excerpt') );
		add_filter( 'get_the_excerpt', array( __CLASS__, 'filter_excerpt'));
		add_filter( 'the_excerpt', array( __CLASS__, 'filter_excerpt'));
	}
	
	static function init_metaboxes() {
		foreach (self::$types as $type) {
			add_meta_box('mb_html_excerpt', __('Excerpt'), array( __CLASS__, 'excerpt_metabox') , $type, 'normal', 'high');
		}
	}
	
	static function excerpt_metabox($post) {
		$exr = esc_textarea(get_post_meta($post->ID, 'html_excerpt', true));
		if (!$exr) $exr = $post->post_excerpt;
		wp_nonce_field('hv_edit_post','hv_edit_post_nonce');
		wp_editor( html_entity_decode($exr), 'html_excerpt', array('textarea_rows'=>5) );
	}
	
	static function save_excerpt( $nPostID ) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $nPostID;
		if ( in_array($_POST['post_type'], self::$types ) && wp_verify_nonce(@$_POST['hv_edit_post_nonce'], 'hv_edit_post')) {
			update_post_meta($nPostID, 'html_excerpt', stripslashes(@$_POST['html_excerpt']));
		}
	}
	
	static function filter_excerpt( $old ){
		$new = get_post_meta( get_the_ID(), 'html_excerpt', true );
		if ($new) return $new;
		return $old;
	}
}

HV_HTML_Excerpts::init( array( 'product', 'solution', 'service' ) );