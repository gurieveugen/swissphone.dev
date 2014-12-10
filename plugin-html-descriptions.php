<?php
class HV_Taxonomy_HTML_Descriptions {
	
	private function __construct() {}
	
	static $taxlist	=	array(); 
	
	static function init( $taxlist ) {
		self::$taxlist = $taxlist;
		foreach($taxlist as $tax) {
			add_action( $tax . '_pre_edit_form', array( __CLASS__, 'pre_tax_edit_form' ));
			add_action( $tax . '_edit_form', 	 array( __CLASS__, 'after_tax_edit_form' ));
		}
		if (is_admin()) {
			wp_enqueue_script('tiny_mce'); 
		}

		$filters = array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description');
		foreach ( $filters as $filter ) {
			remove_filter($filter, 'wp_filter_kses');
		}
		
		foreach ( array( 'term_description' ) as $filter ) {
			remove_filter( $filter, 'wp_kses_data' );
		}		
		
	}
	
	function pre_tax_edit_form() {
		ob_start();
	}
	
	function after_tax_edit_form() {
		$html = ob_get_contents();
		ob_end_clean();
		$html = str_ireplace('name="description"', 'name="description" class="theEditor" ', $html);
		echo $html;
		add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 ); 
		wp_insert_term();
	}
	
}

HV_Taxonomy_HTML_Descriptions::init( array( 'solution_category', 'product_category', 'service_category' ) );