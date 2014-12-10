<?php
class HV_Custom_Links {
	
	private function __construct() {}
	
	static function register_cpt() {
		
	  $labels = array(
	    'name' 			=> __('Links', 'SwissPhone'),
	    'singular_name' => __('Link', 'SwissPhone'),
	    'add_new' 		=> __('Add new', 'SwissPhone'),
	    'add_new_item' 	=> __('Add new Link', 'SwissPhone'),
	    'edit_item' 	=> __('Edit Link', 'SwissPhone'),
	    'new_item' 		=> __('Add Link', 'SwissPhone'),
	    'view_item' 	=> __('View Link', 'SwissPhone'),
	    'search_items' 	=> __('Search links', 'SwissPhone'),
	    'not_found' 	=> __('No Links found', 'SwissPhone'),
	    'not_found_in_trash' 	=> __('No Links found in Trash', 'SwissPhone'), 
	    'parent_item_colon' 	=> '',
	    'menu_name' 	=> 'Links'
	
	  );
	  
	  $args = array(
	    'labels' 		=> $labels,
	    'public' 		=> false,
	    'publicly_queryable' => false,
	    'show_ui' 		=> true, 
	    'show_in_menu' 	=> true, 
	    'query_var' 	=> true,
	    'rewrite' 		=> false,
	    'capability_type' => 'post',
	    'has_archive' 	=> false, 
	    'hierarchical' 	=> false,
	    'menu_position' => null,
	    'supports' 		=> array('title','thumbnail')
	  ); 
	  register_post_type('link',$args);	

	}
	
	static function init() {
		add_action( 'init', array( __CLASS__, 'register_cpt') );
		add_action( 'admin_init', array( __CLASS__,'setupMetaboxes') );
	}
	
	function setupMetaboxes() {
		add_meta_box('hv_slider_box', __('Link Options','SwissPhone'),array(__CLASS__,'showMetabox'),'link','normal','high');
		add_action('save_post', array(__CLASS__,'saveSlide'));
	}

	function saveSlide($nPostID) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $nPostID;
		if ( 'link' != $_POST['post_type'] ) return $nPostID;
		if ( @$_POST['existing_page_select'] && !empty($_POST['existing_page_select']) ) {
			update_post_meta($nPostID,'custom_link', $_POST['existing_page_select']);
		} else {
			update_post_meta($nPostID,'custom_link', $_POST['custom_link']);
		}
	}

	function showMetabox($oPost) {
		?>
		<table style="width: 100%">
		<?php
			$int_list = '';
			$ipages = new WP_Query(array(
				'post_type' => array('product','solution', 'service', 'page', 'post', 'news'),
				'showposts'	=> -1,
				'orderby'   => 'title',
				'order'		=> 'ASC'
			));
			$cur = get_post_meta($oPost->ID,'custom_link',true);
			foreach ( $ipages->posts as $comp ) {
				$url = get_permalink($comp->ID);
				$sel = ($cur == $url)?'selected':'';
				$int_list .= '<option value="'.esc_attr($url).'" '.$sel.' >'.$comp->post_title.' - ('.$comp->post_type.') </option>';
			} 
			$all_terms = get_terms(array(
				'service_category', 'solution_category', 'product_category', 'news_category', 'category' 
			), array(
				'hide_empty' => 0
			));
			foreach ( $all_terms as $term ) {
				$url = get_term_link($term);
				$sel = ($cur == $url)?'selected':'';
				$int_list .= '<option value="'.esc_attr($url).'" '.$sel.' >'.remove_brs($term->name).' (category) </option>';
			} 
			
		?>
		<tr>
			<td><?php _e( 'Custom Link', 'SwissPhone' ); ?></td>
			<td><input type="text" name="custom_link" value="<?php echo esc_attr(get_post_meta($oPost->ID, 'link_custom')); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Link to page', 'SwissPhone'); ?></td>
			<td>
				<select name="existing_page_select">
					<option value=" "><?php _e('Custom link', 'SwissPhone'); ?></option>
					<?php echo $int_list; ?> 
				</select>
			</td>
		</tr>
		</table>
		<?php
	}
	
}

HV_Custom_Links::init();