<?php
class HV_Proper_Attachments {
	
	function __construct() {
		global $pagenow;
		if (WP_ADMIN) {
    		add_action( 'admin_init', array( $this, 'fix_async_upload_image' ) );
			if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
				add_filter( 'image_send_to_editor', array( $this, 'image_send_to_editor'), 1, 8 );
				add_filter( 'media_send_to_editor', array( $this, 'media_send_to_editor'), 1, 3 );
				add_filter( 'gettext', 				array( $this, 'gettext_filter' ), 1, 3 );
				add_filter( 'media_upload_tabs', 	array( $this, 'media_upload_tabs' ), 999 );
			}
			add_action( 'admin_init', array(&$this, 'add_admin_script' ));
			
		}
	}
	
	function fix_async_upload_image() {
		if(isset($_REQUEST['attachment_id'])) {
			$GLOBALS['post'] = get_post($_REQUEST['attachment_id']);
		}
	}
	
	
	function is_context() {
		return 
		(
			isset( $_REQUEST['hv_add_attachment'] )
			||
			(
				isset($_SERVER['HTTP_REFERER']) 
				&& 
				(strpos($_SERVER['HTTP_REFERER'], 'hv_add_attachment') !== false)
			)
			||
			(
				isset($_REQUEST['_wp_http_referer']) 
				&& 
				(strpos($_REQUEST['_wp_http_referer'], 'hv_add_attachment') !== false)
			)
		);
	}
	
	function gettext_filter($translated_text, $source_text, $domain) {
		if ( $this->is_context() ) {
			if ('Insert into Post' == $source_text) {
				return 'Add as Download Link';
			}
			if ( 'Search Media' == $source_text ) {
				echo '<input type="hidden" value="1" name="hv_add_attachment" />';
			}
		}
		return $translated_text;
	}
	
	function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
		$att= array( 
			'url' 			=> $url,
			'post_title' 	=> ($_t = $title)?$_t:$alt
		);
		return media_send_to_editor($html, $id, $att);
	}
	
	function media_send_to_editor( $html, $id, $att ) {
		if ( $this->is_context() ) {
			?>
			<script type="text/javascript">
				var win = window.dialogArguments || opener || parent || top;
				win.HV_sent_html = '<?php echo addslashes($html) ?>';
				win.HV_sent_id = '<?php echo $id ?>';
				win.HV_sent_title = '<?php echo addslashes($att['post_title']); ?>';
				win.HV_sent_url = '<?php echo addslashes($att['url']); ?>';
			</script>
			<?php
		}
		return $html;
	}	

	function media_upload_tabs($tabs) {
		if ( $this->is_context() ) {
			foreach ($tabs as $key => $value) {
				if ( ! in_array($key, array('type', 'library'))) {
					unset( $tabs[$key]);
				}
			}
		}
		return $tabs;
	}
	
	function add_admin_script() {
		wp_enqueue_script( 'hv-proper-attachments-script', get_bloginfo('template_url') . '/js/proper-attachment.js' );	
	}
	
}
global $HV_Proper_attachments;
$HV_Proper_attachments = new HV_Proper_Attachments();

?>
