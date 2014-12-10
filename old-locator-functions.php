<?php /*			
			function compare_distance(l1, l2) {
				return (l1.distance - l2.distance); 
			}
			
  
			
		    function add_new_dealer_marker(){
		    	if (cur_marker_seek == all_dealer_info.length) {
		    		var $t_ar = all_dealer_info.slice(0);
		    		$t_ar.sort(compare_distance);
		    		var $shown = 0;
		    		$('#dealers_list').html('');
		    		for(i in $t_ar) {
		    			$('.locator-debug').append('cur: '+ i + ' <br/ >');
		    			if ($shown >= <?php echo intval(get_option('dealers_search_count')); ?>) break;
		    			$('.locator-debug').append('distance: '+ $t_ar[i].distance + ' <br/ >');
		    			if ($t_ar[i].distance > <?php echo intval(get_option('dealers_search_distance')) * 1000; ?>) break; 
			    			if ($t_ar[i].country == $('#dealer_search_form select').val()) {
			    				$($t_ar[i].html).css('display','none').appendTo($('#dealers_list'))
			    					.find('.distance').html(Math.round($t_ar[i].distance / 1000)  + 'km')
			    					.end()
			    					.show(500);
			    				$shown++;
			    			}
		    		}
		    		$('.locator-debug').append('Shown: '+ $shown + ' <br/ >');
		    		if ($shown < 1) {
		    			$('#dealers_list').html('<strong> <?php echo get_option('dealers_not_found_'. strtolower($_SESSION['last_lang'])); ?> </strong>');
		    		}
		    		
		    		return;
		    	}
		    	var $cur_dlr = all_dealer_info[cur_marker_seek];
	        	geocoder.geocode( { 'address': $cur_dlr.address } , function( results, status ) {
				      if (status == google.maps.GeocoderStatus.OK) {
				      	geo_local_markers[geo_local_markers.length] = new google.maps.Marker({
			            	animation: google.maps.Animation.DROP,
			            	//map: $loc_map,
			            	map: null,
			            	position: results[0].geometry.location,
			            	title: all_dealer_info[cur_marker_seek].name
			        	});
			        	all_dealer_info[cur_marker_seek].distance = google.maps.geometry.spherical.computeDistanceBetween(
		        			search_marker.getPosition(),
		        			results[0].geometry.location 
			        	);
			        	$.ajax({
			        	   type: "POST",
						   url: "<?php echo esc_js(home_url('/')); ?>",
						   data: "caller=ajax&page=dealer_search&dealer_ID="+all_dealer_info[cur_marker_seek].ID,
						   success: function(res){
						     all_dealer_info[cur_marker_seek].html = res;
						     cur_marker_seek++;
				      	 	 add_new_dealer_marker();
						   },
						   error: function() {
						   	 cur_marker_seek++;
				      	 	 add_new_dealer_marker();
						   }
			        	});
				      } else {
				         cur_marker_seek++;
				      	 add_new_dealer_marker();
				      	 // alert('Geocode was not successful for the following reason: ' + status);
				      }
				     
			    });
		    }
		    
		    function rebuild_locator_map( $search ) {
		    	cur_marker_seek = 0;
		    	//for (i in geo_local_markers ) {
		    	//	geo_local_markers[i].setMap(null);
		    	//}
		    	geo_local_markers = [];

		        var country_addr = $('#dealer_search_form select').val() + ", " + $('#dealer_search_form select option:selected').text();
		        geocoder.geocode( { 'address': country_addr } , function( results, status ) {
			      if (status == google.maps.GeocoderStatus.OK) {
			      	//$loc_map.fitBounds(results[0].geometry.bounds);
			      } else {
			        // alert('Geocode was not successful for the following reason: ' + status);
			      }
			    });
			    
				user_marker = new google.maps.Marker({
		            //map: $loc_map,
		            map: null,
		            position: new google.maps.LatLng( cur_user_geo_loc.latitude, cur_user_geo_loc.longitude ),
		            title: 'Your location'
		        });
		    	geo_local_markers.push(user_marker);
		        
		        if ( $search ) {
		        	var addr 	= $('#dealer_search_form select option:selected').text()
		        				+ ' '
		        				+ $('#search_zip').val()
		        				+ ' '
		        				+ $('#search_address').val();
		        				
		        	if ( $('#search_zip').val().length < 2 ) {
		        		alert( '<?php echo esc_js( __( 'Please enter postal code','SwissPhone') ) ?>' );
		        		return false;
		        	}
		        	$('#dealers_list').html('<?php _e('Searching...', 'SwissPhone'); ?>');
		        	//alert(addr);			
		        	geocoder.geocode( { 'address': addr } , function( results, status ) {
				      if (status == google.maps.GeocoderStatus.OK) {
				      	search_marker = new google.maps.Marker({
			            	animation: google.maps.Animation.DROP,
			            	//map: $loc_map,
			            	map: null,
			            	position: results[0].geometry.location,
			            	title: 'Search location'
			        	});
			        	geo_local_markers.push(search_marker);
			        	add_new_dealer_marker();
				      } else {
				        // alert('Geocode was not successful for the following reason: ' + status);
				      }
			    	});
			    	
		        } else {
		        	search_marker	=	user_marker;
		        	add_new_dealer_marker();
		        }
		    	return false;
		    }
		    
		    $(document).ready(function($){
		       	geocoder = new google.maps.Geocoder();
				if (geocoder) {
					var opt =  {
				  		zoom: 5,
				  		center: new google.maps.LatLng( cur_user_geo_loc.latitude, cur_user_geo_loc.longitude ),
				  		mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					//$loc_map = new google.maps.Map( document.getElementById('dealer_locator_canvas'), opt );
					//rebuild_locator_map(false);
			    }
			});
	*/		