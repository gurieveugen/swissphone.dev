<?php
add_action('wpcf7_before_send_mail', 'save_ffrs_csv', 9999);

function save_ffrs_csv( $cform ) {
	if($cform->id != 7885) return $cform;
	$exporter = ABSPATH . 'wp-content/plugins/contact-form-7-to-database-extension/ExportToCsvUtf8.php';
	if (!file_exists($exporter)) return $cform;
	require_once $exporter;
	$exp = new ExportToCsvUtf8;
	$exp->setUseBom(true);
	//$exp->setCommonOptions();
	ob_start();
	$exp->echoCsv($cform->title);
	$csv = ob_get_clean();
	if($csv):
		$ftp_server     = 'ftp.miydim.com';
		$ftp_directory  = '/';
		$ftp_user_name  = 'swissphone@miydim.com';
		$ftp_user_pass  = 'SP20!#';
		$filename       = sanitize_file_name($cform->title) . '.csv';
		$local_path     = ABSPATH . '/' . $filename;
		@file_put_contents($local_path, $csv);
		$conn_id        = ftp_connect($ftp_server);
		$login_result   = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		if ((!$conn_id) || (!$login_result)) :
			sp_ftp_err_log( "Can't connect to FTP server" );
		else :
			$upload = @ftp_put($conn_id, $ftp_directory . $filename, $local_path, FTP_BINARY);
			if (!$upload) :
				sp_ftp_err_log( "Can't upload CSV to FTP server" );
			else :
				#update_option( $_optname, $_time );
			endif;
			unlink($local_path);
			ftp_close($conn_id);
		endif;
	endif;
	return $cform;
}