<?php
session_start();
ini_set('memory_limit', '1048M');
//ini_set('max_execution_time', '999');
//set_time_limit(999999);

add_action( 'wp_enqueue_scripts', 'swissphones_scripts_method' );
function swissphones_scripts_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'googlejquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_enqueue_script( 'googlejquery' );
    
    wp_deregister_script( 'swfobject' ); 
	wp_register_script( 'swfobject', 'https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js' );     
	wp_enqueue_script( 'swfobject' );
    
	wp_deregister_script( 'jquery-ui' );	 
	wp_register_script( 'jquery-ui' , 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
	wp_enqueue_script( 'jquery-ui' );
}

add_action( 'admin_menu', 'add_sp_theme_admin' );

function add_sp_theme_admin() {
    add_theme_page( 'Site Options', ' Options', 10, 'edit-site-options', 'sp_site_admin_page' );
}

function sp_site_admin_page() {
    $msg = '';
    if ( ! empty($_POST['action']) && wp_verify_nonce($_POST['cache_options_nonce'], 'cache_options') ) {
        
        switch ($_POST['action']) {
            case 'save':
                update_option( 'custom_cache_mode', $_POST['page_cache'] );
                update_option( 'custom_cache_timeout', $_POST['cache_timeout'] );
                update_option( 'custom_cache_show', $_POST['cache_show'] );
                $msg = '<p><strong>Options saved</strong></p>';
                break;
            case 'purge':
                purge_all_c_cache();
                $msg = '<p><strong>Cache cleared</strong></p>';
        }
    }
	
	if ( isset($_POST['ftp_options_nonce']) && wp_verify_nonce($_POST['ftp_options_nonce'], 'ftp_options') ) {
        update_option( 'export_ftp_server', $_POST['ftp_server'] );
        update_option( 'export_ftp_login', $_POST['ftp_login'] );
        if ( $pss = $_POST['ftp_pass']) update_option( 'export_ftp_password', $pss );
        update_option( 'export_ftp_dir', $_POST['ftp_dir'] );
        update_option( 'export_interval', $_POST['ftp_export_interval'] );
		$msg = '<p><strong>Options saved</strong></p>';
    }
    
    $opt_server = get_option( 'export_ftp_server', '' );
    $opt_login = get_option( 'export_ftp_login', '' );
    $opt_dir = get_option( 'export_ftp_dir', '/' );
	$opt_csv  = get_option( 'export_interval', 300 );
	
    $opt_mode = get_option( 'custom_cache_mode', 'on' );
    $opt_show = get_option( 'custom_cache_show', 'all' );
    ?>
    <div class="wrap">
        <br />
        <h2>Cache options</h2>
        <?php echo $msg; ?>
        <br />
        <form action="" method="post">
            <?php wp_nonce_field( 'cache_options', 'cache_options_nonce' ); ?>
            <input type="hidden" name="action" value="save" />
            <table cellpadding="5" cellspacing="5">
                <tr>
                    <td>Page Caching </td>
                    <td><input type="radio" value="on" <?php checked( 'on', $opt_mode ); ?> name="page_cache" /> On <input type="radio" <?php checked( 'off', $opt_mode ); ?> value="off" name="page_cache" /> Off </td>
                </tr>
                <tr>
                    <td>Cache Timeout (sec) </td>
                    <td><input type="text" size="5" name="cache_timeout" value="<?php echo get_option( 'custom_cache_timeout', 1800 ); ?>" /></td>
                </tr>
                <tr>
                    <td>Show cache </td>
                    <td><input type="radio" value="all" size="5" <?php checked( 'all', $opt_show ); ?> name="cache_show" /> All users <input type="radio" <?php checked( 'visitors', $opt_show ); ?> value="visitors" name="cache_show" /> non-registered only </td>
                </tr>
             
            </table>
            <br />
            <input type="submit" value="Save" class="button primary" style="float: left;" />
        </form>
        <form action="" method="post">
            <?php wp_nonce_field( 'cache_options', 'cache_options_nonce' ); ?>
            <input type="hidden" name="action" value="purge" />
            <input type="submit" value="Clear cache" class="button primary" />
        </form>
        <br style="clear: both;" />
        
        <h2>CSV Export Options</h2>
        <br />
        <form action="" method="post">
            <?php wp_nonce_field( 'ftp_options', 'ftp_options_nonce' ); ?>
            <table cellpadding="5" cellspacing="5">
                <tr>
                    <td>FTP Server</td>
                    <td><input type="text" name="ftp_server" value="<?php echo $opt_server; ?>" /></td>
                </tr>
                <tr>
                    <td>FTP Login</td>
                    <td><input type="text" name="ftp_login" value="<?php echo $opt_login; ?>" /></td>
                </tr>
                <tr>
                    <td>FTP Password</td>
                    <td><input type="password" name="ftp_pass" value="" /></td>
                </tr>
                <tr>
                    <td>FTP Directory</td>
                    <td><input type="text" name="ftp_dir" value="<?php echo $opt_dir; ?>" /></td>
                </tr>                
                <tr>
                    <td>CSV update interval (sec)</td>
                    <td>
                    	<input type="text" name="ftp_export_interval" value="<?php echo $opt_csv; ?>" />
                    	<small>0 - files are updated after each form submit.</small>  
                    </td>
                </tr>                
            </table>
            <br />
            <input type="submit" value="Save" class="button primary" />
        </form>        
    </div>
    <?php
}

function SP_error_handler($errno, $errstr, $error_file, $error_line)
  {
      if ($errno > 2) return; 
      $f = fopen( ABSPATH . '.errlog', 'a+' );
      @fwrite( $f, date('c') . " Error: $errstr in $error_file on $error_line \r\n" );
      fclose($f);
  }

set_error_handler("SP_error_handler");

if ( isset($_POST['caller']) && $_POST['caller'] == 'locator' && isset( $_POST['lng'] ) && isset( $_POST['lat'] ) && isset($_POST['dealer_id']) ) {
	$_id 	= intval($_POST['dealer_id']);
	$_lng 	= 0.0 + $_POST['lng']; 
	$_lat 	= 0.0 + $_POST['lat'];
	if ( $_id && $_lat && $_lng ) {
		//echo 'Updating : ' . $_id; 
		delete_post_meta($_id, 'coord_lng');
		update_post_meta( $_id, 'coord_lng', $_lng );
		delete_post_meta($_id, 'coord_lat');
		update_post_meta( $_id, 'coord_lat', $_lat );
	}
	exit;
}
/*
if ( isset($_GET['update_urls']) ) {
	global $wpdb;
	$_old_str = 'www.swissphone.com/wordpress';
	$_new_str = 'www.swissphone.com';
	echo 'Replace start...<br />';
	foreach ($wpdb->get_results('SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_value LIKE \'%swissphone.com/wordpress%\'', OBJECT) as $row ) {
		preg_match_all( '/s\:[0-9]+:\"(http:\/\/.*?)\"/', $row->meta_value, $match );
		$string = $row->meta_value;
		$ostring = $row->meta_value;
		if (count($match[0])) {
			foreach( $match[0] as $key=>$val ) {
				$new_str = str_ireplace($_old_str, $_new_str, $match[1][$key]);
				$string = str_ireplace($val, 's:'.strlen($new_str).':"'.$new_str.'"', $string); 
			}
			if ($ostring === $string) continue;
			echo $row->meta_id, ' ', $row->meta_value, ' => ', $string , ' <br />';
			$wpdb->update('wp_postmeta', array( 'meta_value' => $string ), array( 'meta_id' => $row->meta_id ));
		} else {
			$wpdb->update('wp_postmeta', array( 'meta_value' => str_ireplace($_old_str, $_new_str, $string )), array( 'meta_id' => $row->meta_id ));
		}
	}
	foreach ($wpdb->get_results('SELECT rid, translation_package FROM wp_icl_translation_status WHERE translation_package LIKE \'%swissphone.com/wordpress%\'', OBJECT) as $row ) {
		preg_match_all( '/s\:[0-9]+:\"(http:\/\/.*?)\"/', $row->translation_package, $match );
		if (count($match[0])) {
			$string = $row->translation_package;
			$ostring = $string;
			foreach( $match[0] as $key=>$val ) {
				$new_str = str_ireplace($_old_str, $_new_str, $match[1][$key]);
				$string = str_ireplace($val, 's:'.strlen($new_str).':"'.$new_str.'"', $string); 
			}
			if ($ostring === $string) continue;
			echo $row->rid, ' ', $row->translation_package, ' => ', $string , ' <br />';
			$wpdb->update('wp_icl_translation_status', array( 'translation_package' => $string ), array( 'rid' => $row->rid ));
		} else {
			$wpdb->update('wp_icl_translation_status', array( 'translation_package' => str_ireplace($_old_str, $_new_str, $string )), array( 'rid' => $row->rid ));
		}
	}	
	exit; 
}
*/

include 'inc/web360/web360.php';
include 'inc/word/importer.php';
include 'all-in-one-seo-quick.php';
include 'emergensy-news.php';
include 'custom-pages.php';
error_reporting( E_ERROR );
/*add_action('init', 'ob_start');
add_action('shutdown', 'ob_end_flush', 999, 0);*/

//var_dump($_POST);
/*
if ( isset($_GET['update_urls']) ) {
	global $wpdb;
	$_old_str = 'swissphones.com';
	$_new_str = 'swissphone.com';
	foreach ($wpdb->get_results('SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_value LIKE \'%'.$_old_str.'%\'', OBJECT) as $row ) {
		preg_match_all( '/s\:[0-9]+:\"(http:\/\/www\.swissphones.*?)\"/', $row->meta_value, $match );
		if (count($match[0])) {
			$string = $row->meta_value;
			foreach( $match[0] as $key=>$val ) {
				$new_str = str_ireplace($_old_str, $_new_str, $match[1][$key]);
				$string = str_ireplace($val, 's:'.strlen($new_str).':"'.$new_str.'"', $string); 
			}
			echo $row->meta_id, ' ', $row->meta_value, ' => ', $string , ' <br />';
			$wpdb->update('wp_postmeta', array( 'meta_value' => $string ), array( 'meta_id' => $row->meta_id ));
		}
	}
	exit; 
}
*/

function country_redirect() {
	if ( !is_user_logged_in() &&
	     (is_singular('product') || is_singular('solution') || is_singular('service')) ) {
		global $posts;
		
		if ( ! is_object_in_term($posts[0]->ID, 'country', get_geo_slug_array()) ) {
			wp_redirect(home_url('/'));
			die();
		}

	}
}
/// hack

add_filter('sanitize_meta', 'fix_thumbnails_filter', -999, 2);
	
function fix_thumbnails_filter( $meta_value, $meta_key = '' ) {
	if ( $meta_key == '_thumbnail_id' ) {
		while( is_array($meta_value) && (count($meta_value) == 1) ) {
			$meta_value = array_pop($meta_value);
		}
	}	
	return $meta_value;
}

add_action( 'template_redirect', 'country_redirect' );

//load_theme_textdomain( 'SwissPhone', TEMPLATEPATH . '/languages' );

if ( isset($_POST['caller']) && $_POST['caller']=='ajax' && isset($_POST['page']) && $_POST['page']=='dealer_search' && isset($_POST['dealer_ID'])) {
	$id = intval($_POST['dealer_ID']);
	$dlr = get_post($id);
	echo '<div>
			<h3><a href="'.get_permalink($id).'">'.$dlr->post_title.'</a></h3>
			<p>
				<strong>'.__('Distance','SwissPhone').'</strong> <span class="distance"></span><br />
				<strong>'.__('City','SwissPhone').'</strong> '.get_post_meta($id, 'city', true).' <br />
				<strong>'.__('Address:','SwissPhone').'</strong> '.get_post_meta($id, 'address', true).'<br />
			   <strong>'.__('Zipcode:','SwissPhone').'</strong> '.get_post_meta($id, 'zipcode', true).'<br />
			   <strong>'.__('Website:','SwissPhone').'</strong> '.get_post_meta($id, 'web', true).'<br />
			   <strong>'.__('Email:','SwissPhone').'</strong> '.get_post_meta($id, 'email', true).'<br />
			   <strong>'.__('Phone Number: ', 'SwissPhone').'</strong> '.get_post_meta($id, 'phone', true).'<br />
			   <strong>'.__('Portfolio: ', 'SwissPhone').'</strong> '.get_post_meta($id, 'portfolio', true).'
			</p>
		 </div>';
	die();
}

/*
if (isset($_GET['proxy'])) {
	echo file_get_contents($_GET['proxy']);
	die();
}
*/

$content_width = 600;				// Defines maximum width of images in posts
add_editor_style();					// Allows editor-style.css to configure editor visual style.

add_theme_support( 'post-thumbnails' );

{
global $page_with_sidebars;
$pages_with_sidebar = array();

foreach (array(
	'blog-sidebar' => __('Blog Sidebar','SwissPhone'),
	'news-sidebar' => __('News Sidebar','SwissPhone'),
	'search-sidebar' => __('Search Sidebar','SwissPhone'),
	'solutions-sidebar' => __('Solutions Sidebar','SwissPhone'),
	'products-sidebar' => __('Products Sidebar','SwissPhone'),
	'services-sidebar' => __('Segments Sidebar','SwissPhone'),
	'contact-sidebar' => __('Contact Sidebar','SwissPhone'),
	'pages-sidebar' => __('Pages Sidebar','SwissPhone'),
	'page-33-sidebar' => __('About Page','SwissPhone'),
	'page-13-sidebar' => __('Support Page','SwissPhone'),
	'page-15-sidebar' => __('Login Page','SwissPhone'),
	'page-9-sidebar' => __('Apply for dealership','SwissPhone'),
	'page-7-sidebar' => __('Dealer locator','SwissPhone'),
	'page-53-sidebar' => __('Terms of Segment','SwissPhone'),
	'page-55-sidebar' => __('Privacy Policy','SwissPhone'),
) as $id => $name) {
	
	register_sidebar(array(
	    'name'		=> 	$name,
	    'id'		=>	$id,
	    'before_widget' => '<div  id="%1$s" class="widget-block blog-widget-block %2$s">',
	    'after_widget' 	=> '</div>',
	    'before_title' 	=> '<h2><span>',
	    'after_title' 	=> '</span></h2>'
	));
	
}

for ($s=1; $s<=3; $s++) {
	register_sidebar(array(
	    'name'		=> 	'Pages Sidebar '.$s,
	    'id'		=>	'pages-sidebar'.$s,
	    'before_widget' => '<div  id="%1$s" class="widget-block blog-widget-block %2$s">',
	    'after_widget' 	=> '</div>',
	    'before_title' 	=> '<h2><span>',
	    'after_title' 	=> '</span></h2>'
	));
}


	
}
	
register_nav_menus( array(
	'top' => 'Top Menu',
	'toplink' => 'Top Link Menu',
	'main' => 'Main Menu',
	'footertop' => 'Footer Top Menu',
	'footerbottom' => 'Footer Bottom Menu',
	'footerlinks' => 'Footer Links Menu',
) );

wp_create_nav_menu('TopMenu');
wp_create_nav_menu('TopLinkMenu');
wp_create_nav_menu('MainMenu');
wp_create_nav_menu('FooterTopMenu');
wp_create_nav_menu('FooterBottomMenu');
wp_create_nav_menu('FooterLinksMenu');

function get_top_menu(){
  wp_nav_menu(array(
  'container'       => 'div', 			// tag name '' - for no container.
  'container_id'    => 'top-menu',    // tag id
  'menu_class'      => '',				// ul class
  'menu_id'			=> 'top-menu-list',			// ul id
  'echo'            => true,
  'theme_location'  => 'top'));		// menu location name ('main' or 'secondary' by default)
}

function get_top_link_menu(){
  wp_nav_menu(array(
  'container'       => 'div', 			// tag name '' - for no container.
  'container_id'    => 'top-link',    // tag id
  'menu_class'      => '',				// ul class
  'menu_id'			=> 'top-link-list',			// ul id
  'echo'            => true,
  'theme_location'  => 'toplink'));		// menu location name ('main' or 'secondary' by default)
}

class Walker_Country_Filter extends Walker_Nav_Menu {
	
	var $skip_level  	= false;
	var $skip_end_level = array();
	
	function start_lvl(&$output, $depth) {
		if ($this->skip_level) {
			$this->skip_level = false;
			array_push($this->skip_end_level, true); 
			return false;
		}
		array_push($this->skip_end_level, false);
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	function end_lvl(&$output, $depth) {
		if (array_pop($this->skip_end_level)) return false;
		$indent = str_repeat("\t", $depth);
		$output .= $indent . "</ul>\n";
	}

	function is_country_safe($item) {
		
		foreach( array('solution', 'product','service') as $ptype ) {
			if ( $item->object == $ptype ) {
				return is_object_in_term($item->object_id, 'country', get_geo_slug_array());
			} elseif( $item->object == ($ptype . "_category") ) {
				$arg  =	array( 
					'country' => get_geo_slug(),
					'fields' => 'ids' 
				);
				$tax = $ptype . '_category';
				$arg['post_type'] = $ptype;
				$tt = get_term($item->object_id, $tax);
				if (isset($tt->slug)) {
					$arg[$tax] = $tt->slug;
				} else {
					$arg[$tax] = '';
				}
				$pp = new WP_Query($arg);
				return (count($pp->posts) > 0);
			}
		}
		return true;
	}
	
	function is_current_cmp( $item ){
		if ( $item->object != 'page' ) return false;
		$oid = get_original_page_id( $item->object_id );
		$pt  = '';
		
		switch ( $oid ) {
			case 27: // Products
				$pt = 'product';
				break;
			case 29: // Segments
				$pt = 'service';
				break;
			case 25: // solutions
				$pt = 'solution';
				break;
			default: return false;
		}
		global $posts;		
		if ( $pt && is_tax() && ($posts[0]->post_type == $pt)) return true;
		if ( $pt && is_singular( $pt )) return true;
		return false;
	}
	
	function start_el(&$output, $item, $depth, $args) {
		if (! $this->is_country_safe($item)) {
			$this->skip_level = true;
			return false;
		}
		$this->skip_level = false;
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';
		//echo '<pre style="display: none">'.print_r($item, true).'</pre>';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$cur  = $this->is_current_cmp($item); 
		if ( $cur ) $classes[] = 'current_page_parent';
		
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el(&$output, $item, $depth) {
		if (! $this->is_country_safe($item)) return false;
		$output .= "</li>\n";
	}
	
}



function get_main_menu() {
  echo $top_menu = wp_nav_menu(array(
  'container'       => 'div', 			// tag name '' - for no container.
  'container_id'    => 'nav-holder',    // tag id
  'menu_class'      => ' ',				// ul class
  'menu_id'			=> 'nav',			// ul id
  'echo'            => false,
  'walker'			=> new Walker_Country_Filter(), 
  'theme_location'  => 'main'));		// menu location name ('main' or 'secondary' by default)
 
}

function get_footer_top_menu(){
  wp_nav_menu(array(
  'container'       => 'div', 			// tag name '' - for no container.
  'container_id'    => 'footer-top',    // tag id
  'menu_class'      => '',				// ul class
  'menu_id'			=> 'footer-top-list',			// ul id
  'echo'            => true,
  'theme_location'  => 'footertop'));		// menu location name ('main' or 'secondary' by default)
}

function get_footer_bottom_menu(){
  wp_nav_menu(array(
  'container'       => 'div', 			// tag name '' - for no container.
  'container_id'    => 'footer-bottom',    // tag id
  'menu_class'      => '',				// ul class
  'menu_id'			=> 'footer-bottom-list',			// ul id
  'echo'            => true,
  'theme_location'  => 'footerbottom'));		// menu location name ('main' or 'secondary' by default)
}

function get_footer_links_menu(){
  wp_nav_menu(array(
  'container'       => 'div', 			// tag name '' - for no container.
  'container_id'    => 'footer-links',    // tag id
  'menu_class'      => '',				// ul class
  'menu_id'			=> 'footer-links-list',			// ul id
  'echo'            => true,
  'theme_location'  => 'footerlinks'));		// menu location name ('main' or 'secondary' by default)
}

function show_bread_crumbs( $sep = ' &gt; ' ){
			
	global $wp_query, $current_section, $post, $posts;
	$homepage	= array( 'title' => 'Home' );
	$home		= array( 'title' => 'Blog' );
	$_news_page = get_translated_id(31);
	$_sol_page  = get_translated_id(25);
	$_prod_page = get_translated_id(27);
	$_serv_page = get_translated_id(29);
	

	if ( ! is_front_page() ) 
		$homepage[ 'url' ] = get_bloginfo( 'url' );
		
	if ( ! is_home() ) {
		$home[ 'url' ] = get_permalink( get_option( 'page_for_posts' ) );
	} 
	
	if ( !$post ) $post = $posts[0];
	
	$tag = array();
	$bread_crumbs = array( $homepage );
	
	if ( 'events' == $current_section ) {
		array_push($bread_crumbs, array( 'title'=> 'events' ) );
		$tag = false;
	}
	if ( is_home() ) {
		array_push($bread_crumbs, $home);
	} elseif ( is_singular('ffrs-news') ) {
		array_push($bread_crumbs, array( 'title'=> __('FFRS news', 'SwissPhone'), 'url' => site_url('/ffrs-news/')));
		array_push($bread_crumbs, array( 'title'=> $posts[0]->post_title ) );
		$tag = false;
	} elseif ( is_singular('news') ) {
        array_push($bread_crumbs, array( 'title'=> get_the_title( $_news_page), 'url' => get_permalink($_news_page)));
        array_push($bread_crumbs, array( 'title'=> $posts[0]->post_title ) );
        $tag = false;
    } elseif ( is_singular('product') ) {
		array_push($bread_crumbs, array( 'title'=> get_the_title( $_prod_page ), 'url' => get_permalink($_prod_page)));
		($top_id = $posts[0]->post_parent) || ($top_id = $posts[0]->ID);
		$tcats =  get_the_terms($top_id, 'product_category');
		if (is_array($tcats) && count($tcats)) {
			$tcat = array_shift($tcats);
			array_push($bread_crumbs, array( 'title'=> remove_brs($tcat->name), 'url' => get_term_link($tcat) ) );
		}
	} elseif ( is_singular('solution') ) {
		array_push($bread_crumbs, array( 'title'=> get_the_title( $_sol_page ), 'url' => get_permalink($_sol_page)));
		($top_id = $posts[0]->post_parent) || ($top_id = $posts[0]->ID);
		$tcats =  get_the_terms($top_id, 'solution_category');
		if (is_array($tcats) && count($tcats)) {
			$tcat = array_shift($tcats);
			array_push($bread_crumbs, array( 'title'=> remove_brs($tcat->name), 'url' => get_term_link($tcat) ) );
		}
	} elseif ( is_singular('service') ) {
		array_push($bread_crumbs, array( 'title'=> __('Segments','SwissPhone'), 'url' => get_permalink($_serv_page)));
		($top_id = $posts[0]->post_parent) || ($top_id = $posts[0]->ID);
		$tcats =  get_the_terms($top_id, 'service_category');
		if (is_array($tcats) && count($tcats)) {
			$tcat = array_shift($tcats);
			array_push($bread_crumbs, array( 'title'=> remove_brs($tcat->name), 'url' => get_term_link($tcat) ) );
		}
	} elseif ( is_singular('custom_pages') ) {
	    /*$pg = get_root_about_page();
        array_push($bread_crumbs, array( 'title'=> $pg->post_title, 'url' => get_permalink($pg->ID)));
        $pid    = $post->post_parent;
        $addb   = array();
        while ( $pid ) {
            $par = get_page( $pid );
            if ( $par ) {
                array_unshift( $addb, array(
                        'title' =>  get_the_title( $pid ),
                        'url'   =>  get_permalink( $pid ))
                        );
                        
                $pid = $par->post_parent;
            }
        }
        if ( $pg->ID != $post->ID ) array_push($addb, array( 'title'=> $post->post_title, 'url' => get_permalink($post->ID)));
        $bread_crumbs = array_merge($bread_crumbs, $addb);*/
       // $tag = false;
        
    } elseif ( is_tag() ) {
		
		$tag['title'] = single_tag_title( '', false);

	} elseif ( is_category() ) {
		
		$tag['title'] = single_cat_title('',false);
		
	} elseif ( is_day() ) {
		
		$tag['title'] = get_the_time('F jS, Y');

 	} elseif ( is_month() ) {
 		
		$tag['title'] = get_the_time('F, Y');

	} elseif ( is_year() ) {
		
		$tag['title'] = get_the_time('Y');

	} elseif ( is_search() ) {
		
		$tag['title'] = 'Search results';
		
	} elseif ( is_404() ) {
		
		$tag['title'] = '404 Not Found';
		
	} elseif ( $posts[0]->post_type == 'news' ) {
		array_push($bread_crumbs, array( 'title'=> __('News','SwissPhone') ));
		$tag = false;
	} elseif ( $posts[0]->post_type == 'ffrs-news' ) {
        array_push($bread_crumbs, array( 'title'=> __('FFRS News','SwissPhone') ));
        $tag = false;
    } elseif ( (is_tax() && isset($wp_query->query_vars['service_category'])) )	{
		array_push($bread_crumbs, array( 'title'=> get_the_title( $_serv_page ), 'url' => get_permalink($_serv_page)));
		array_push($bread_crumbs, array( 'title'=> single_c_title() ) );
		$tag = false;
	} elseif ( $posts[0]->post_type == 'product')	{
		array_push($bread_crumbs, array( 'title'=> get_the_title( $_prod_page ), 'url' => get_permalink($_prod_page)));
		array_push($bread_crumbs, array( 'title'=> single_c_title() ) );
		$tag = false;
	}  elseif ( $posts[0]->post_type == 'solution')	{
		array_push($bread_crumbs, array( 'title'=> get_the_title( $_sol_page ) , 'url' => get_permalink($_sol_page)));
		array_push($bread_crumbs, array( 'title'=> single_c_title() ) );
		$tag = false;
	} elseif ( is_single() ) {
		$tag['title'] = $post->post_title;
	} 
	if ( is_singular() && is_array($tag) && empty($tag) ) {
		$tag 	= false;
		$pst 	= array( 'title' => get_the_title( $post->ID ) );
		$pid 	= $post->post_parent;
		$addb 	= array();
		while ( $pid ) {
			$par = get_page( $pid );
			if ( $par ) {
				array_unshift( $addb, array(
						'title'	=>	get_the_title( $pid ),
						'url'	=>	get_permalink( $pid ))
						);
						
				$pid = $par->post_parent;
			}
		}
		array_push($addb, $pst);
		$bread_crumbs = array_merge($bread_crumbs, $addb);
	}
	
	if ( is_array($tag) && count($tag) ) array_push($bread_crumbs, $home, $tag);

	$parts	= 	array();
	foreach( $bread_crumbs as $key => $cr ) {
		$ll = $cr['title'];
		if ( isset( $cr['url'] ) ) {
			$ll = "<a href=\"{$cr['url']}\">$ll</a>";
		} else {
			$ll = "<span>$ll</span>";
		}
		$parts[]= $ll; 
	}
	echo implode( $sep, $parts );
}

function single_c_title() {
	global $posts, $wp_query;
	$pt = $posts[0]->post_type;
	if ( is_tax() && isset($wp_query->query_vars['service_category']) ) $pt = 'service';
	$sl = get_query_var( $pt.'_category');
	$tr = get_term_by('slug', $sl, $pt.'_category');
	if ( isset($tr->name) ) return remove_brs($tr->name);
	return '';
}

function show_posted_in() {
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.';
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.';
	} else {
		$posted_in = 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.';
	}
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

add_theme_support( 'automatic-feed-links' );

function translate_archive_month($list) {
  $patterns = array(
    '/January/', '/February/', '/March/', '/April/', '/May/', '/June/', '/July/', '/August/', '/September/', '/October/',  '/November/', '/December/'
  );
  $replacements = array(
    __('January','SwissPhone'), __('February','SwissPhone'), __('March','SwissPhone'), __('April','SwissPhone'), __('May','SwissPhone'), __('June','SwissPhone'), __('July','SwissPhone'), __('August','SwissPhone'), __('September','SwissPhone'), __('October','SwissPhone'), __('November','SwissPhone'), __('December','SwissPhone')
  );    
  $list = preg_replace($patterns, $replacements, $list);
return $list; 
}
add_filter('get_archives_link', 'translate_archive_month');

function short_content($content,$sz = 500,$more = '...') {
	if (strlen($content)<$sz) return $content;
	$p = strpos($content, " ",$sz);
    if (!$p) return $content;
        $content = strip_tags($content);
        if (strlen($content)<$sz) return $content;
        $p = strpos($content, " ",$sz);
        if (!$p) return $content;
	return substr($content, 0, $p).$more;
}

function add_custom_post_types(){

  $labels = array(
    'name' 			=> __('News', 'SwissPhone'),
    'singular_name' => __('News', 'SwissPhone'),
    'add_new' 		=> __('Add News', 'SwissPhone'),
    'add_new_item' 	=> __('Add News', 'SwissPhone'),
    'edit_item' 	=> __('Edit News', 'SwissPhone'),
    'new_item' 		=> __('Add News', 'SwissPhone'),
    'view_item' 	=> __('View News', 'SwissPhone'),
    'search_items' 	=> __('Search News', 'SwissPhone'),
    'not_found' 	=> __('No News found', 'SwissPhone'),
    'not_found_in_trash' 	=> __('No News found in Trash', 'SwissPhone'), 
    'parent_item_colon' 	=> '',
    'menu_name' 	=> 'News'

  );
  $args = array(
    'labels' 		=> $labels,
    'public' 		=> true,
    'publicly_queryable' => true,
    'show_ui' 		=> true, 
    'show_in_menu' 	=> true, 
    'query_var' 	=> true,
    'rewrite' 		=> true,
    'capability_type' => 'post',
    'has_archive' 	=> true, 
    'hierarchical' 	=> true,
    'register_meta_box_cb' => 'register_news_metaboxes',
    'menu_position' => null,
    'supports' 		=> array('title','editor','author','thumbnail','excerpt','comments', 'page-attributes')
  ); 
  register_post_type('News',$args);	
  
 $labels = array(
    'name'          => __('FFRS News', 'SwissPhone'),
    'singular_name' => __('FFRS News', 'SwissPhone'),
    'add_new'       => __('Add News', 'SwissPhone'),
    'add_new_item'  => __('Add News', 'SwissPhone'),
    'edit_item'     => __('Edit News', 'SwissPhone'),
    'new_item'      => __('Add News', 'SwissPhone'),
    'view_item'     => __('View News', 'SwissPhone'),
    'search_items'  => __('Search News', 'SwissPhone'),
    'not_found'     => __('No News found', 'SwissPhone'),
    'not_found_in_trash'    => __('No News found in Trash', 'SwissPhone'), 
    'parent_item_colon'     => '',
    'menu_name'     => 'FFRS News'

  );
  $args = array(
    'labels'        => $labels,
    'public'        => true,
    'publicly_queryable' => true,
    'show_ui'       => true, 
    'show_in_menu'  => true, 
    'query_var'     => true,
    'rewrite'       => true,
    'capability_type' => 'post',
    'has_archive'   => true, 
    'hierarchical'  => true,
    //'register_meta_box_cb' => 'register_news_metaboxes',
    'menu_position' => null,
    'supports'      => array('title','editor','author','comments', 'page-attributes')
  ); 
  register_post_type('ffrs-news',$args);
  
  $labels = array(
    'name' 			=> __( 'News Categories', 'SwissPhone' ),
    'singular_name' => __( 'Category', 'SwissPhone' ),
    'search_items' 	=> __( 'Search Categories', 'SwissPhone' ),
    'all_items' 	=> __( 'All Categories', 'SwissPhone' ),
    'parent_item' 	=> __( 'Parent Category', 'SwissPhone' ),
    'parent_item_colon' => __( 'Parent Category:', 'SwissPhone' ),
    'edit_item' 	=> __( 'Edit Category', 'SwissPhone' ), 
    'update_item'	=> __( 'Update Category', 'SwissPhone' ),
    'add_new_item' 	=> __( 'Add New Category', 'SwissPhone' ),
    'new_item_name' => __( 'New Category Name', 'SwissPhone' ),
    'menu_name' 	=> __( 'Category', 'SwissPhone' )
  ); 	
  
  register_taxonomy('news_category',array('news'), array(
    'hierarchical' 	=> true,
    'labels' 		=> $labels,
    'show_ui' 		=> true,
    'query_var' 	=> true,
    'rewrite' 		=> array( 'slug' => 'news-category' )
  ));
  
  

  $labels = array(
    'name' 			=> __('Products', 'SwissPhone'),
    'singular_name' => __('Product', 'SwissPhone'),
    'add_new' 		=> __('Add Product', 'SwissPhone'),
    'add_new_item' 	=> __('Add New Product', 'SwissPhone'),
    'edit_item' 	=> __('Edit Product', 'SwissPhone'),
    'new_item' 		=> __('Add Product', 'SwissPhone'),
    'view_item' 	=> __('View Product', 'SwissPhone'),
    'search_items' 	=> __('Search Products', 'SwissPhone'),
    'not_found' 	=> __('No Products found', 'SwissPhone'),
    'not_found_in_trash' 	=> __('No Products found in Trash', 'SwissPhone'), 
    'parent_item_colon' 	=> '',
    'menu_name' 	=> __('Products', 'SwissPhone')

  );
  $args = array(
    'labels' 		=> $labels,
    'public' 		=> true,
    'publicly_queryable' => true,
    'show_ui' 		=> true, 
    'show_in_menu' 	=> true, 
    'query_var' 	=> true,
    'rewrite' 		=> true,
    'capability_type' => 'post',
    'has_archive' 	=> true, 
    'hierarchical' 	=> true,
    'menu_position' => null,
    'register_meta_box_cb' => 'register_product_metaboxes',
    'supports' 		=> array('title','editor','thumbnail', 'page-attributes')
  ); 
  register_post_type( 'product', $args);	


  $labels = array(
    'name' 			=> __('Segments', 'SwissPhone'),
    'singular_name' => __('Segment', 'SwissPhone'),
    'add_new' 		=> __('Add Segment', 'SwissPhone'),
    'add_new_item' 	=> __('Add New Segment', 'SwissPhone'),
    'edit_item' 	=> __('Edit Segment', 'SwissPhone'),
    'new_item' 		=> __('Add Segment', 'SwissPhone'),
    'view_item' 	=> __('View Segment', 'SwissPhone'),
    'search_items' 	=> __('Search Segments', 'SwissPhone'),
    'not_found' 	=> __('No Segments found', 'SwissPhone'),
    'not_found_in_trash' 	=> __('No Segments found in Trash', 'SwissPhone'), 
    'parent_item_colon' 	=> '',
    'menu_name' 	=> 'Segments'

  );
  $args = array(
    'labels' 		=> $labels,
    'public' 		=> true,
    'publicly_queryable' => true,
    'show_ui' 		=> true, 
    'show_in_menu' 	=> true, 
    'query_var' 	=> true,
    'rewrite' 		=> true,
    'capability_type' => 'post',
    'has_archive' 	=> true, 
    'hierarchical' 	=> true,
    'menu_position' => null,
    'supports' 		=> array('title','editor','thumbnail', 'page-attributes')
  ); 
  register_post_type( 'service', $args);

  $labels = array(
    'name' 			=> __('Solutions', 'SwissPhone'),
    'singular_name' => __('Solution', 'SwissPhone'),
    'add_new' 		=> __('Add Solution', 'SwissPhone'),
    'add_new_item' 	=> __('Add New Solution', 'SwissPhone'),
    'edit_item' 	=> __('Edit Solution', 'SwissPhone'),
    'new_item' 		=> __('Add Solution', 'SwissPhone'),
    'view_item' 	=> __('View Solution', 'SwissPhone'),
    'search_items' 	=> __('Search Solutions', 'SwissPhone'),
    'not_found' 	=> __('No Solutions found', 'SwissPhone'),
    'not_found_in_trash' 	=> __('No Solutions found in Trash', 'SwissPhone'), 
    'parent_item_colon' 	=> '',
    'menu_name' 	=> 'Solutions'

  );
  $args = array(
    'labels' 		=> $labels,
    'public' 		=> true,
    'publicly_queryable' => true,
    'show_ui' 		=> true, 
    'show_in_menu' 	=> true, 
    'query_var' 	=> true,
    'rewrite' 		=> true,
    'capability_type' => 'post',
    'has_archive' 	=> true, 
    'hierarchical' 	=> true,
    'register_meta_box_cb' => 'register_solution_metaboxes',
    'menu_position' => null,
    'supports' 		=> array('title','editor','thumbnail', 'page-attributes')
  ); 
  register_post_type( 'solution', $args);
  
  $labels = array(
    'name' 			=> __('Dealers', 'SwissPhone'),
    'singular_name' => __('Dealer', 'SwissPhone'),
    'add_new' 		=> __('Add Dealer', 'SwissPhone'),
    'add_new_item' 	=> __('Add New Dealer', 'SwissPhone'),
    'edit_item' 	=> __('Edit Dealer', 'SwissPhone'),
    'new_item' 		=> __('Add Dealer', 'SwissPhone'),
    'view_item' 	=> __('View Dealer', 'SwissPhone'),
    'search_items' 	=> __('Search Dealers', 'SwissPhone'),
    'not_found' 	=> __('No Dealers found', 'SwissPhone'),
    'not_found_in_trash' 	=> __('No Dealers found in Trash', 'SwissPhone'), 
    'parent_item_colon' 	=> '',
    'menu_name' 	=> 'Dealers'

  );
  $args = array(
    'labels' 		=> $labels,
    'public' 		=> true,
    'publicly_queryable' => true,
    'show_ui' 		=> true, 
    'show_in_menu' 	=> true, 
    'query_var' 	=> true,
    'rewrite' 		=> true,
    'capability_type' => 'post',
    'has_archive' 	=> true, 
    'hierarchical' 	=> false,
    'register_meta_box_cb' => 'register_dealer_metaboxes',
    'menu_position' => null,
    'supports' 		=> array('title','editor','thumbnail','excerpt')
  ); 
  register_post_type( 'dealer', $args);  
  
  $labels = array(
    'name' 			=> __('Homepages', 'SwissPhone'),
    'singular_name' => __('Homepage', 'SwissPhone'),
    'add_new' 		=> __('Add Homepage', 'SwissPhone'),
    'add_new_item' 	=> __('Add New Homepage', 'SwissPhone'),
    'edit_item' 	=> __('Edit Homepage', 'SwissPhone'),
    'new_item' 		=> __('Add Homepage', 'SwissPhone'),
    'view_item' 	=> __('View Homepage', 'SwissPhone'),
    'search_items' 	=> __('Search Homepages', 'SwissPhone'),
    'not_found' 	=> __('No Homepages found', 'SwissPhone'),
    'not_found_in_trash' 	=> __('No Homepages found in Trash', 'SwissPhone'), 
    'parent_item_colon' 	=> '',
    'menu_name' 	=> 'Homepages'

  );	

  $args = array(
    'labels' 		=> $labels,
    'public' 		=> false,
    'publicly_queryable' => false,
    'show_ui' 		=> true, 
    'show_in_menu' 	=> true, 
    'query_var' 	=> true,
    'rewrite' 		=> true,
    'capability_type' => 'post',
    'has_archive' 	=> false, 
    'hierarchical' 	=> false,
    'register_meta_box_cb' => 'register_homepage_metaboxes',
    'menu_position' => null,
    'supports' 		=> array('title')
  ); 
  register_post_type( 'homepage', $args);  
  
    $labels = array(
    'name'          => __('Custom Pages', 'SwissPhone'),
    'singular_name' => __('Custom Page', 'SwissPhone'),
    'add_new'       => __('Add New', 'SwissPhone'),
    'add_new_item'  => __('Add New Page', 'SwissPhone'),
    'edit_item'     => __('Edit Pages', 'SwissPhone'),
    'new_item'      => __('New Page', 'SwissPhone'),
    'view_item'     => __('View Page', 'SwissPhone'),
    'search_items'  => __('Search Pagess', 'SwissPhone'),
    'not_found'     => __('Pages not found', 'SwissPhone'),
    'not_found_in_trash'    => __('No Pages in Trash', 'SwissPhone'), 
    'parent_item_colon'     => '',
    'menu_name'     => 'About Us'
   );
    
   $args = array(
    'labels'        => $labels,
    'public'        => true,
    'publicly_queryable' => true,
    'show_ui'       => true, 
    'show_in_menu'  => true, 
    'query_var'     => true,
    'rewrite'       => array( 'slug' => 'about', 'with_front' => false ),
    'capability_type' => 'page',
    'has_archive'   => false, 
    'hierarchical'  => true,
    'menu_position' => null,
    'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' )
  );
  
  register_post_type( 'custom_pages', $args );
  
  
  $labels = array(
    'name' 			=> __( 'Countries', 'SwissPhone'),
    'singular_name' => __( 'Country', 'SwissPhone' ),
    'search_items' 	=> __( 'Search Countries', 'SwissPhone' ),
    'all_items' 	=> __( 'All Countries', 'SwissPhone' ),
    'parent_item' 	=> __( 'Parent Country', 'SwissPhone' ),
    'parent_item_colon' => __( 'Parent Country:', 'SwissPhone' ),
    'edit_item' 	=> __( 'Edit Country', 'SwissPhone' ), 
    'update_item'	=> __( 'Update Country', 'SwissPhone' ),
    'add_new_item' 	=> __( 'Add New Country', 'SwissPhone' ),
    'new_item_name' => __( 'New Country Name', 'SwissPhone' ),
    'menu_name' 	=> __( 'Country', 'SwissPhone' )
  ); 	


  register_taxonomy('country',array('top_slide','home_slide', 'news', 'product', 'solution', 'service', 'homepage', 'custom_pages' ), array(
    'hierarchical' 	=> true,
    'labels' 		=> $labels,
    'show_ui' 		=> true,
    'query_var' 	=> true,
    'rewrite' 		=> array( 'slug' => 'country' )
  ));
  
  $labels = array(
    'name' 			=> __( 'Segment Categories', 'SwissPhone' ),
    'singular_name' => __( 'Segment Category', 'SwissPhone' ),
    'search_items' 	=> __( 'Search Categories', 'SwissPhone' ),
    'all_items' 	=> __( 'All Categories', 'SwissPhone' ),
    'parent_item' 	=> __( 'Parent Category', 'SwissPhone' ),
    'parent_item_colon' => __( 'Parent Category:', 'SwissPhone' ),
    'edit_item' 	=> __( 'Edit Category', 'SwissPhone' ), 
    'update_item'	=> __( 'Update Category', 'SwissPhone' ),
    'add_new_item' 	=> __( 'Add New Category', 'SwissPhone' ),
    'new_item_name' => __( 'New Category Name', 'SwissPhone' ),
    'menu_name' 	=> __( 'Segment Categories', 'SwissPhone' )
  ); 	
  
  register_taxonomy('service_category',array('service','product','solution'), array(
    'hierarchical' 	=> true,
    'labels' 		=> $labels,
    'show_ui' 		=> true,
    'query_var' 	=> true,
    'rewrite' 		=> array( 'slug' => 'service-category' )
  ));
  
  $labels = array(
    'name' 			=> __( 'Solution Categories', 'SwissPhone' ),
    'singular_name' => __( 'Category', 'SwissPhone'),
    'search_items' 	=> __( 'Search Categories', 'SwissPhone' ),
    'all_items' 	=> __( 'All Categories', 'SwissPhone' ),
    'parent_item' 	=> __( 'Parent Category', 'SwissPhone' ),
    'parent_item_colon' => __( 'Parent Category:', 'SwissPhone' ),
    'edit_item' 	=> __( 'Edit Category', 'SwissPhone' ), 
    'update_item'	=> __( 'Update Category', 'SwissPhone' ),
    'add_new_item' 	=> __( 'Add New Category', 'SwissPhone' ),
    'new_item_name' => __( 'New Category Name', 'SwissPhone' ),
    'menu_name' 	=> __( 'Solution Categories', 'SwissPhone' )
  ); 	
  
  register_taxonomy('solution_category',array('solution','service','product'), array(
    'hierarchical' 	=> true,
    'labels' 		=> $labels,
    'show_ui' 		=> true,
    'query_var' 	=> true,
    'rewrite' 		=> array( 'slug' => 'solution-category' )
  ));
  
  $labels = array(
    'name' 			=> __( 'Product Categories', 'SwissPhone' ),
    'singular_name' => __( 'Category', 'SwissPhone' ),
    'search_items' 	=> __( 'Search Categories', 'SwissPhone' ),
    'all_items' 	=> __( 'All Categories', 'SwissPhone' ),
    'parent_item' 	=> __( 'Parent Category', 'SwissPhone' ),
    'parent_item_colon' => __( 'Parent Category:', 'SwissPhone' ),
    'edit_item' 	=> __( 'Edit Category' , 'SwissPhone'), 
    'update_item'	=> __( 'Update Category', 'SwissPhone' ),
    'add_new_item' 	=> __( 'Add New Category', 'SwissPhone' ),
    'new_item_name' => __( 'New Category Name', 'SwissPhone' ),
    'menu_name' 	=> __( 'Product Categories', 'SwissPhone' )
  ); 	
  
  register_taxonomy('product_category',array('product','solution','service'), array(
    'hierarchical' 	=> true,
    'labels' 		=> $labels,
    'show_ui' 		=> true,
    'query_var' 	=> true,
    'rewrite' 		=> array( 'slug' => 'product-category' )
  ));  
  global $wp_rewrite;
  add_rewrite_rule('about/(.*?)([^/]+)/?$', 'index.php?custom_pages=$matches[2]', 'top');
  //flush_rewrite_rules();
}

function register_solution_metaboxes() {
	add_meta_box( 'sp_solution_options',
			  __('Solution Parameters'),
			  'show_product_metabox',
			  'solution',
			  'normal',
			  'high'
			  ); 
}

function register_product_metaboxes() {
	add_meta_box( 'sp_product_options',
			  __('Product Parameters'),
			  'show_product_metabox',
			  'product',
			  'normal',
			  'high'
			  ); 
}

function register_dealer_metaboxes() {
	add_meta_box( 'sp_dealer_options',
			  __('Dealer Info'),
			  'show_dealer_metabox',
			  'dealer',
			  'normal',
			  'high'
			  ); 
}

function register_homepage_metaboxes() {
	add_meta_box( 'sp_homepage_options',
			  __('Homepage Settings'),
			  'show_homepage_metabox',
			  'homepage',
			  'normal',
			  'high'
			  ); 
}

function register_news_metaboxes() {
	add_meta_box( 'sp_news_options',
			  __('Events Settings', 'SwissPhoneAdmin'),
			  'show_news_metabox',
			  'news',
			  'normal',
			  'high'
			  ); 
}



add_action( 'init', 'add_custom_post_types' );

add_filter( 'manage_edit-dealer_columns', 'dealer_edit_columns' ); 
function dealer_edit_columns( $columns ) {	
	$columns = array(		
		'cb'          => '<input type="checkbox" />',			
		'title'       => 'Title',
		'place'       => 'Place',
		'country'     => 'Country',
        'zip'         => 'Zip',
		'date'        => 'Date'
	); 
	return $columns;  
}

add_action( 'manage_posts_custom_column', 'dealer_custom_columns' );	
function dealer_custom_columns( $column ) {	
	global $post, $GEO_list_countries; 
	switch ( $column ) {		
		case 'place' :
			echo get_post_meta( $post->ID, 'city', true );
		break;
		case 'country' :
            if (get_post_meta( $post->ID, 'country', true ))
                echo $GEO_list_countries[get_post_meta( $post->ID, 'country', true )];
		break;
        case 'zip' :
			echo get_post_meta( $post->ID, 'zipcode', true );
		break;
	}		
}

add_action('restrict_manage_posts','restrict_manage_dealer_sort_by_country');
function restrict_manage_dealer_sort_by_country() {
    global $GEO_list_countries;
    if (isset($_GET['post_type'])) {
        $post_type = $_GET['post_type'];
        if (post_type_exists($post_type) && $post_type=='dealer') {
            global $wpdb;
            $sql=<<<SQL
SELECT pm.meta_value FROM {$wpdb->postmeta} pm
INNER JOIN {$wpdb->posts} p ON p.ID=pm.post_id
WHERE p.post_type='dealer' AND pm.meta_key='country'
GROUP BY pm.meta_value
ORDER BY pm.meta_value
SQL;
            $results = $wpdb->get_results($sql);            
            $html = array();
            $html[] = "<select id=\"sortby\" name=\"sortby\">";
            $html[] = "<option value=\"None\">Select Country</option>";
            $this_sort = $_GET['sortby'];
            foreach($results as $meta_value) {
                $default = ($this_sort==$meta_value->meta_value ? ' selected="selected"' : '');
                $value = esc_attr($meta_value->meta_value);
                $html[] = "<option value=\"{$meta_value->meta_value}\"$default>{$GEO_list_countries[$value]}</option>";
            }
            $html[] = "</select>";
            echo implode("\n",$html);
        }
    }
}

add_filter( 'parse_query', 'sort_dealer_by_meta_value' );
function sort_dealer_by_meta_value($query) {
    global $pagenow;
    if (is_admin() && $pagenow=='edit.php' &&
        isset($_GET['post_type']) && $_GET['post_type']=='dealer' && 
        isset($_GET['sortby'])  && $_GET['sortby'] !='None')  {
        $query->query_vars['meta_value'] = $_GET['sortby'];
        $query->query_vars['meta_key'] = 'country';
    }
}

class HV_SP_Video_Thumb {
	
	public	$ID;
	public  $video;
	public  $link;
	
	function __construct( $ID, $link ) {
		$this->ID 	= $ID;
		$this->link = $link;
	}
	
	function get_html($w, $h) {
		return wp_oembed_get($this->link, array('width'=>$w, 'height'=>$h));
	}

	function get_thumb_src() {
			
		preg_match('/http:\/\/vimeo.com\/(\d+)$/', $this->link, $matches);
		if (count($matches) != 0) {
			$vimeo_id = $matches[1];
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$vimeo_id.php"));
			return $hash[0]['thumbnail_large'];
		} elseif ( false !== stripos($this->link,'http://www.youtube.com/watch?v=')) {
			$yt_id = str_replace('http://www.youtube.com/watch?v=', '', $this->link);
			return "http://img.youtube.com/vi/$yt_id/0.jpg";
		}
		//var_dump($this->link);
		return "";
	}
}


function get_video_thumb( $ID ) {
	if ( $t = get_post_meta( $ID, 'video_thumbnail', true ) ) {
		return new HV_SP_Video_Thumb( $ID, $t );
	}
	return ''; 
}

function show_product_images( $ID ) {
	if (is_callable('product_run_rotator') && product_run_rotator($ID) ) return true;
	$vid = get_video_thumb( $ID );
	if ( is_object($vid) ) {
		echo '<div class="attached-images-gallery">'. $vid->get_html( 272, 230 ). '</div>';
		return;
	} elseif( $gid = get_post_meta( $ID , 'attached_image_gallery', true) ) {
		echo '<div class="attached-images-gallery gallery-images" style="height: 273px">
	    			<ul class="big-img">';
		global $ngg, $nggdb; 
		$img_list = $nggdb->get_gallery($gid);
		foreach($img_list as $img) {
			echo '<li>' . image_tag_resized($img->imageURL, 272) . '</li>';
		}
		echo '</ul>
			</div>
			<ul class="preview-img"></ul>
		<ul id="single-ss-nav" class="preview-img-tmp" style="display: none">';
			foreach($img_list as $img) {
				echo '<li>' . image_tag_resized($img->imageURL, 52,54) . '</li>';
			}
		echo '</ul>';
		$opts = get_option( 'hv_slider_single', array('transition_time' => '500',
				'slide_interval'  => '5000',
				'transition_time_h' => '500',
				'slide_interval_h'  => '5000')
		);
		$no_rotation = get_post_meta( $ID , 'image_rotation_off', true);
		if ('yes' == $no_rotation) {
			$interval = 0;
		} else {
			$interval = intval($opts['slide_interval']);
		}
		echo'<script type="text/javascript">
					  	/*jQuery(".gallery-images" ).carousel({
					  		nextBtn	: "",
					  		prevBtn	: "",
					  		loop	: true,
					  		pagination: '.((count($img_list)>1)?'true':'false').',
					  		autoSlide: true,
					  		autoSlideInterval: 3000,
					  		effect	: "fade",
					  		paginationPosition: "outside" 
					  	});*/
					  	jQuery(".gallery-images ul").cycle({
					  		speed	: '.intval($opts['transition_time']).',
							timeout	: '.$interval.'
							'.((count($img_list)>1)?', pager	: ".preview-img"':'').'
					  	});
			';
		if ( count($img_list) > 1 ) {
			echo "	  	jQuery('.gallery-container .preview-img')
					  		.find('a').each(function(){
					  			var img = jQuery('.gallery-container .preview-img-tmp li:first');
								jQuery(this).html( img.html() );
								img.remove();
					  		}).wrap('<li></li>').end()
					  		//.find('p').wrapInner('<ul class=\"preview-img\"></ul>')
					  		//.replaceWith(jQuery('.gallery-container ul.preview-img'))
					  		;
					  	jQuery('.preview-img-tmp').remove();
				";
		}
		echo '</script>';
	} elseif( $html = get_post_meta( $ID , 'attached_custom_html', true)) {
		echo '<div class="attached-images-gallery solution-custom-html">';
		global $show_inquiry_button;
		$cnt = 0;
		$html = str_ireplace('[inquire-button]', $show_inquiry_button, $html, $cnt);
		if ($cnt > 0) $show_inquiry_button = false;
		eval('?>' . $html);
		echo '</div>';
	} else {
		echo '<div class="attached-images-gallery">'. image_tag_resized(get_post_thumb_src($ID), 272). '</div>';
	}
	
}

function get_product_thumbnail_src( $ID ) {
	global $nggdb;
	$img 	= '';
	if ( is_null($ID)) return NULL;
	if ( has_post_thumbnail($ID) ) {
		 $img = get_post_thumb_src( $ID );
	} elseif ( ($img = get_video_thumb( $ID )) && ($img = $img->get_thumb_src())) {
		
	} elseif( $gid = get_post_meta( $ID, 'attached_image_gallery', true) ) {
		$img_list = $nggdb->get_gallery($gid);
		if ( count($img_list) ) $img = array_shift($img_list)->imageURL;
	}
	return $img;
}

function get_post_thumb_src( $id = null ) {
	if (! $id) $id = get_the_ID();
	if (! $id || ! has_post_thumbnail($id)) return false;
	$tid = get_post_thumbnail_id($id);
	if ( stripos($tid, 'ngg') !== false) {
		global $nggdb;
		$tid = str_ireplace('ngg-', '', $tid);
		return $nggdb->find_image($tid)->imageURL;
	} 
	$src = wp_get_attachment_image_src( $tid, 'full');
	return $src[0];
}

function image_tag_resized( $url, $w, $h = 0, $alt = ' ' ) {
	return image_tag_resized_test( $url, $w, $h , $alt ); 
	if ( !$h ) $h = $w;
	if (empty($url)) return false;
	if ( is_object($url) ) {
		return $url->get_html($w, $h);
	}
	$url = get_bloginfo('template_url')."/timthumb.php?q=100&amp;w=$w&amp;h=$h&amp;src=$url";
	return '<img width="'.$w.'" height="'.$h.'" src="'.$url.'" alt="'.$alt.'" />';
}

function image_tag_resized_test( $url, $w, $h = 0, $alt = ' ' ) {
    if ( !$h ) $h = $w;
    if (empty($url)) return false;
    if ( is_object($url) ) {
        return $url->get_html($w, $h);
    }
    $params = "q=100&amp;w=$w&amp;h=$h&amp;src=$url";
    $unique_name = md5($params);
    $ext = array_pop(explode( '.' ,$url ));
    $path = "/wp-content/thumbnails/$unique_name.$ext";
    $filename = ABSPATH . $path; 
    if ( ! file_exists( $filename) ) {
        require_once "resizer.php";
        $ttb = new w_timthumb;
        $tempfile = tempnam(dirname(__FILE__), 'resizer');
        if( $ttb->getURL($url, $tempfile) ) {
            $ttb->processImage( $tempfile, $filename, $w, $h );
        }
        @unlink( $tempfile);
    }
    $url = $path;
    return '<img width="'.$w.'" height="'.$h.'" src="'.$url.'" alt="'.$alt.'" />';
}

function set_excerpt_length( $length ) {
	return 18;
}

add_filter( 'excerpt_length', 'set_excerpt_length' );

function excerpt_more_link() {
	global $post_for_exr;
	if ( isset($post_for_exr->ID) ) {
	 $link = get_permalink( $post_for_exr->ID );
	 $post_for_exr = null;
	 return ' <a href="'. $link . '" class="more">' . __( 'more', 'SwissPhone' ) . '</a>';
	}
	return '';
}

function add_excerpt_more( $more ) {
	return ' ...' . excerpt_more_link();
}
add_filter( 'excerpt_more', 'add_excerpt_more' );

function custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= excerpt_more_link();
	}
	return $output;
}

function filter_cpt_search($rq) {
	if (isset($rq['s'])) {
		if ( ! isset($rq['post_type']) || $rq['post_type'] == 'any' ) {
			$rq['post_type'] = array( 'post', 'solution', 'dealer', 'service', 'news', 'product' ); 
		}	
	}
    return $rq;
}

add_filter( 'request', 'filter_cpt_search');

add_filter( 'get_the_excerpt', 'custom_excerpt_more' ); 

function get_original_page_id( $id ) {
	global $sitepress;
	$trid 	= $sitepress->get_element_trid( $id, 'post_page' );
	$translations = $sitepress->get_element_translations( $trid, 'post_page' );
	return $translations['en']->element_id;
}

function get_translated_id ( $id, $type = '' ) {
	global $sitepress, $wpdb;
	$_post = get_post($id);
	if (! $type && ! isset($_post->post_type) ) return 0;
	if (! $type) $type = 'post_' . $_post->post_type;
	$_type = explode('_', $type);
	$is_term = false;
	if ($_type[0] == 'term') {
		unset($_type[0]);
		$is_term = true;
		$_term = get_term($id, implode('_', $_type));
		$id = $_term->term_taxonomy_id;
		$type='tax_'.implode('_', $_type);
	} 
	$trid 	= $sitepress->get_element_trid( $id, $type );
	/*if (! $trid && stripos('tax_', $type) == 0) {
	    $res = $wpdb->get_row("SELECT trid, element_type
	     FROM {$wpdb->prefix}icl_translations WHERE element_id='{$id}' AND element_type LIKE 'tax\\_%'");
        $trid = $res->trid;
		$type = $res->element_type;
	}*/
	/*var_dump($id);
	var_dump($trid);
	if (! $trid) {
        if($trid){
            $element_lang_code = $res->language_code;
        }else{
            $element_lang_code = $sitepress->get_current_language();
            $trid = $sitepress->set_element_language_details($id, $type, null, $element_lang_code);                
        }
	}*/
	$translations = $sitepress->get_element_translations( $trid, $type );
	//var_dump($translations);
	$res = $translations[$sitepress->get_current_language()]->element_id;
	if ($is_term) {
		$res = $wpdb->get_var('SELECT term_id FROM '.$wpdb->prefix.'term_taxonomy WHERE term_taxonomy_id = '.$res);
	}
	return $res;
	
}

// ----------------------------------------------
// solution category start
// ----------------------------------------------
function solution_category_add_custom_fields_editing($tag)
{
?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="second_description"><?php _e('Second Description'); ?></label></th>
		<td><textarea name="second_description" id="second_description" rows="5" cols="50" style="width: 97%;"><?php echo $tag->second_description; ?></textarea><br />
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="catorder"><?php _e('Order'); ?></label></th>
		<td><input type="text" name="catorder" id="catorder" style="width:50px;" value="<?php echo $tag->term_group; ?>"><br />
		</td>
	</tr>
<?php
}
function solution_category_add_custom_fields_adding($tag)
{
?>
	<script type="text/javascript">sec_desc_flag = true;</script>
	<div class="form-field">
		<label for="second_description"><?php _e('Second Description'); ?></label>
		<textarea name="second_description" id="second_description" rows="5" cols="40"></textarea>
	</div>
	<div class="form-field">
		<label for="catorder"><?php _e('Order'); ?></label>
		<input type="text" name="catorder" id="catorder" style="width:50px;">
	</div>
<?php
}
add_action('solution_category_edit_form_fields','solution_category_add_custom_fields_editing');
add_action('solution_category_add_form_fields','solution_category_add_custom_fields_adding');

add_action('product_category_edit_form_fields','solution_category_add_custom_fields_editing');
add_action('product_category_add_form_fields','solution_category_add_custom_fields_adding');

add_action('service_category_edit_form_fields','solution_category_add_custom_fields_editing');
add_action('service_category_add_form_fields','solution_category_add_custom_fields_adding');


function solution_category_update_custom_fields($term_id) {
	global $wpdb;
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."term_taxonomy SET second_description = '".$_POST['second_description']."' WHERE term_id = '".$term_id."'"));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."terms SET term_group = '".$_POST['catorder']."' WHERE term_id = '".$term_id."'"));
}
add_action('edited_term', 'solution_category_update_custom_fields');
add_action('created_solution_category', 'solution_category_update_custom_fields');
add_action('created_product_category', 'solution_category_update_custom_fields');
add_action('created_service_category', 'solution_category_update_custom_fields');

/*function term_request_order( $order, $args) {
	if ($args['orderby'] == 'second_description') {
		
	}
}*/

function solution_category_second_description() {
	global $wpdb;
	$add_sd_field_flag = true;
	$tt_fields = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."term_taxonomy");
	foreach($tt_fields as $tt_field) {
		if ($tt_field->Field == 'second_description') { $add_sd_field_flag = false; }
	}
	if ($add_sd_field_flag) {
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."term_taxonomy ADD second_description TEXT NULL");
	}
}
add_action('init', 'solution_category_second_description');
// ----------------------------------------------
// solution category end
// ----------------------------------------------

function force_brs( $text ) {
	return str_ireplace('[br]', '<br />', $text);
}

function remove_brs( $text ) {
	return str_ireplace('[br]', '', $text);
} 
/*
function change_ytembed_height($content, $url = 1, $args = 2) {
	if( current_user_can('administrator') ) {
		var_dump( $url );
		var_dump( $args);
	}
	$pattern = '(height=[\'\"](\d+)[\'\"])'; //pattern to look for
    if (false !== strpos($content,"youtube")) {
        $content = preg_replace_callback($pattern, do_maths, $content);
    }
    return $content;
}
add_filter('embed_oembed_html','change_ytembed_height', 9999, 3);
*/



if ( ! is_admin() ) add_action( 'wp_head', 'replace_wordtube_script', 999 );

function replace_wordtube_script() {
    global $wordtube;
    wp_enqueue_script('swfobject');
    remove_action( 'wp_print_scripts', array( $wordtube, 'integrate_js') );
}



	//Acumatica ERM insert, makes 3 calls to create account, linked contact and linked opportunity. Returns array with result (status, IDs, error message)
	function createAcuOpportunity($arrAcuInsertParams){
		//create account
		
		//prepare result array: 
		$arrAcuInsertReuslt = array(
			"status" => false,		
			"account_id" => 0,
			"contact_id" => 0,
			"opportunity_id" => 0,
			"errorMessage" => ''
			);				
		
		
		$accCallURL = $arrAcuInsertParams['acuIntegrationURL'] . "AccountCreate.php?accBusinessAccountName=".$arrAcuInsertParams['accBusinessAccountName'] . 
		"&accBusinessName=" . $arrAcuInsertParams['accBusinessName'] . "&accAttention=" . $arrAcuInsertParams['accAttention'] . "&accEmail=" . 	$arrAcuInsertParams['accEmail'] . 
		"&accPhone1=" . $arrAcuInsertParams['accPhone1'] . "&accFax=" . $arrAcuInsertParams['accFax'] . 
		"&accAddressLine1=". $arrAcuInsertParams['accAddressLine1']."&accCity=". $arrAcuInsertParams['accCity'].
		"&accCountry=". $arrAcuInsertParams['accCountry']."&accState=". $arrAcuInsertParams['accState'].
		"&accPostalCode=". $arrAcuInsertParams['accPostalCode']."&acuTest=". $arrAcuInsertParams['acuTest'];

        $searchAcc = makeAcuScreenCall( $arrAcuInsertParams['acuIntegrationURL'] . 
            "searchAccount.php?acuName=" . $arrAcuInsertParams['accBusinessAccountName'] .
            "&acuCountry=".$arrAcuInsertParams['accCountry'] .
            "&acuState=".$arrAcuInsertParams['accState'].
            "&acuTest=". $arrAcuInsertParams['acuTest']
        );

		$callResult = $searchAcc ? $searchAcc : makeAcuScreenCall($accCallURL);		

		if ($callResult and strlen($callResult) < 37){			
			$arrAcuInsertReuslt['account_id'] = $callResult;
		}else{
			//error
			$arrAcuInsertReuslt['errorMessage'] = $callResult;
			return $arrAcuInsertReuslt;
		}
		
		//create contact
		
		$conCallURL = $arrAcuInsertParams['acuIntegrationURL'] . "ContactCreate.php?conAccountID=".$arrAcuInsertReuslt['account_id'] . 
		"&conFirstName=" . $arrAcuInsertParams['conFirstName'] . "&conLastName=" . 	$arrAcuInsertParams['conLastName'] . 
		"&conPosition=" . $arrAcuInsertParams['conPosition'] . "&conPhone1=" . $arrAcuInsertParams['conPhone1'] . 
		"&conEmail=". $arrAcuInsertParams['conEmail']."&conAddressSameAsMain=". $arrAcuInsertParams['conAddressSameAsMain'].
		"&acuTest=". $arrAcuInsertParams['acuTest'];
		
        $searchCtc = $searchAcc ? makeAcuScreenCall($arrAcuInsertParams['acuIntegrationURL'] . 
            "searchContact.php?acuAccount=" . $arrAcuInsertReuslt['account_id'] .
            "&acuEmail=".$arrAcuInsertParams['conEmail'] .
            "&acuTest=". $arrAcuInsertParams['acuTest']
        ) : ''; 
        
		$callResult = $searchCtc ? $searchCtc : makeAcuScreenCall($conCallURL);		
		
		if ($callResult and strlen($callResult) < 37){			
			$arrAcuInsertReuslt['contact_id'] = $callResult;
		}else{
			//error
			$arrAcuInsertReuslt['errorMessage'] = $callResult;
			return $arrAcuInsertReuslt;
		}
		
		
		
		//create opportunity
	
		$oppCallURL = $arrAcuInsertParams['acuIntegrationURL'] . "OpportunityCreate.php?oppAccountID=".
		$arrAcuInsertReuslt['account_id'] . "&oppContactID=" . $arrAcuInsertReuslt['contact_id'] . 
		"&oppClass=" . $arrAcuInsertParams['oppClass'] . "&oppLocation=" . $arrAcuInsertParams['oppLocation'] . 
		"&oppDescription=" . $arrAcuInsertParams['oppDescription'] . "&oppComments=" . $arrAcuInsertParams['oppComments'] . 
		"&acuTest=". $arrAcuInsertParams['acuTest'];

		$callResult = makeAcuScreenCall($oppCallURL);
		
		if ($callResult and strlen($callResult) < 37){			
			$arrAcuInsertReuslt['opportunity_id'] = $callResult;
		}else{
			//error
			$arrAcuInsertReuslt['errorMessage'] = $callResult;
			return $arrAcuInsertReuslt;
		}		
		
        makeAcuScreenCall($arrAcuInsertParams['acuIntegrationURL'] . "searchContact.php?command=reload&acuTest=". $arrAcuInsertParams['acuTest']);
        makeAcuScreenCall($arrAcuInsertParams['acuIntegrationURL'] . "searchAccount.php?command=reload&acuTest=". $arrAcuInsertParams['acuTest']);
        
		$arrAcuInsertReuslt['status'] = true;
		return $arrAcuInsertReuslt;
	}
	
	//makes CURL call to acumatica
	function makeAcuScreenCall($acuScreenCallURL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $acuScreenCallURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		//echo $output . "<br>";
		curl_close($ch);  
		return $output;
	}



include "geoloc.php";
include "class-tpl.php";
include "extended_admin.php";
include "hv_slider.php";
include "proper-attachments.php";
include "contact-forms.php";
include "add_widgets.php";
include "inc/import.php";
include "slider2.php";
include "inc/constant_integrate.php";
include "plugin-taxonomy-thumbs.php";
include "plugin-html-exerpts.php";
include "plugin-html-descriptions.php" ;
include "plugin-custom-links.php" ;
include 'custom-cache.php';
include 'adwords.php';
include "new-contact-form.php";