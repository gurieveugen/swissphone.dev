<?php

add_action( 'admin_menu', 'admin_init_import_dealers');
add_action( 'admin_enqueue_scripts', 'dealer_import_scripts' );
function dealer_import_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-progressbar', get_bloginfo('template_url') . '/js/jquery-ui-progressbar.js' );
	wp_enqueue_style( 'jquery-ui-lightness', get_bloginfo('template_url') . '/css/ui-lightness/jquery-custom.css' );
}
function admin_init_import_dealers() {
	add_management_page( 
		__('Dealers', 'SwissPhone'), 
		__('Dealers', 'SwissPhone'), 
		'manage_options', 
		'dealers_import',
		'dlr_import_admin'
		);
}


function dlr_import_admin() {
	global $sitepress;
	if ( isset($_POST['file_upload']) && wp_verify_nonce($_POST['wp_admin_xls_upload'], 'wp_admin_xls_upload')) {
		$fname = dirname(__FILE__). '/' . $_FILES['xls_file']['name'];
		move_uploaded_file($_FILES['xls_file']['tmp_name'], $fname);
		include dirname(__FILE__).'/excel/reader.php';
		$fr = new Spreadsheet_Excel_Reader();
		$fr->setOutputEncoding('utf-8');
		$fr->setRowColOffset(0);
		$fr->read( $fname );
		$data = $fr->sheets[0]['cells'];
		unlink( $fname );
		echo '<div class="wrap">';
		echo '<span>'. __('Importing file', 'SwissPhone') . ' ' . $_FILES['xls_file']['name'] . '</span><br />'; 
		$fields = array();
		/* Why headers as keys?! */
		$header = array(
			'id' 	=> false,
			'name' 	=> false,
			'country' => false,
			'city'	=> false,
			'zipcode' => false,
			'address' => false,
			'phone'	=> false,
			'fax' 	=> false,
			'web' 	=> false,
			'email' => false,
			'lang'	=> false,
		);
		foreach( $data[0] as $key=>$fld ) {
			$fields[strtolower($fld)] =  $key;
		}
		unset($data[0]);
		foreach( $data as $row ) {
			$trow = array();
			//echo '<span>'. __('Reading data...', 'SwissPhone') . '</span><br />'; 
			foreach ($header as $fln => $val) {
				$trow[$fln] = utf8_encode($row[$fields[$fln]]);
				//echo "$fln : " .  $trow[$fln] . '; '; 
			}
			//echo "<br />";
			$pst = array(
				'post_type'	 => 'dealer',
				'post_title' => $trow['name']
			);
			$lng = strtolower($trow['lang']);
			if ( $lng && in_array($lng, array( 'en', 'fr', 'de') )) {
				$pst['icl_post_language'] = $lng;
			}
			
			$cid = false;
			
			if (@$trow['id'] && ($ps = get_post($trow['id'])) && (@$ps->post_type == "dealer") ) {
				$pst['ID'] = $trow['id'];
				$pst['post_title'] = esc_sql($pst['post_title']);
				wp_update_post($pst);
				$cid = $pst['ID'];
				do_action( 'save_post', $cid, $pst ); // ICL hack.
				//echo '<span>'. __('Dealer updated ', 'SwissPhone') ."(ID: $cid)" .'</span><br />'; 
			} elseif ($trow['name']) {
				$pst['post_status'] = 'publish';
				$cid = wp_insert_post($pst);
				do_action( 'save_post', $cid, $pst ); // ICL hack.
				//echo '<span>'. __('Dealer added ', 'SwissPhone') ."(ID: $cid)" .'</span><br />';
			}
			if ($cid && ! is_object($cid)) {
				unset($trow['id']);
				unset($trow['name']);
				foreach( $trow as $key=>$val ) {
					update_post_meta($cid, $key, $val);
				}
			}
		}
		echo '<span>'. __('Finished', 'SwissPhone') . '</span></div>'; 
	}
	if ( 'dealer' == @$_POST['form_sent'] && wp_verify_nonce(@$_POST['dealerform_options'], 'dealerform_options')) {
		update_option('dealers_search_distance', $_POST['distance']);
		update_option('dealers_search_count', $_POST['count']);
		update_option('dealers_not_found_en', stripslashes($_POST['search_not_found_en']));
		update_option('dealers_not_found_de', stripslashes($_POST['search_not_found_de']));
		update_option('dealers_not_found_fr', stripslashes($_POST['search_not_found_fr']));
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'All Dealers', 'SwissPhone' ); ?></h2>
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Country</th>
					<th>City</th>
					<th>ZIP</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Fax</th>
					<th>Web</th>
					<th>Email</th>
					<th>Portfolio</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$dealer_list = array();
		foreach (get_posts(array(
				'post_type'	=> 'dealer',
				'numberposts'	=> -1
			)) as $dlr ) {
			$dealer_list[] = $dlr->ID; 	
			$cust = get_post_custom( $dlr->ID );
			$address_list[] = esc_js($cust['country'][0] .  ', ' . $cust['city'][0] . ' ' . $cust['address'][0] . ', ' . $cust['zipcode'][0]);
			echo '
			<tr>
				<td>'.$dlr->ID.'</td>
				<td>'.$dlr->post_title.'</td>
				<td>'.$cust['country'][0].'</td>
				<td>'.$cust['city'][0].'</td>
				<td>'.$cust['zipcode'][0].'</td>
				<td>'.$cust['address'][0].'</td>
				<td>'.$cust['phone'][0].'</td>
				<td>'.$cust['fax'][0].'</td>
				<td>'.$cust['web'][0].'</td>
				<td>'.$cust['email'][0].'</td>
				<td>'.$cust['portfolio'][0].'</td>
			</tr>
			';
		}
	?>
		</tbody>
	</table>
	<form action="" method="post" enctype="multipart/form-data" >
		<input type="file" name="xls_file" />
		<input type="hidden" name="file_upload"  value="xls" />
		<?php wp_nonce_field( 'wp_admin_xls_upload', 'wp_admin_xls_upload' );  ?>
		<input type="submit" value="<?php _e('Upload', 'SwissPhone'); ?>" />
	</form>
	<div id="index_rebuild_dialog" title="Building Search Index">
		<div id="index_progress_bar"></div>
	</div>
	<h2>Delaer locator form</h2>
	<form action="" method="post">
		<?php wp_nonce_field( 'dealerform_options', 'dealerform_options' );  ?>
		<input type="hidden" name="form_sent" value="dealer" />
		<table>
			<tr>
				<td>Distance to search </td>
				<td><input type="text" name="distance" value="<?php echo esc_attr(get_option('dealers_search_distance')); ?>" /></td>
			</tr>
			<tr>
				<td>Search results to show </td>
				<td><input type="text" name="count" value="<?php echo esc_attr(get_option('dealers_search_count')); ?>" /></td>
			</tr>
			<tr>
				<td>"Not found" message (en): </td>
				<td><textarea style="width: 400px" name="search_not_found_en" ><?php echo esc_textarea(get_option('dealers_not_found_en')); ?></textarea></td>
			</tr>
			<tr>
				<td>"Not found" message (de): </td>
				<td><textarea style="width: 400px" name="search_not_found_de" ><?php echo esc_textarea(get_option('dealers_not_found_de')); ?></textarea></td>
			</tr>			
			<tr>
				<td>"Not found" message (fr): </td>
				<td><textarea style="width: 400px" name="search_not_found_fr" ><?php echo esc_textarea(get_option('dealers_not_found_fr')); ?></textarea></td>
			</tr>			
		</table>
		<input type="submit" value="Save" class="button" /><br />
		<input type="button" value="Rebuild Search Index" class="button" onclick="do_rebuild_index();" />
	</form>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<script type="text/javascript">
		var all_dealers_id_list = [ <?php echo implode( ',' , $dealer_list ) ?> ];
		var all_dealers_addr_list = ['<?php echo implode( "','" , $address_list ) ?>'];
		var curr_dealer_pointer = 0;
		var gm_geocoder = 0;
		var curr_progress_value = 0;
		var inc_progress_value = 100.0 / all_dealers_addr_list.length;
		var last_retries	= 0;
		
		function do_rebuild_index() {
			gm_geocoder = new google.maps.Geocoder();
			if (gm_geocoder) {
				jQuery('#index_rebuild_dialog').dialog();
				jQuery('#index_progress_bar').progressbar({ value: 0 });
				rebuild_next_dealer();
		    }
		}
		
		function finish_index_rebuild() {
			jQuery('#index_rebuild_dialog').dialog( "close" );
		}
		
		function rebuild_next_dealer() {
			if ( curr_dealer_pointer >= all_dealers_id_list.length ) {
				return finish_index_rebuild();
			}
			var _id = all_dealers_id_list[curr_dealer_pointer];
			var _addr = all_dealers_addr_list[curr_dealer_pointer];
			gm_geocoder.geocode( { 'address': _addr } , function( results, status ) {
		    	if (status == google.maps.GeocoderStatus.OK) {
		      		var loc = results[0].geometry.location;
		      		jQuery.ajax({
		        	   type: "POST",
					   url: "<?php echo esc_js(home_url('/')); ?>",
					   data: "caller=locator&dealer_id="+_id+"&lat=" + loc.lat() + "&lng=" + loc.lng(),
					   success: function(){
					   	curr_progress_value += inc_progress_value;
					   	jQuery('#index_progress_bar').progressbar( "value" , curr_progress_value );
					   }
		        	});
		        	last_retries = 0;
		        	curr_dealer_pointer++;
		    	} else {
		    		last_retries++;
		    	}
		    	if (last_retries > 10) { 
		    		curr_dealer_pointer++;
		    		last_retries = 0;
		    	}
		    	setTimeout( 'rebuild_next_dealer()', 100 * last_retries );
		    });
		}
		
	</script>
	
	</div>
<?php	
}
