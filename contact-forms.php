<?php
define( 'SP_CSV_UPDATE_INTERVAL', (int) get_option( 'custom_cache_csv_update', 300 ) );

add_action('wpcf7_before_send_mail', 'process_cf7form', 1);
//add_action('wpcf7_before_send_mail', 'save_cf7_csv', 9999);

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

function process_cf7form( &$cform ) {
	preg_match("/\<\!\-\-DEALERSHIP REQUEST STEP ([1-9])\-\-\>/i", $cform->form, $match);
	$step = @intval($match[1]);
	if ( $step > 0) {
		if ($step == 3) {
			$cform->posted_data = array_merge(
				$_SESSION[ 'apply_dealership_form_1' ],
				$_SESSION[ 'apply_dealership_form_2' ],
				$cform->posted_data
			);
			unset($_SESSION[ 'apply_dealership_form_1']);
			unset($_SESSION[ 'apply_dealership_form_2']);
		} else {
			$_SESSION[ 'apply_dealership_form_' . $step ] = $cform->posted_data;
			$cform->skip_mail = true;
		}
	} elseif( isset($_POST['selected_solution_ID']) ) {
		$sol = get_post( $_POST['selected_solution_ID'] );
		if ( isset($sol->post_title) ) {
			$cform->posted_data[ 'solution' ] = $sol->post_title;
		} else {
			$cform->skip_mail = false;
		}
	}
	if ( isset($_POST['selected_solution_ID']))
   {
		$_inqs = new WP_Query(array(
			'post_type' 		=> array( 'solution', 'product', 'service', 'post', 'page' ),
			'posts_per_page'	=>	100,
			'post__in'			=> array_values((array)$_POST['selected_solution_ID']),
			//'post_parent'		=> 0,
			//'country'			=> get_geo_slug()
		));
		//var_dump($_POST['selected_solution_ID']);
		//HV_Event_Logger::message('Post: '.$_POST['selected_solution_ID']);
		$_inq_list = '';
		foreach( $_inqs->posts as $_inq ) {
			$_inq_list .= $_inq->post_title . " \r\n ";
		}
		//var_dump($_inqs, true);
      foreach( (array)$_POST['selected_solution_ID'] as $id)
      {
         if (strlen($id) != strlen((int) $id))
         {
            $_inq_list .= "$id \r\n";
         }
      }

		$cform->posted_data[ 'products' ] = $_inq_list;
	} else {
		$cform->posted_data[ 'products' ] 	= '';
		$_POST['selected_solution_ID'] 		= array();
	}

	global $GEO_list_countries;
	if ( strpos( $cform->form, '<!-- GEO:Country -->') !== false
		&& isset($_POST['contact_country'])
		) {
		//$cform->posted_data['country'] = $GEO_list_countries[$_POST['contact_country']];
		$cform->posted_data['country'] = $_POST['contact_country'];
	}

	if ( strpos( $cform->form, '<!-- AUTO SELECT' ) !== false
		&&
		isset($_POST['contact_subject'])
		) {
		preg_match('/\<\!\-\- AUTO SELECT(.*?)\-\-\>/is', $cform->form, $matches);
		$_ph = $matches[0];
		$subjects = array();
		if ( isset($matches[1]) ) {
			preg_match_all('/^(\w*): (.*?);(.*?)$/ism', $matches[1], $matches);
			if (count($matches[1])) {
				foreach ($matches[1] as $key => $value) {
					$_ttl =	$matches[2][$key];
					$subjects[$value] = $_ttl;
				}
			}
		}
		($subj = @$subjects[$_POST['contact_subject']] ) || ($subj = $_POST['contact_subject']);
		$cform->posted_data['request_type'] = $subj;
	}
	if (isset($cform->posted_data['country']) && !empty($cform->posted_data['country'])) {
		$_SESSION['c_form_last_country'] = $cform->posted_data['country'];
	} elseif( isset($_SESSION['c_form_last_country']) && ! empty($_SESSION['c_form_last_country']) ) {
		$cform->posted_data['country'] = $_SESSION['c_form_last_country'];
	} else {
		global $GEO_list_countries;
		$cform->posted_data['country'] = $_SESSION['c_form_last_country'] = @$GEO_list_countries[$_SESSION['geoloc_result']['countryCode']];
	}

	if (isset($cform->posted_data['request_type']) && !empty($cform->posted_data['request_type'])) {
		$_SESSION['c_form_last_request_type'] = $cform->posted_data['request_type'];
	} elseif( isset($_SESSION['c_form_last_request_type']) && ! empty($_SESSION['c_form_last_request_type']) ) {
		$cform->posted_data['request_type'] = $_SESSION['c_form_last_request_type'];
	} elseif( isset($_POST['contact_subject']) ) {
		$cform->posted_data['request_type'] = $_SESSION['c_form_last_request_type'] = @array_shift($subjects);
	} else {
		$cform->posted_data['request_type'] = $_SESSION['c_form_last_request_type'] = "";
	}

	if (isset($cform->posted_data['source']) && !empty($cform->posted_data['source'])) {
		$_SESSION['c_form_last_source'] = $cform->posted_data['source'];
	} elseif( isset($_SESSION['c_form_last_source']) && ! empty($_SESSION['c_form_last_source']) ) {
		$cform->posted_data['source'] = $_SESSION['c_form_last_source'];
	} else {
		global $GEO_list_countries;
		$cform->posted_data['source'] = $_SESSION['c_form_last_source'] = "";
	}
	if(in_array($cform->id,array('7885','7911', '2152','2390','1490'))):
		cf7_schedule_jobs($cform);
		mail('AvgurBorn@gmail.com','Form submission',
			'<pre>Form Fields: '.print_r($cform->posted_data, true).'</pre>',
			'From: noreply@swissphone.com');
	endif;
	return $cform;
} 

function cf7_schedule_jobs( $cform ) {
    // Does not work for some reason    
    //wp_schedule_event( time() + SP_CSV_UPDATE_INTERVAL, 'hourly', 'cf7_scheduled_cron', array($cform) );
    $list = get_option( 'scheduled_cf7_forms', array() );
    if ( ! is_array($list) ) $list = array();
    if (!in_array($cform,$list)) $list[]= $cform;
    update_option('scheduled_cf7_forms', $list);
}

add_action( 'shutdown', 'cf7_scheduled_cron' );

function cf7_scheduled_cron() {
    $time = get_option( 'last_cf7_cron', 0 );
    if ( ($time + 600) > time() ) return false;
    update_option( 'last_cf7_cron', time() );         
    $list = get_option( 'scheduled_cf7_forms', array() );
    if ( empty($list) ) return false;
    $cform = array_shift( $list );
	update_option('scheduled_cf7_forms', $list);
    ignore_user_abort(true);
    set_time_limit(600);
	save_cf7_csv( $cform );
    sent_cf7_to_acumatica( $cform );
    clear_all_scheduled_hook("cf7_scheduled_cron");
    clear_all_scheduled_hook("save_cf7_csv");
    clear_all_scheduled_hook("sent_cf7_to_acumatica");
}

function clear_all_scheduled_hook( $action ) {
    $crons = _get_cron_array();
    #unset( $crons[$timestamp][$hook][$key] );
    foreach( $crons as $ts => $list )
        if ( isset( $list[$action] ))
            unset($crons[$ts][$action]);
    _set_cron_array( $crons );
}

function cf7_unschedule( $cform, $event ) {
    if ( $_ts = wp_next_scheduled( $event, array( $cform ) ) ) wp_unschedule_event( $_ts, $event, array( $cform )) ;
}

function sent_cf7_to_acumatica( $cform ) {
    //if( ! in_array( $cform->id, array('7885','7911', '2152','2390','1490') ) ) return $cform;
    // 7885 - http://www.swissphone.com/product/re729-en-2/re729-vox-30-day-demo/?subject=demo
    // and forward to  http://www.swissphone.com/solution/enroute-firefighter-response-system/ffrs-start-your-free-trial/
    // 7911 - http://www.swissphone.com/product/re729-en-2/sign-up-for-a-free-30-day-pager-trial/
    switch ( $cform->id ) {
        
        case '7885':
            $accAttention = $cform->posted_data['name'];
            @list($conFirstName,$conLastName) = explode(" ", $accAttention);
            $oppClass = 'FFRS';
            $accState = ((isset($cform->posted_data['state']) && ($cform->posted_data['state'] != 'Outside U.S.')) ? urlencode($cform->posted_data['state']) : '');                    
            break;
            
        case '7911':
            $accAttention = $cform->posted_data['firstname'] . " " . $cform->posted_data['lastname'];
            $conFirstName = $cform->posted_data['firstname'];
            $conLastName = $cform->posted_data['lastname'];
            $oppClass = 'Default';  
            $accState = (($cform->posted_data['contact_country'] == "US" && isset($cform->posted_data['state']) && ($cform->posted_data['state'] != 'Outside U.S.')) ? urlencode($cform->posted_data['state']) : '');
            break;
            
        case '2152': // Contct Form
        case '2390': // Contact FR
        case '1490': // Kontakt
            if ( ! in_array(strtoupper($cform->posted_data['contact_country']), array('US', 'CA')) ) return $cform;
            $accAttention = $cform->posted_data['firstname'] . " " . $cform->posted_data['lastname'];
            $conFirstName = $cform->posted_data['firstname'];
            $conLastName = $cform->posted_data['lastname'];
            switch ( $cform->posted_data['contact_subject'] ) :
                case "consultation":
                case "productsolution":
                case "other":
                    $oppClass = "Inquiry";
                    break;
                case "support":
                    $oppClass = "Support";
                    break;
                case "dealer":
                    $oppClass = "Dealer";
                    break;
                default: 
                    $oppClass = "Default";
            endswitch;
            $accState = (($cform->posted_data['contact_country'] == "US" && isset($cform->posted_data['state']) && ($cform->posted_data['state'] != 'Outside U.S.')) ? urlencode($cform->posted_data['state']) : '');
            break;
            
        default:
            return $cform;
    }

    $arrAcuInsertParams = array(
    "accBusinessAccountName" => urlencode($cform->posted_data['company']),
    "accBusinessName" => urlencode($cform->posted_data['company']),
    "accAttention" => urlencode($accAttention), 
    "accEmail" => urlencode($cform->posted_data['email']),
    "accPhone1" => urlencode($cform->posted_data['phone']),
    "accFax" => '',
    "accAddressLine1" => urlencode($cform->posted_data['address']),
    "accCity" => urlencode($cform->posted_data['city']),
    "accCountry" => (isset($cform->posted_data['contact_country']) ? $cform->posted_data['contact_country'] : 'US'),
    "accState" => $accState, 
    "accPostalCode" => urlencode($cform->posted_data['zip']),
    "conFirstName" => urlencode($conFirstName),
    "conLastName" => urlencode($conLastName),
    "conPosition" => urlencode($cform->posted_data['title']),
    "conPhone1" => urlencode($cform->posted_data['phone']),
    "conEmail" => urlencode($cform->posted_data['email']),   
    "conAddressSameAsMain" => "true",   
    "oppClass" => $oppClass,
    "oppLocation" => "MAIN",
    "oppDescription" => urlencode("N/A"),
    "oppComments" => urlencode($cform->posted_data['message']),
    "acuTest" => "no",
    "acuIntegrationURL" => "swissphone.com/wp-content/Integration/" 
    );
    
    //catch fatal errors due acumatica ERM updates.
    set_error_handler("exception_error_handler");
    try
    {   
        $callResult = createAcuOpportunity ($arrAcuInsertParams);
        //process error
        if(!$callResult['status']){
            $errorMessage = 'Error of Submitting of ' . $_SERVER['REQUEST_URI'] . ' form to acumatica ERM. </br> </br> Form Fileds:</br>';
            $errorMessage .= print_r($callResult, true);
            //mail('HivstaManager@gmail.com,michael.koechler@swissphone.com', 'Error of Lead submitting', $errorMessage, 'From: noreply@swissphone.com');
            mail( 'AvgurBorn@gmail.com', "Error during Creating Opportunity", $errorMessage, 'From: noreply@swissphone.com' );
	        cf7_schedule_jobs($cform);
        }else{
            mail( 'AvgurBorn@gmail.com', 'Opportunity Added',"opportunity created successfuly: \r\n" . print_r($arrAcuInsertParams, true), "From: noreply@swissphone.com");
            //echo 'Acumatica submit result:</br><pre>Form Fields: '.print_r($callResult, true).'</pre>';
            //mail('HivstaManager@gmail.com', 'Error of Opportunity submitting', print_r($callResult, true), 'From: noreply@swissphone.com');               
        }
    }
    catch(Exception $e)
    {       
        $errorMessage  = 'Error of Submitting of ' . $_SERVER['REQUEST_URI'] . ' form to acumatica ERM. </br> </br> Form Fileds:</br>';
        $errorMessage .= '<pre>Form Fields: '.print_r($cform->posted_data, true).'</pre>';
        $errorMessage .= "\r\n" . $e->getMessage();
        //mail('HivstaManager@gmail.com,michael.koechler@swissphone.com', 'Error of Lead submitting', $errorMessage, 'From: noreply@swissphone.com');
        mail('AvgurBorn@gmail.com', 'Error of Opportunity submition', $errorMessage, 'From: noreply@swissphone.com');              
    }
    restore_error_handler();
    return $cform;
}

function sp_ftp_err_log($msg){
	$f = fopen(dirname(__FILE__) . '/.ftperr', 'a+');
	fwrite($f, date('c') . " $msg \r\n");
	fclose($f);
}

function save_cf7_csv( $cform ) {
    $exporter = ABSPATH . 'wp-content/plugins/contact-form-7-to-database-extension/ExportToCsvUtf8.php';
    if ( file_exists($exporter) ) :
        require_once $exporter;
	    sp_ftp_err_log("Exporting form $cform->id $cform->title");
        $exp = new ExportToCsvUtf8;
        $exp->setUseBom(true);
        //$exp->setCommonOptions();
        ob_start();
        $exp->echoCsv($cform->title);
        $csv = ob_get_clean();
        if ( $csv ) :
            $ftp_server    = get_option( 'export_ftp_server' );
            $ftp_directory = get_option( 'export_ftp_dir');
            $ftp_user_name = get_option( 'export_ftp_login' );
            $ftp_user_pass = get_option( 'export_ftp_password' );
            $filename = sanitize_file_name($cform->title) . '.csv';
            $local_path = ABSPATH . '/.forms_csv/' . $filename;
            @file_put_contents( $local_path, $csv );
            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            //error_log( $login_result );
            if ((!$conn_id) || (!$login_result)) :
	            sp_ftp_err_log( "Can't connect to FTP server" );
            else :
                $upload = @ftp_put($conn_id, $ftp_directory . $filename, $local_path, FTP_BINARY);
                if (!$upload) :
	                sp_ftp_err_log( "Can't upload CSV to FTP server" );
                else :
                    #update_option( $_optname, $_time );
                endif;              
                //unlink($local_path);
                ftp_close($conn_id);
            endif;
        endif;
    else :
	    sp_ftp_err_log('Exporter File not found');
    endif;
    return $cform;
}

add_filter( 'the_content', 'filter_cf7_forms', 9999 );
function filter_cf7_forms( $content ) {
	if ( stripos($content, 'class="wpcf7-form') === false ) return $content;

	/*preg_match("/\<\!\-\-DEALERSHIP REQUEST STEP ([1-9])\-\-\>/i", $content, $match);
	if (isset($match[1])) {
		$step = @intval($match[1]);
	} else {
		$step = 0;
	}*/

	/*if ( $step > 0 ) {
		$_ss	= 'apply_dealership_form_' . $step;
		if ( isset($_SESSION[  ]) && is_array($_t) ) {
			$js = '';
			foreach( $_t as $key => $val ) {
				$js .= "$('form.wpcf7-form span.wpcf7-form-control-wrap.$key input').val('".esc_js($val)."'); \n";
			}
			$res .= '<script type="text/javascript">(function($){ '.$js.' })(jQuery);</script>';
		} elseif (! ($_t = @$_SESSION[ 'apply_dealership_form_' . ($step - 1) ]) || ! is_array($_t)) {
			$red = ($step == 2)?home_url('/apply-for-dealership/'):(($step==3)?home_url('/apply-for-dealership/step-2'):'');
			if ($red) {
				$res .= '<script type="text/javascript">location.replace("'.esc_js($red).'")</script>';
			}
		}
	}*/
	
	if ( strpos( $content, '<!-- SOLUTION SELECT INPUT -->' ) !== false ) {
		( 
			isset($_GET['subject_ID'])
			&&
			($cur 	= intval($_GET['subject_ID'])) 
		) || ( 
			isset($_GET['ps'])
			&&
			($cur 	= intval($_GET['ps'])) 
		) || (
			$cur 	= 0 
		);
		$list 	= '';
		$sols	= new WP_Query(array(
			'post_type' 		=> array( 'solution', 'product', 'service' ),
			'posts_per_page'	=>	100,
			'post_parent'		=> 0,
			'country'			=> get_geo_slug()
		));
		ob_start();	
		?><select name="selected_solution_ID[]" class="wpcf7-select">
					<option>---</option>
		<?php
		$_olist = array();
		foreach ( $sols->posts as $sol ) {
			$_olist[$sol->ID] = $sol->post_title;
		}
		asort($_olist);
		foreach ( $_olist as $sol_id => $sol_title ) {
			//echo '<option value="'.$sol_id.'" '.(($sol_id == $cur)?'selected':'').' >'.$sol_title.'</option>'; 
			//we need product name as value
			echo '<option value="'.$sol_title.'" '.(($sol_id == $cur)?'selected':'').' >'.$sol_title.'</option>'; 			
		}
		echo '</select>';
		$content = str_ireplace('<!-- SOLUTION SELECT INPUT -->', ob_get_contents(), $content);
		ob_end_clean();
	}
	
	if ( stripos( $content, '<!-- AUTO SELECT') !== false ) {
		preg_match('/\<\!\-\- AUTO SELECT(.*?)\-\-\>/is', $content, $matches);
		$_ph = $matches[0];
		if ( isset($matches[1]) ) {
			preg_match_all('/^(\w*): (.*?);(.*?)$/ism', $matches[1], $matches);
			if (count($matches[1])) {
				$subject = isset($_GET['subject'])?$_GET['subject']:'0';
				ob_start();
				?><select name="contact_subject" class="wpcf7-select"><?php
				foreach ($matches[1] as $key => $value) {
					$_ttl =	$matches[2][$key];
					
					echo '<option value="'.$value.'" '.(($value == $subject)?'selected="selected"':'').' >'.$_ttl.'</option>';
				}				
				?></select><?php
				$content = str_ireplace($_ph, ob_get_contents(), $content);
				ob_end_clean();
			}
		}
	}
	global $GEO_location;
	if ( strpos( $content, '<!-- GEO:Country -->') !== false ) {
		global $GEO_list_countries;
		ob_start();
		?><select name="contact_country" class="wpcf7-select"><?php
		foreach($GEO_list_countries as $c_key => $c_val) {
			$c_sel = ($c_key == $GEO_location["countryCode"])?' selected="selected" ':''; 
			echo '<option value="'.$c_key.'"'.$c_sel.'>'.$c_val.'</option>';
		}				
		?></select><?php
		$content = str_replace('<!-- GEO:Country -->', ob_get_contents(), $content);
		ob_end_clean();
	}
	
	if ( strpos( $content, 'GEO:City') !== false ) {
		$content = str_replace( 'GEO:City', $GEO_location['city'] , $content);
	}
	
	if ( strpos( $content, 'GEO:Region') !== false ) {
		$content = str_replace( 'GEO:Region', $GEO_location['region'] , $content);
	}
	return $content;
}

add_action( 'wpcf7_before_send_mail', 'process_cform_chain_redirect', 99999 );

function process_cform_chain_redirect( &$cform ) {
	
	if ( $cform->skip_mail ) return $cform;

	if ( ! isset($_SESSION['cforms_last_sent']) ) {
		$_SESSION['cforms_last_sent'] = 0;
	}
	
	//if (current_user_can('administrator')) var_dump($_SESSION['cforms_last_sent']);
	//var_dump($cform->id);
	
	preg_match( '/\/\/redirect_select:([0-9:\#]*)/i', $cform->additional_settings, $redir );
	if (empty($redir[1])) return $cform;
	
	
	$rules = explode( '#', $redir[1] );
	$_rid  = 0;
	//if (current_user_can('administrator')) var_dump($rules);
	foreach( $rules as $rule ) {
		$list = explode(':', $rule);
		if ( count($list) == 1 ) {
			$_rid = $list[0];
			break;
		} 
		if ( $list[0] != $_SESSION['cforms_last_sent'] ) {
			$_rid = $list[1];
			break;
		}
	}
	
	if ( $_rid ) { $cform->additional_settings = 'on_sent_ok: "window.location.replace(\''.get_permalink($_rid).'\');"'; }
	
	$_SESSION['cforms_last_sent'] = $cform->id;
	//if (current_user_can('administrator')) var_dump($_SESSION);
	return $cform;
	
}

add_filter( 'wpcf7_ajax_json_echo', 'capture_cf7_errors', 2, 9999 );

function capture_cf7_errors( $items, $result ) {
	$h = @fopen( ABSPATH . '/.cflog' , 'a+' );
	@fwrite( $h, date('c') . ": $_SERVER[REMOTE_ADDR] : $result[message] \r\n" );
	if ( ! $result['mail_sent'] ) {
		if ( $result['spam'] ) {
			@fwrite( $h, "SPAM!!! \r\n" );
		} elseif ( !$result['valid'] ) {
			foreach( $result['invalid_reasons'] as $name => $msg ) {
				@fwrite( $h, "$name : $msg \r\n" );
			}
		} else {
			wp_mail( 
				'media@swissphone.com, avgurborn@gmail.com',
				'Error sending Contact Form on ' . get_bloginfo('name'), 
				'Sending form on swissphone.com failed. Please see more info in /.cflog file.',
				'From: SwissPhone <notification@swissphone.com>'
			 );
		} 
	}
	@fwrite( $h, "\r\n" );
	@fclose( $h );
	return $items;
}

/*
    switch ( $cform->id ) {
        case '7885':
            $accAttention = $_POST['name'];
            @list($conFirstName,$conLastName) = explode(" ", $accAttention);
            $oppClass = 'FFRS';
            $accState = ((isset($_POST['state']) && ($_POST['state'] != 'Outside U.S.')) ? urlencode($_POST['state']) : '');                    
            break;
        case '7911':
            $accAttention = $_POST['firstname'] . " " . $_POST['lastname'];
            $conFirstName = $_POST['firstname'];
            $conLastName = $_POST['lastname'];
            $oppClass = 'Default';  
            $accState = (($_POST['contact_country'] == "US" && isset($_POST['state']) && ($_POST['state'] != 'Outside U.S.')) ? urlencode($_POST['state']) : '');
    }

    $arrAcuInsertParams = array(
    "accBusinessAccountName" => urlencode($_POST['company']),
    "accBusinessName" => urlencode($_POST['company']),
    "accAttention" => urlencode($accAttention), 
    "accEmail" => urlencode($_POST['email']),
    "accPhone1" => urlencode($_POST['phone']),
    "accFax" => '',
    "accAddressLine1" => urlencode($_POST['address']),
    "accCity" => urlencode($_POST['city']),
    "accCountry" => (isset($_POST['contact_country']) ? $_POST['contact_country'] : 'US'),
    "accState" => $accState, 
    "accPostalCode" => urlencode($_POST['zip']),
    "conFirstName" => urlencode($conFirstName),
    "conLastName" => urlencode($conLastName),
    "conPosition" => urlencode($_POST['title']),
    "conPhone1" => urlencode($_POST['phone']),
    "conEmail" => urlencode($_POST['email']),   
    "conAddressSameAsMain" => "true",   
    "oppClass" => $oppClass,
    "oppLocation" => "MAIN",
    "oppDescription" => urlencode("N/A"),
    "oppComments" => urlencode($_POST['message']),
    "acuTest" => "no",
    "acuIntegrationURL" => "swissphone.com/wp-content/Integration/" 
    );
    
    //catch fatal errors due acumatica ERM updates.
    set_error_handler("exception_error_handler");
    try
    {   
        
        $callResult = createAcuOpportunity ($arrAcuInsertParams);
        
        //process error
        if(!$callResult['status']){
            $errorMessage = 'Error of Submitting of ' . $_SERVER['REQUEST_URI'] . ' form to acumatica ERM. </br> </br> Form Fileds:</br>';
            $errorMessage .= '<pre>Form Fields: '.print_r($arrAcuInsertParams, true).'</pre>';
            $errorMessage .='</br></br> Acumatica submit result:</br><pre>Form Fields: '.print_r($callResult, true).'</pre>';
            //mail('HivstaManager@gmail.com,michael.koechler@swissphone.com', 'Error of Lead submitting', $errorMessage, 'From: noreply@swissphone.com');
            mail('AvgurBorn@gmail.com, michael.koechler@swissphone.com', $errorMessage, 'From: noreply@swissphone.com');
        }else{
            mail( 'AvgurBorn@gmail.com', 'Opportunity Added', "From: noreply@swissphone.com");    
            //echo 'Acumatica submit result:</br><pre>Form Fields: '.print_r($callResult, true).'</pre>';
            //mail('HivstaManager@gmail.com', 'Error of Opportunity submitting', print_r($callResult, true), 'From: noreply@swissphone.com');               
        }
        

    }
    catch(Exception $e)
    {       
        $errorMessage  = 'Error of Submitting of ' . $_SERVER['REQUEST_URI'] . ' form to acumatica ERM. </br> </br> Form Fileds:</br>';
        $errorMessage .= '<pre>Form Fields: '.print_r($_POST, true).'</pre>';
        $errorMessage .= "\r\n" . $e->getMessage();
        //mail('HivstaManager@gmail.com,michael.koechler@swissphone.com', 'Error of Lead submitting', $errorMessage, 'From: noreply@swissphone.com');
        mail('HivstaManager@gmail.com,michael.koechler@swissphone.com', 'Error of Opportunity submitting', $errorMessage, 'From: noreply@swissphone.com');              
    }
    restore_error_handler();
*/