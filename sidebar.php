<?php
/**
 * @package WordPress
 * @subpackage SwissPhone
 */
global $sitepress, $posts;
$sb = 'pages-sidebar';
if ( is_search() ) {
	$sb = 'search-sidebar';
} elseif ( is_page() ) {
	$trid 		= $sitepress->get_element_trid( $posts[0]->ID, 'post_page' );
	$translations = $sitepress->get_element_translations( $trid, 'post_page' );
	$id = @$translations['en']->element_id;
	while ( $id ) {
		$tsb = 'page-'.$id.'-sidebar';
		if ( is_active_sidebar($tsb) ) {
			$sb = $tsb;
			break;
		}

		
		$ps = get_post($id);
		$id = @$ps->post_parent;
	}
}
if ( is_singular(array('post','product','service','solution')) ) {
	global $posts; 
	if ($posts[0]->post_parent) {
		get_sidebar('solutions');
	}
} else {
	dynamic_sidebar( $sb );
}