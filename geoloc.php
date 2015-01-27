<?php
session_start();
include 'Ip2Country.php';
include 'countrylist.php';


setlocale(LC_ALL, strtolower(ICL_LANGUAGE_CODE).'_'.strtoupper(ICL_LANGUAGE_CODE));
global $GEO_list_countries;
asort($GEO_list_countries, SORT_LOCALE_STRING);

function list_countries( $cur = NULL, $return = false, $no_empty = false) {
	global $GEO_location, $GEO_list_countries;	
	if (is_null($cur)) $cur = $GEO_location['countryCode'];
	$list = '';
	if ( $no_empty ) {
		$act_list = array();
		$ps = new WP_Query(array(
			'post_type' => 'dealer',
			'showposts' => 999
		));
		foreach($ps->posts as $dlr) {
			$cn = get_post_meta( $dlr->ID, 'country', true);
			if (! in_array($cn, $act_list)) array_push($act_list, $cn);
		}
		
	}
	
	foreach ($GEO_list_countries as $cc => $cn) {
		$cl = ($cc === $cur)?' selected ':'';
		$accept = true;
		if ( $no_empty && !in_array($cc, $act_list) ) {
			$accept = false;
		}
		if ( $accept ) $list .= '<option value="'.$cc.'" '.$cl.'>'.$cn."</option> \n";
	} 
	if ( $return ) return $list;
	echo $list;
	return $list;
}

function get_geo( $ip = NULL ) {
	global $GEO_location, $debug_msg;
	if ( is_null($ip) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SESSION['user_geo_ip'])) $ip = $_SESSION['user_geo_ip'];
	}
	//if ( $ip == '92.113.148.16' ) $ip = '78.46.9.199';
	//if ( $ip == '93.73.191.88' ) $ip = '78.46.9.199';
	$ip2loc = new Ip2Country;
	if (isset($_SESSION['user_geo_ip']) && $_SESSION['user_geo_ip'] == $ip && strlen(trim($_SESSION['geoloc_result']['city']))) {
		if (isset($_SESSION['geoloc_result'])) $res = $_SESSION['geoloc_result'];
	} else {
		$ip2loc->dir = ABSPATH . 'wp-content/geo_db/db_ip/';
		$ip2loc->loc_dir = ABSPATH . 'wp-content/geo_db/db_loc/';
		$res = $ip2loc->load($ip);
		$res['city'] = utf8_encode($res['city']);
		$res['region'] = utf8_encode($res['region']);
	}
	$_SESSION['user_geo_ip'] = $ip;
	$_SESSION['geoloc_result'] = $res;

	$debug_msg .= "DEBUG:  IP - $ip; LOCATION: ".print_r($res, true)." \n";
	echo '<pre id="debugg">';
	var_dump(
		$debug_msg, 
		$_SESSION['geoloc_result'], 
		strlen(trim($_SESSION['geoloc_result']['city'])),
		$ip2loc->load($ip)
	);
	echo '</pre>';
	return $res;
}



function get_current_homepage( $field = 'dumb' ) {
	global $homepage, $homepage_post;
	if ( isset($homepage[$field][0]) ) return $homepage[$field][0];
	
	$hp = new WP_Query(array(
		'post_type'		=> 'homepage',
		'posts_per_page' => 1,
		'country'		=> get_geo_slug()
	));
	
	if ( count($hp->posts) ) {
	    $homepage_post = $hp->posts[0];
		$homepage = get_post_custom( $homepage_post->ID );
	}
	if (isset($homepage[$field][0])) return $homepage[$field][0];
	return '';
}

function is_geo_local() {
	return (get_geo_slug() != 'other');	
}

function get_geo_slug() {
	global $GEO_location;
	return $GEO_location['term']->slug . ", all";
}

function get_geo_slug_array() {
	global $GEO_location;
	return array($GEO_location['term']->slug,  "all");
}

function get_geo_lang() {
	global $GEO_location;
	$l_de = array( 'CH', 'SE', 'DE', 'UA' );
	$l_fr = array( 'FX', 'FR', 'GF', 'PF', 'TF' );
	$cd = strtoupper($_SESSION['geoloc_result']['countryCode']);
	if ( in_array($cd, $l_de) ) return 'de';
	if ( in_array($cd, $l_fr) ) return 'fr';
	return ICL_LANGUAGE_CODE;
}

function get_geo_locale_terms( $code, $country ) {
	global $countries_menu_list;
	$countries_menu_list = array();
	$terms = get_terms('country', array(
		'hide_empty'	=> false
	));
	$other 	= NULL;
	$result = array();
	$_tt = new Ip2Country; 
	$list_countries = $_tt->codes;
	unset($_tt); 
	foreach($terms as $term) {
		if ( strtolower($term->name) == 'other' ) {
			$other = $term;
			$cc 	= 'other';
		} else {
			$cc = array_search(strtoupper($term->name), $list_countries);
		}
		if ( strtolower($term->name) == strtolower($country) ||	strtoupper($term->name) == strtoupper($code) ) {
			$result = array($term);
		}

		$cc and $countries_menu_list[$cc] = $term->name;
		 
	  	if ( $cc ) register_sidebar(array(
		    'name'			=> 	__('Homepage','SwissPhone') . ' - ' . $term->name,
		    'id'			=>	'country-sidebar-'.strtolower($cc),
		    'before_widget' => '<div  id="%1$s" class="homepage-widget-block %2$s">',
		    'after_widget' 	=> '</div>',
		    'before_title' 	=> '<h2><span>',
		    'after_title' 	=> '</span></h2>'
		));
	}
	if ( count($result) ) return $result; 
	return array($other);
}

function init_geo_loc() {
	global $GEO_location;
	$ip = NULL;
	//var_dump($_POST);
	$redirect = array(
		'AU' => '122.99.95.245',
		'AT' => '144.65.222.0',
		'CA' => '70.54.89.73',
		'FR' => '88.190.229.170',
		'DE' => '77.185.208.234',
		'other' => '0.0.0.0',
		'CH' => '195.141.111.0',
		'US' => '4.71.173.0',
	);
	if(isset($_POST['swiss_country_code']) and isset($redirect[$_POST['swiss_country_code']]))
		$ip = $redirect[$_POST['swiss_country_code']];
	//var_dump($ip);
	//if (isset($_GET['ip'])) $ip = $_GET['ip'];
	$GEO_location = get_geo( $ip );
	$terms = get_geo_locale_terms($GEO_location['countryCode'], $GEO_location['country']);
	$GEO_location['terms']	= $terms;
	$GEO_location['term']	= array_pop($terms);
}

add_action('init', 'init_geo_loc', 10000);