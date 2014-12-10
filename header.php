<?php
/**
 * @package WordPress
 * @subpackage SwissPhone
 */

	
	global $gContext, $sitepress, $wp_query;
	if ( strlen($gContext) ) {
		$gTerm = get_term_by('slug', get_query_var($gContext), $gContext);
		$gTerm = $sitepress->get_element_trid( $gTerm->term_taxonomy_id, 'tax_'.$gContext );
		$gTerm = $sitepress->get_element_translations( $gTerm, 'tax_'.$gContext );
	} else {
		$gTerm = false;
	} 	
	
	define('CURRENT_PAGE_ID', $wp_query->get_queried_object_id());
	
	$languages = icl_get_languages('orderby=id&order=asc&skip_missing=0');
	$l_sel 		 = '';
	
	//echo $last_lang;
	//echo '<br>'.ICL_LANGUAGE_CODE;

	$auto_redirect = false;

    if(!empty($languages)){
        $l_sel .= '
            <div id="lang_select_header">
                <ul>
                ';
			
            foreach($languages as $key => $lang){
            	$url = apply_filters('WPML_filter_link', $lang['url'], $lang);
				if (is_array($gTerm) && count($gTerm)) {
					//remove_filter('term_link', array($sitepress, 'tax_permalink_filter'));
					if (isset($gTerm[$key]->element_id)
						&&
						$_tid = (int)$wpdb->get_var('SELECT term_id FROM '.$wpdb->prefix.'term_taxonomy WHERE term_taxonomy_id = '.$gTerm[$key]->element_id)
						){
						global $icl_adjust_id_url_filter_off;
						$icl_adjust_id_url_filter_off = true;
						$url = get_term_link(get_term($_tid, $gContext));
						$icl_adjust_id_url_filter_off = false;
					} else {
						$url = '';
					}
					//remove_filter('term_link', array($sitepress, 'tax_permalink_filter'),1,2);
				} elseif (strlen($gContext)) {
					$url = '';
				}
				$skip = false;
				if (empty($url)) {
					$url = home_url( '/'.$key.'/' );
					$skip = true; 
				}
				if ( ! $skip ) $l_sel.= 
                	'<li>
                		<a href="'.add_query_arg( 'set_lang', $lang['language_code'], $url).'"' .
                		 ' class="lang_select_'.$lang['language_code'].'">'.$lang['language_code'].'</a></li>';
						;
                /*if($lang['active']) {
                	$l_sel.= ' class="lang_active"';
				}*/
				
				if ( isset($_COOKIE['sp_lang_select']) && $_COOKIE['sp_lang_select'] != ICL_LANGUAGE_CODE && $_COOKIE['sp_lang_select'] == $lang['language_code'] ) {
					$auto_redirect = $url;
				}
				
            }
        $l_sel.=  '</ul>
            </div>';
     }
	if ( $auto_redirect && ( is_front_page() || strlen(trim(str_ireplace( home_url( '/' ), '', $auto_redirect), ' /')) > 2 )) {
		wp_redirect( $auto_redirect );
		exit;
	}
	$_SESSION['sp_lang'] = ICL_LANGUAGE_CODE;

	/*if ( $ret_key && @$_SESSION['last_lang'] && $url_change && ($last_lang != $_SESSION['last_lang']) ) {
		$tt = str_replace(home_url('/'), '', $ret_key);
		if ( strlen($tt) > 4) {
			$get_arr = array();
			if (is_array($_GET)) foreach ($GET as $key => $val) {
				$get_arr[] = "$key=$val"; 
			}
			if (count($get_arr)) {
				$get_arr = "?" . implode('&', $get_arr);
			} else {
				$get_arr = '';
			}
			wp_redirect($ret_key.$get_arr);
			die();
		}
	}*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />

<?php
        if ( is_front_page() and $home_title = get_current_homepage( '_aioseop_title' ) ) {
            ?>
            <title><?php echo esc_attr( $home_title ); ?></title>
            <?php
        } else {
            ?>
            <title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo('name'); ?></title>
            <?php
        }
        	
	    if ( is_front_page() ) {
            global $aiosp;
            remove_action( 'wp_head', array( $aiosp, 'wp_head' ) );
            if ( $home_keyword = get_current_homepage( '_aioseop_keywords' ) ) {  echo '<meta name="keywords" content="',esc_attr($home_keyword),'" />'."\r\n";   }
            if ( $home_descr = get_current_homepage( '_aioseop_description' ) ) {  echo '<meta name="description" content="',esc_attr($home_descr),'" />'."\r\n";   }
        }
?>

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ) ?>/inc/web360/basic.css" /> 
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php 
		if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
		
		wp_head(); ?>
	<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie.css" type="text/css" media="screen" /><![endif]-->
	<!--
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_url'); ?>/js/css_browser_selector.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_url'); ?>/js/jquery.carousel.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/custom-form-elements.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.cycle.min.js"></script>
	-->
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/script-load.js"></script>    
	<script type="text/javascript">
	
	 var _gaq = _gaq || [];
	 _gaq.push(['_setAccount', 'UA-27775263-1']);
	 _gaq.push(['_trackPageview']);
	
	 (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	 })();
	
	</script>	
	
	<script type="text/javascript">
	    var player = new Array();
        //var counter = 0;
        function playerReady(obj) {
            var id = obj['id'];
            var version = obj['version'];
            var client = obj['client'];
            //console.log('the videoplayer '+id+' has been instantiated');
            player[id] = document.getElementById(id);
            addListeners(id);
        };
        
        function addListeners(id) {
            if (player[id]) { 
                player[id].addModelListener("STATE", "stateListener");
            } else {
                setTimeout("addListeners()",100);
            }
        }
        
        function stateListener(obj) { 
            //possible states IDLE, BUFFERING, PLAYING, PAUSED, COMPLETED
            currentState = obj.newstate; 
            previousState = obj.oldstate;
            //console.log('current state : '+ currentState + ' previous state : '+ previousState );
            
            //find out what title is playing (or id of the file)
            var cfg = player[obj.id].getConfig();
            var plst = player[obj.id].getPlaylist();
        
            //decide if the counter needs updating and then 
            //update in the db with ajax request
            var decision = false;
            if (((currentState == "PLAYING") && ( (previousState == "BUFFERING") ||(previousState == "COMPLETED")))) {
                decision = true;
            }
            //test
            if(decision) {
                var ajaxString = "file=" + escape( plst[cfg["item"]].file );
                jQuery.ajax({
                    type: "POST",
                    data: ajaxString,
                    url: "<?php echo site_url('/index.php?wt-stat=true');?>"
                }); 
            }
        }
	</script>
</head>
<body <?php body_class(); ?> >
<div class="height-block">
<div class="global-block">
  <!-- top -->
  <div class="top-block">
  
    <div class="left">
	  <?php get_top_menu(); ?>
	</div>
	
	<div class="right">
	  <div class="language-block">
	  	<?php	echo $l_sel;  	?>
	  </div>
	  <div class="search-block">
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"
		      class="country-select" style="position: relative; margin-top: 5px;">
			<select name="swiss_country_code" class="styled" onchange="jQuery(this).closest('form').submit();">
			<?php
				foreach($GLOBALS['countries_menu_list'] as $code => $name)
					if($code == 'other'){
						echo "<option value='$code' ",
							selected(in_array($GLOBALS['GEO_location']['countryCode'],array('AU','DE','FR','AT','CA','CH','US')),false,false),
							">$name</option> \r\n";
					}else{
						echo "<option value='$code' ",selected($GLOBALS['GEO_location']['countryCode'],$code,false),">$name</option> \r\n";
					}
			?>
			</select>
		</form>
		<script type="text/javascript">
			jQuery(function($){
				$('form.country-select select').change(function(){
					$(this).closest('form').submit();
				});
			});
		</script>
	    <?php //get_search_form(); ?>
	  </div>
	</div>
	
  </div>
  <!-- end top -->
  
  <!-- header -->
  <div class="header-block cf">
    
	<div class="logo"><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"><img src="<?php bloginfo('template_url'); ?>/images/logo.gif" alt="<?php bloginfo('name'); ?>" /></a></div>
	
	<div class="request-link">
	  <?php get_top_link_menu(); ?>
	</div>
	
  </div>
  <!-- end header -->
  
  <!-- menu -->
  <div class="menu-block">
  
    <div class="main-menu">
	  <?php get_main_menu(); ?>
	<script type="text/javascript">
jQuery('#nav li ul').each(function(){
	var $wrap = false;
	jQuery(this).children().each(function(){ $wrap = true; });
	if ($wrap) {
		jQuery(this).wrap('<div class="sub-menu"></div>');
	} else {
		jQuery(this).remove();
	}
});
jQuery('#nav div.sub-menu').prepend('<div class="top-bg-sub-menu">&nbsp;</div>');
jQuery('#nav li ul li div.sub-menu').parent().addClass('select');
</script>
	</div>
	<?php /*
	<div class="product-select">
	  <form action="/" onSubmit="return false;">
	    <select class="styled" name="products_a_z" id="products_a_z" 
	    	onchange='document.location.replace(this.options[this.selectedIndex].value);'>
			<option value=""><?php _e('Select product', 'SwissPhone'); ?></option>
	    	<?php
	    		$arg = array(
					'post_type' 	=> 'product',
					'posts_per_page'=> 100,
					'orderby'		=> 'title', 
					'order'			=> 'ASC',
					'country'   	=> get_geo_slug(),
					'post_parent'		=> 0
				); 
	    		$paz = new WP_Query($arg);
				foreach ( $paz->posts as $pr) {
					if ($pr->post_parent == 0)
						echo '<option value="'.get_permalink($pr->ID).'">'.$pr->post_title."</option>\n";
				}
	    	?>
		</select>
	  </form>
	</div>*/ ?>
	<?php if ($ph = get_current_homepage( 'phone' )): ?>
	<div class="phone-number">
		<div class="textwidget"><?php echo $ph;  ?></div>
	</div>
	<?php endif; ?>
    <?php /* ?>
	<div class="search-block"><?php get_search_form(); ?></div>
    <?php */ ?>
  </div>
  <!-- end menu -->
  
  <!-- content -->
  <div class="content-block">