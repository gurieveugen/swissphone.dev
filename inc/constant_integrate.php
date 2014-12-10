<?php
include "cc/class.cc.php";

function sync_with_cc( &$cform ){
	$cc = new cc( 'hivista_tester', 'HVST2011');
	preg_match("/\<\!\-\-CONSTANT CONTACT([^>]*)\-\-\>/i", $cform->form, $match);
	if (@$match[1]) {
		preg_match_all("/(email|Status|EmailType|FirstName|MiddleName|LastName|JobTitle|CompanyName|HomePhone|WorkPhone|Addr1|Addr2|Addr3|City|StateCode|StateName|CountryCode|CountryName|PostalCode|SubPostalCode|Note|CustomField1|CustomField2|CustomField3):\[([^\]]*)\]/i", $match[1], $matches);
		$email = '';
		$fld = array_combine($matches[1], $matches[2]);
		$data = array();
		foreach($fld as $fl => $val){
			if (strtolower($fl) == "email") {
				$email = $cform->posted_data[$val];
			} else {
				$data[$fl] = $cform->posted_data[$val];
			}
		}
		if ($email){
			$cc->create_contact($email, array(1,2), $data);
		}

	};
	//$cform->posted_data[ 'solution' ] = $sol->post_title;
	return $cform;
}

add_action('wpcf7_before_send_mail', 'sync_with_cc', 2);
