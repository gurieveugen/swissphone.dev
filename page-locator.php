<?php
/**
 * @package WordPress
 * @subpackage SwissPhone
 * Template Name: Locator Page
 */
?>
<?php get_header(); ?>
    <div class="crumbs-block"><?php show_bread_crumbs() ?></div>
    <div class="post-page">
	  <div class="left"><?php get_sidebar(); ?></div>
	  
	  <div class="right">
	<?php if ( have_posts() ) : the_post(); ?>

	  <div id="post-<?php the_ID(); ?>">
	  	<div class="locator-debug" style="display: none;"></div>
		<h1><?php the_title(); ?></h1>
		<div class="entry-content contact-page">
		  <div class="top-left-bg">
		    <div class="top-right-bg">
			  <?php the_content(); ?>
			  <?php 
			  	global $GEO_location;
			  	$dealers = new WP_Query(array(
					'post_type'		=> 'dealer',
					'posts_per_page'=> 999,
					//'country'		=> $GEO_location['term']->slug
				));
				//var_dump($dealers);
				
			    $d_list = array();
				foreach ( $dealers->posts as $dlr ) {
					$_meta 		=	get_post_custom( $dlr->ID );
					$ID			=	esc_js($dlr->ID);
					$name		=	esc_js(apply_filters( 'dealer_title', $dlr->post_title ));
					$country 	=	esc_js($_meta['country'][0]);
					$city 		=	esc_js($_meta['city'][0]);
                    $state 		=	esc_js($_meta['state'][0]);
					$address 	=	esc_js($_meta['address'][0]);
					$phone 		=	esc_js($_meta['phone'][0]);
					$zip 		=	esc_js($_meta['zipcode'][0]);
					$_lng		=	str_replace(array(","," "),array(".",""),esc_js((float)$_meta['coord_lng'][0]));
					$_lat		=	str_replace(array(","," "),array(".",""),esc_js((float)$_meta['coord_lat'][0]));
                    if ($country == "US") {
                        $_html		=	str_ireplace( "\r", '\\' . "\r" ,'<div>
    						<h3 style="color: #CC0033;">'.$name.'</h3>
    						<p>
                                <strong>'.__('Address','SwissPhone').'</strong> : '.$address.'<br />    							
    							<strong>'.__('City','SwissPhone').'</strong> : '.$city.' <br />
                                <strong>'.__('State','SwissPhone').'</strong> : '.$state.' <br />    							
    						   <strong>'.__('Zipcode','SwissPhone').'</strong> : '.$zip.'<br />
                               <strong>'.__('Phone Number', 'SwissPhone').' : </strong> '.$phone.'<br />
                               <strong>'.__('Email','SwissPhone').'</strong> : '.esc_js($_meta['email'][0]).'<br />
    						   <strong>'.__('Website','SwissPhone').'</strong> : '.esc_js($_meta['web'][0]).'<br />
    						   <strong>'.__('Portfolio', 'SwissPhone').' : </strong> '.esc_js($_meta['portfolio'][0]).'
    						</p>
    					 </div>');
                        
                    } else {
    					$_html		=	str_ireplace( "\r", '\\' . "\r" ,'<div>
    						<h3 style="color: #CC0033;">'.$name.'</h3>
    						<p>
    							<strong>'.__('Distance','SwissPhone').'</strong> : <span class="distance"></span><br />
    							<strong>'.__('City','SwissPhone').'</strong> : '.$city.' <br />
    							<strong>'.__('Address','SwissPhone').'</strong> : '.$address.'<br />
    						   <strong>'.__('Zipcode','SwissPhone').'</strong> : '.$zip.'<br />
    						   <strong>'.__('Website','SwissPhone').'</strong> : '.esc_js($_meta['web'][0]).'<br />
    						   <strong>'.__('Email','SwissPhone').'</strong> : '.esc_js($_meta['email'][0]).'<br />
    						   <strong>'.__('Phone Number', 'SwissPhone').' : </strong> '.$phone.'<br />
    						   <strong>'.__('Portfolio', 'SwissPhone').' : </strong> '.esc_js($_meta['portfolio'][0]).'
    						</p>
    					 </div>');
                    }
					
					$d_list[] = "{ ID: '$ID', location: false, lng: '".$_lng."', lat: '".$_lat."', country: '$country', city: '$city', address: '$address', zip: '$zip' ,LatLng: '', distance: 99999999, html: '$_html' }";
				}
			  	$d_list_js = "[ \n ". implode( ", \n ", $d_list ) . " \n ]";
			    global $GEO_location;
				$cur_user_geo_js = array();
				foreach ($GEO_location AS $key => $val ) {
					$str = "$key: ";
					if ( !is_scalar($val)) continue;
					if ( is_string($val) ) {
						$val = '"'.esc_js($val).'"';
					} 
					$str .= $val;
					$cur_user_geo_js[] = $str;
				}
				$cur_user_geo_js = "{".implode( ", \n", $cur_user_geo_js)."}";
	?>
			  <form action="#" method="post" class="contact-form cf" id="dealer_search_form" onsubmit="return do_locator_search();">
			    <p><label><?php _e('Country', 'SwissPhone'); ?> : </label>
					<select class="styled" name="search_country">
						<?php list_countries( NULL, false, true); ?>
					</select></p>
				<p><label><?php _e('Zipcode', 'SwissPhone'); ?> : </label><input type="text" name="search_zip" id="search_zip" /></p>
				<p style="display: none;"><label><?php _e('Physical Address','SwissPhone' ); ?>:</label><input type="text" name="search_address" id="search_address" value="<?php //echo esc_attr($GEO_location['city']); ?>" /></p>
				<div class="sub-input-big"><input type="submit" value="<?php _e('Search for Dealers', 'SwissPhone'); ?>" /></div>	 
			  </form>
			  
			  	<div id="dealer_locator_canvas" style="width: 720px; height: 600px; display: none;"></div>	
			  	<script src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false" type="text/javascript"></script>
		    <script type="text/javascript">
		    var $ = jQuery;
		    var all_dealer_info		= <?php echo $d_list_js; ?>; 
			/* var cur_user_geo_loc	= <?php echo $cur_user_geo_js; ?>; */
			var cur_located			= 0;
			var geocoder			= null;
			var search_data			= new Array();
			var dlr_pnt				= 0;

			function build_next_dlr() {
				if ( dlr_pnt >= all_dealer_info.length ) return false;
				var dlr = all_dealer_info[dlr_pnt];
				if ( (dlr.lng == 0) || (dlr.lat == 0) ) {
					//alert( dlr.country +  ', ' + dlr.city + ' ' + dlr.address + ', ' + dlr.zip );
					geocoder.geocode( { 'address': dlr.country +  ', ' + dlr.city /*+ ' ' + dlr.address*/ + ', ' + dlr.zip } , function( results, status ) {
				    	if (status == google.maps.GeocoderStatus.OK) {
				      		dlr.location = results[0].geometry.location;
				      		search_data.push( dlr );
				      		$.ajax({
				        	   type: "POST",
							   url: "<?php echo esc_js(home_url('/')); ?>",
							   data: "caller=locator&dealer_id="+dlr.ID+"&lat=" + dlr.location.lat() + "&lng=" + dlr.location.lng()
				        	});
				    	}
				    	//if ( window.console ) console.log(status);
				    	//alert(status);
				    	dlr_pnt++;
				    	build_next_dlr();
				    });
				} else {
					//console.log(dlr);
					dlr.location = new google.maps.LatLng( dlr.lat, dlr.lng );
					search_data.push( dlr );
					dlr_pnt++;
					build_next_dlr();
				}
				return true; 
			}
			
			function show_locator_not_found() {
				jQuery('#dealers_list').html('<strong> <?php echo html_entity_decode(esc_js(get_option('dealers_not_found_'. strtolower(ICL_LANGUAGE_CODE)))); ?> </strong>');
			}
			
			function compare_distance(l1, l2) {
				return (l1.distance - l2.distance); 
			}
			
			
			
			function sort_and_show_results( loc ) {
				for ( pos in search_data ) {
					dlr = search_data[pos];
				    dlr.distance = google.maps.geometry.spherical.computeDistanceBetween( loc, dlr.location );
	        	}
	        	search_data.sort(compare_distance);
	        	var dlr_shown = 0;
	        	(function($){
		    		$('#dealers_list').html('');
		    		for( i in search_data ) {
		    			dlr = search_data[i];
		    			$('.locator-debug').append('cur: '+ i + ' <br/ >');
		    			if (dlr_shown >= <?php echo intval(get_option('dealers_search_count')); ?>) break;
		    			$('.locator-debug').append('distance: '+ dlr.distance + ' <br/ >');
		    			if (dlr.distance > <?php echo intval(get_option('dealers_search_distance')) * 1000; ?>) break; 
		    			if (dlr.country == $('#dealer_search_form select').val()) {
		    				$(dlr.html).find('.distance').html(Math.round(dlr.distance / 1000)  + 'km').end()
		    					.appendTo($('#dealers_list'));
		    				dlr_shown++;
		    			}
		    		}
		    		if ( dlr_shown < 1 ) show_locator_not_found();
		    		$('.locator-debug').append('Shown: '+ dlr_shown + ' <br/ >');
		    	})(jQuery);
			}
			
			function do_locator_search() {
				if ( jQuery('#search_zip').val().length < 2 ) {
	        		alert( '<?php echo esc_js( __( 'Please enter postal code','SwissPhone') ) ?>' );
	        		return false;
		        }
		        jQuery('#dealers_list').html('<?php _e('Searching...', 'SwissPhone'); ?>');
				var country_addr = $('#dealer_search_form select option:selected').text() + ' ' + jQuery('#search_zip').val();
		        geocoder.geocode( { 'address': country_addr } , function( results, status ) {
			      if (status == google.maps.GeocoderStatus.OK) {
				      sort_and_show_results( results[0].geometry.location );
			      } else {
					  show_locator_not_found();
			      }
			    });
			    return false;
			}
			
			jQuery(function($){
				geocoder = new google.maps.Geocoder();
				if (geocoder) {
					build_next_dlr();
			    }
			});


		    </script>
		    <div id="dealers_list">
		    </div>

			</div>
		  </div>
		</div>
		</div>

<?php endif; ?>
	  </div>
	  <div class="bot-bg-page">&nbsp;</div>
	</div>
	
<?php get_footer(); ?>

