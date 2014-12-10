<?php
class Taxonomy_Thumbs {

	static $term_id = '';
	static $taxlist = '';

	function init( $taxlist = array() ) {
		
		foreach( $taxlist as $tax ) {
			add_action("{$tax}_edit_form_fields",array(__CLASS__,'admin'));
			add_action("edit_{$tax}",	array(__CLASS__,'update'));
		}	
		
		global $pagenow;
		if (WP_ADMIN) {
    		add_action( 'admin_init', array( __CLASS__, 'fix_async_upload_image' ) );
			if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
				//add_filter( 'image_send_to_editor', array( __CLASS__, 'image_send_to_editor'), 1, 8 );
				add_filter( 'gettext', 				array( __CLASS__, 'gettext_filter' ), 1, 3 );
				add_filter( 'media_upload_tabs', 	array( __CLASS__, 'media_upload_tabs' ), 999 );
				add_filter( 'get_media_item_args',	array( __CLASS__, 'media_args_filter' ), 999 );
			}
			add_action( 'admin_init', array( __CLASS__, 'add_admin_script' ));
			
		}
		
	}

	function update($nID) {
		update_option('taxonomy_thumbnail_'.$nID, @$_POST['taxonomy_thumb_url']);
	}

	function get_thumbnail_url($ID, $tax = false) {
		$url = get_option('taxonomy_thumbnail_'.$ID);
		if ( $url || !$tax ) return $url;
		$_tp = new WP_Query(array(
			'post_type'			=> array('solution', 'service', 'product'),
			$tax 				=> $term->slug,
			'country'			=> get_geo_slug(),
			'posts_per_page'	=> -1
		));
		foreach ( $_tp->posts as $_ps ) {
			if ($url = get_product_thumbnail_src( $_ps->ID )) break;
		}
		return $url;
	}
	
	function admin ($tag) {
		$url = self::get_thumbnail_url($tag->term_id);
		if (!empty($url)) {
			$img = '<img src="'.esc_attr($url).'" height="150"  id="taxonomy_thumb_preview" />';
		} else {
			$img = '<img src="" height="150" style="display: none;" id="taxonomy_thumb_preview" />';
		}
		?>
		<tr class="form-field">
			<th><?php _e( 'Category Thumbnail', 'SwissPhone' ); ?></th>
			<td>
				<?php echo $img; ?><br />
				<a href="#add_image" onclick="return show_taxonomy_thumbs_select();"><?php _e('Select thumbnail', 'SwissPhone'); ?></a>
				| 
				<a href="#remove_image" onclick="return remove_taxonomy_thumb();"><?php _e('Remove thumbnail', 'SwissPhone'); ?></a>
				<input type="hidden" value="<?php echo esc_attr($url); ?>" name="taxonomy_thumb_url" id="taxonomy_thumb_url" />
			</td>
		</tr>
		<?php
	}

	function get_option($sName) {
		foreach (self::option as $option) {
			if ($sName == $option[Category_Custom::OPTION_NAME]) return $option[Category_Custom::OPTION_VALUE];
		}
		return false;
	}



	
	function fix_async_upload_image() {
		if(isset($_REQUEST['attachment_id'])) {
			$GLOBALS['post'] = get_post($_REQUEST['attachment_id']);
		}
	}
	
	
	function is_context() {
		return 
		(
			isset( $_REQUEST['taxonomy_thumbs'] )
			||
			(
				isset($_SERVER['HTTP_REFERER']) 
				&& 
				(strpos($_SERVER['HTTP_REFERER'], 'taxonomy_thumbs') !== false)
			)
			||
			(
				isset($_REQUEST['_wp_http_referer']) 
				&& 
				(strpos($_REQUEST['_wp_http_referer'], 'taxonomy_thumbs') !== false)
			)
		);
	}
	
	function media_args_filter( $args ) {
		if ( self::is_context() ) {
			$args['send'] = true;
		}
		return $args;
	}
	
	function gettext_filter($translated_text, $source_text, $domain) {
		if ( ($domain != 'SwissPhone') && self::is_context() ) {
			if ('Insert into Post' == $source_text) {
				return __('Set as Thumbnail', 'SwissPhone');
			}
		}
		return $translated_text;
	}
	
	function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
		/*var_dump(self::is_context() );
		var_dump($html);
		var_dump($url);
		if ( self::is_context() ) {
			?>
			<script type="text/javascript">
				var win = window.dialogArguments || opener || parent || top;
				win.taxonomy_thumb_url = '<?php echo esc_js($url); ?>';
			</script>
			<?php
		}
		die();*/
		return $html;
	}
	
	function media_upload_tabs($tabs) {
		if ( self::is_context() ) {
			/*foreach ($tabs as $key => $value) {
				if ( ! in_array($key, array('type', 'library'))) {
					unset( $tabs[$key]);
				}
			}*/
		}
		return $tabs;
	}
	
	function add_admin_script() {
		wp_enqueue_script( 'hv-taxonomy-thumbs', get_bloginfo('template_url') . '/js/taxonomy-thumbs.js',array('thickbox') );	
	}
	
}

Taxonomy_Thumbs::init( array( 'solution_category', 'product_category', 'service_category' ) );

function get_taxonomy_thumb($id, $tax = false ) {
	return Taxonomy_Thumbs::get_thumbnail_url($id, $tax);
}