<?php

define ( 'CUSTOM_CACHE_ON', get_option( 'custom_cache_mode', 'on' ) == 'on' );
define ( 'CUSTOM_CACHE_INTERVAL', get_option( 'custom_cache_timeout', 1800 ) );
define ( 'CUSTOM_CACHE_SHOWALL', get_option( 'custom_cache_show', 'all' ) == 'all' );

add_action( 'template_redirect', 'custom_cache_start', -99999 );
//add_action( 'shutdown', 'custom_cache_end', 99999 );

global $custom_cache_name;
define( 'C_CAHCE_DIR', ABSPATH . '/wp-content/cc/' );

function custom_cache_current_page() {
	return md5( get_geo_slug() . ICL_LANGUAGE_CODE . get_current_user_id() . $_SERVER['REQUEST_URI'] ); 
}

function custom_cache_exists( $name ) {
	if ( ! @file_exists( C_CAHCE_DIR . $name ) ) return false;
	if ( @filemtime( C_CAHCE_DIR . $name ) + CUSTOM_CACHE_INTERVAL < $_SERVER['REQUEST_TIME'] ) {
		unlink( C_CAHCE_DIR . $name );
		return false;
	} 
	return true;
}

function custom_out_cache( $name ) {
	echo gzinflate(file_get_contents( C_CAHCE_DIR . $name ));
}

function custom_cache_start() {
	
	if ($_SERVER["HTTP_HOST"] == 'www.swissphone.de' || $_SERVER["HTTP_HOST"] == 'swissphone.de') {
		header("location: http://www.swissphone.com/de/");
		exit;
	}

	if ( isset($_GET['set_lang']) ) {
		setcookie('sp_lang_select');
		setcookie('sp_lang_select', ICL_LANGUAGE_CODE, time() + 999999, '/' );
		$_SESSION['set_c_h'] = $_GET['set_lang'];
		wp_redirect( add_query_arg( 'set_lang', false ) );
		exit;
	}
	
	if ( (!isset( $_COOKIE['sp_lang_select'] ) || empty($_COOKIE['sp_lang_select'])) && empty($_SESSION['set_c_h']) ) {
		$_tl = get_geo_lang();
		$_SESSION['set_c_n'] = $_tl;
		setcookie('sp_lang_select');
		setcookie('sp_lang_select', $_tl , time() + 999999, '/' );
		//wp_redirect( add_query_arg( 'set_lang', get_geo_lang() ) );
		//exit;
		$_COOKIE['sp_lang_select'] = $_tl;
		unset($_tl);
	}
	
		
	if(isset($_REQUEST['s']) && $_COOKIE['sp_lang_select'] != ICL_LANGUAGE_CODE ){
		//wp_redirect($ret_key.$get_arr);
		//echo home_url('/');
		$new_search_url = home_url( '/'.$_COOKIE['sp_lang_select']. '/?s='.$_GET['s'] );
		wp_redirect($new_search_url);
		exit;
	}
	
	global $custom_cache_name;
	$custom_cache_name = custom_cache_current_page();
	if ( defined('CUSTOM_CACHE_ON') && CUSTOM_CACHE_ON && (CUSTOM_CACHE_SHOWALL || !is_user_logged_in()) && custom_cache_exists($custom_cache_name) && empty($_POST) ) {
		custom_out_cache($custom_cache_name);
		exit();
	}
	ob_start( 'custom_cache_end' );
}

function custom_cache_end( $data = '' ) {
	//$data = ob_get_flush();
	//if (isset($_GET['test'])) var_dump(debug_backtrace());
	if ( ! is_admin() && strlen($data) ) {
		
		global $custom_cache_name;
		if ( ! @file_exists( C_CAHCE_DIR ) ) mkdir( C_CAHCE_DIR, 0777 );
		$f = @fopen( C_CAHCE_DIR . $custom_cache_name, 'w+' );
		fwrite( $f, gzdeflate($data,2) );
		fclose($f);
        $last_update = get_option('custom_cache_update');
        if ( ($last_update + CUSTOM_CACHE_INTERVAL) < $_SERVER['REQUEST_TIME'] ) {
            update_option( 'custom_cache_update', $_SERVER['REQUEST_TIME'] );
            foreach( scandir( C_CAHCE_DIR ) as $file ) {
                if ( (strlen($file) == 32) and (@filemtime( C_CAHCE_DIR . $file ) + CUSTOM_CACHE_INTERVAL) < $_SERVER['REQUEST_TIME'] ) {
                    unlink( C_CAHCE_DIR . $file );
                }
            }            
        }
	}
	return $data;
}

function purge_all_c_cache() {
    foreach( scandir( C_CAHCE_DIR ) as $file ) {
        if ( strlen($file) == 32 ) {
            unlink( C_CAHCE_DIR . $file );
        }
    }
}
 


