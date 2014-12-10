<?php
class Ip2Country
{
	//fields
	public $dir 	= 'db_ip/';
	public $loc_dir = 'db_loc/';
	public $result	= array();
	
	public $codes = array(
		'AF'=>'AFGHANISTAN',
		'AL'=>'ALBANIA',
		'DZ'=>'ALGERIA',
		'AS'=>'AMERICAN SAMOA',
		'AD'=>'ANDORRA',
		'AO'=>'ANGOLA',
		'AI'=>'ANGUILLA',
		'AQ'=>'ANTARCTICA',
		'AG'=>'ANTIGUA AND BARBUDA',
		'AR'=>'ARGENTINA',
		'AM'=>'ARMENIA',
		'AW'=>'ARUBA',
		'AC'=>'ASCENSION ISLAND',
		'AU'=>'AUSTRALIA',
		'AT'=>'AUSTRIA',
		'AZ'=>'AZERBAIJAN',
		'BS'=>'BAHAMAS',
		'BH'=>'BAHRAIN',
		'BD'=>'BANGLADESH',
		'BB'=>'BARBADOS',
		'BY'=>'BELARUS',
		'BE'=>'BELGIUM',
		'BZ'=>'BELIZE',
		'BJ'=>'BENIN',
		'BM'=>'BERMUDA',
		'BT'=>'BHUTAN',
		'BO'=>'BOLIVIA',
		'BA'=>'BOSNIA AND HERZEGOWINA',
		'BW'=>'BOTSWANA',
		'BV'=>'BOUVET ISLAND',
		'BR'=>'BRAZIL',
		'IO'=>'BRITISH INDIAN OCEAN TERRITORY',
		'BN'=>'BRUNEI DARUSSALAM',
		'BG'=>'BULGARIA',
		'BF'=>'BURKINA FASO',
		'BI'=>'BURUNDI',
		'KH'=>'CAMBODIA',
		'CM'=>'CAMEROON',
		'CA'=>'CANADA',
		'CV'=>'CAPE VERDE',
		'KY'=>'CAYMAN ISLANDS',
		'CF'=>'CENTRAL AFRICAN REPUBLIC',
		'TD'=>'CHAD',
		'CL'=>'CHILE',
		'CN'=>'CHINA',
		'CX'=>'CHRISTMAS ISLAND',
		'CC'=>'COCOS (KEELING) ISLANDS',
		'CO'=>'COLOMBIA',
		'KM'=>'COMOROS',
		'CD'=>'CONGO THE DEMOCRATIC REPUBLIC OF THE',
		'CG'=>'CONGO',
		'CK'=>'COOK ISLANDS',
		'CR'=>'COSTA RICA',
		'CI'=>'COTE D\'IVOIRE',
		'HR'=>'CROATIA',
		'CU'=>'CUBA',
		'CY'=>'CYPRUS',
		'CZ'=>'CZECH REPUBLIC',
		'DK'=>'DENMARK',
		'DJ'=>'DJIBOUTI',
		'DM'=>'DOMINICA',
		'DO'=>'DOMINICAN REPUBLIC',
		'TP'=>'EAST TIMOR',
		'EC'=>'ECUADOR',
		'EG'=>'EGYPT',
		'SV'=>'EL SALVADOR',
		'GQ'=>'EQUATORIAL GUINEA',
		'ER'=>'ERITREA',
		'EE'=>'ESTONIA',
		'ET'=>'ETHIOPIA',
		'EU'=>'EUROPEAN UNION',
		'FK'=>'FALKLAND ISLANDS',
		'FO'=>'FAROE ISLANDS',
		'FJ'=>'FIJI',
		'FI'=>'FINLAND',
		'FX'=>'FRANCE METRO',
		'FR'=>'FRANCE',
		'GF'=>'FRENCH GUIANA',
		'PF'=>'FRENCH POLYNESIA',
		'TF'=>'FRENCH SOUTHERN TERRITORIES',
		'GA'=>'GABON',
		'GM'=>'GAMBIA',
		'GE'=>'GEORGIA',
		'DE'=>'GERMANY',
		'GH'=>'GHANA',
		'GI'=>'GIBRALTAR',
		'GR'=>'GREECE',
		'GL'=>'GREENLAND',
		'GD'=>'GRENADA',
		'GP'=>'GUADELOUPE',
		'GU'=>'GUAM',
		'GT'=>'GUATEMALA',
		'GG'=>'GUERNSEY',
		'GN'=>'GUINEA',
		'GW'=>'GUINEA-BISSAU',
		'GY'=>'GUYANA',
		'HT'=>'HAITI',
		'HM'=>'HEARD AND MC DONALD ISLANDS',
		'VA'=>'VATICAN CITY STATE',
		'HN'=>'HONDURAS',
		'HK'=>'HONG KONG',
		'HU'=>'HUNGARY',
		'IS'=>'ICELAND',
		'IN'=>'INDIA',
		'ID'=>'INDONESIA',
		'IR'=>'IRAN',
		'IQ'=>'IRAQ',
		'IE'=>'IRELAND',
		'IM'=>'ISLE OF MAN',
		'IL'=>'ISRAEL',
		'IT'=>'ITALY',
		'JM'=>'JAMAICA',
		'JP'=>'JAPAN',
		'JE'=>'JERSEY',
		'JO'=>'JORDAN',
		'KZ'=>'KAZAKHSTAN',
		'KE'=>'KENYA',
		'KI'=>'KIRIBATI',
		'KP'=>'KOREA',
		'KR'=>'KOREA',
		'KW'=>'KUWAIT',
		'KG'=>'KYRGYZSTAN',
		'LA'=>'LAO',
		'LV'=>'LATVIA',
		'LB'=>'LEBANON',
		'LS'=>'LESOTHO',
		'LR'=>'LIBERIA',
		'LY'=>'LIBYAN ARAB JAMAHIRIYA',
		'LI'=>'LIECHTENSTEIN',
		'LT'=>'LITHUANIA',
		'LU'=>'LUXEMBOURG',
		'MO'=>'MACAU',
		'MK'=>'MACEDONIA',
		'MG'=>'MADAGASCAR',
		'MW'=>'MALAWI',
		'MY'=>'MALAYSIA',
		'MV'=>'MALDIVES',
		'ML'=>'MALI',
		'MT'=>'MALTA',
		'MH'=>'MARSHALL ISLANDS',
		'MQ'=>'MARTINIQUE',
		'MR'=>'MAURITANIA',
		'MU'=>'MAURITIUS',
		'YT'=>'MAYOTTE',
		'MX'=>'MEXICO',
		'FM'=>'MICRONESIA',
		'MD'=>'MOLDOVA REPUBLIC OF',
		'MC'=>'MONACO',
		'MN'=>'MONGOLIA',
		'MS'=>'MONTSERRAT',
		'MA'=>'MOROCCO',
		'MZ'=>'MOZAMBIQUE',
		'MM'=>'MYANMAR',
		'ME'=>'Montenegro',
		'NA'=>'NAMIBIA',
		'NR'=>'NAURU',
		'NP'=>'NEPAL',
		'AN'=>'NETHERLANDS ANTILLES',
		'NL'=>'NETHERLANDS',
		'NC'=>'NEW CALEDONIA',
		'NZ'=>'NEW ZEALAND',
		'NI'=>'NICARAGUA',
		'NE'=>'NIGER',
		'NG'=>'NIGERIA',
		'NU'=>'NIUE',
		'AP'=>'NON-SPEC ASIA PAS LOCATION',
		'NF'=>'NORFOLK ISLAND',
		'MP'=>'NORTHERN MARIANA ISLANDS',
		'NO'=>'NORWAY',
		'OM'=>'OMAN',
		'PK'=>'PAKISTAN',
		'PW'=>'PALAU',
		'PS'=>'PALESTINA',
		'PA'=>'PANAMA',
		'PG'=>'PAPUA NEW GUINEA',
		'PY'=>'PARAGUAY',
		'PE'=>'PERU',
		'PH'=>'PHILIPPINES',
		'PN'=>'PITCAIRN',
		'PL'=>'POLAND',
		'PT'=>'PORTUGAL',
		'PR'=>'PUERTO RICO',
		'QA'=>'QATAR',
		'ZZ'=>'RESERVED',
		'RE'=>'REUNION',
		'RO'=>'ROMANIA',
		'RU'=>'RUSSIAN FEDERATION',
		'RW'=>'RWANDA',
		'KN'=>'SAINT KITTS AND NEVIS',
		'LC'=>'SAINT LUCIA',
		'VC'=>'SAINT VINCENT AND THE GRENADINES',
		'WS'=>'SAMOA',
		'SM'=>'SAN MARINO',
		'ST'=>'SAO TOME AND PRINCIPE',
		'SA'=>'SAUDI ARABIA',
		'SN'=>'SENEGAL',
		'SC'=>'SEYCHELLES',
		'SL'=>'SIERRA LEONE',
		'SG'=>'SINGAPORE',
		'SK'=>'SLOVAKIA',
		'SI'=>'SLOVENIA',
		'SB'=>'SOLOMON ISLANDS',
		'SO'=>'SOMALIA',
		'ZA'=>'SOUTH AFRICA',
		'GS'=>'SOUTH GEORGIA',
		'ES'=>'SPAIN',
		'LK'=>'SRI LANKA',
		'SH'=>'ST. HELENA',
		'PM'=>'ST. PIERRE AND MIQUELON',
		'SD'=>'SUDAN',
		'SR'=>'SURINAME',
		'SJ'=>'SVALBARD AND JAN MAYEN ISLANDS',
		'SZ'=>'SWAZILAND',
		'SE'=>'SWEDEN',
		'CH'=>'SWITZERLAND',
		'SY'=>'SYRIAN ARAB REPUBLIC',
		'CS'=>'SERBIA AND MONTENEGRO',
		'YU'=>'SERBIA AND MONTENEGRO',
		'RS'=>'Serbia',
		'TW'=>'TAIWAN',
		'TJ'=>'TAJIKISTAN',
		'TZ'=>'TANZANIA',
		'TH'=>'THAILAND',
		'TL'=>'TIMOR-LESTE',
		'TG'=>'TOGO',
		'TK'=>'TOKELAU',
		'TO'=>'TONGA',
		'TT'=>'TRINIDAD AND TOBAGO',
		'TN'=>'TUNISIA',
		'TR'=>'TURKEY',
		'TM'=>'TURKMENISTAN',
		'TC'=>'TURKS AND CAICOS ISLANDS',
		'TV'=>'TUVALU',
		'UG'=>'UGANDA',
		'UA'=>'UKRAINE',
		'AE'=>'UNITED ARAB EMIRATES',
		'GB'=>'UNITED KINGDOM',
		'UK'=>'UNITED KINGDOM',
		'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS',
		'US'=>'UNITED STATES',
		'UY'=>'URUGUAY',
		'UZ'=>'UZBEKISTAN',
		'VU'=>'VANUATU',
		'VE'=>'VENEZUELA',
		'VN'=>'VIET NAM',
		'VG'=>'VIRGIN ISLANDS',
		'VI'=>'VIRGIN ISLANDS',
		'WF'=>'WALLIS AND FUTUNA ISLANDS',
		'EH'=>'WESTERN SAHARA',
		'YE'=>'YEMEN',
		'ZM'=>'ZAMBIA',
		'ZW'=>'ZIMBABWE',
		'AX'=>'ALAND ISLANDS',
		'MF'=>'SAINT MARTIN'
		);
	
	
	//methods
	private function appendToFile($db)
	{
		foreach ($db AS $piece=>$entries)
		{			
			$filename = $this->dir . $piece . '.php';
		
			if (!file_exists($filename))
			{
				$f = fopen($filename, 'w');
				fputs($f, '<?php $entries = array(' . "\n");
			}
			else
			{
				$f = fopen($filename, 'a');
			}
			
			foreach ($entries AS $entry)
			{
				fputs($f, "array('" . $entry[0] . "','" . $entry[1] . "','" . $entry[2] . "'),\n");
			}
		
			fclose($f);
		}		 
	}

	//methods
	private function appendToFile_loc($loc)
	{
		foreach ($loc AS $piece=>$entries)
		{			
			$filename = $this->loc_dir . $piece . '.php';
		
			if (!file_exists($filename))
			{
				$f = fopen($filename, 'w');
				fputs($f, '<?php $loc_entries = array(' . "\n");
			}
			else
			{
				$f = fopen($filename, 'a');
			}
			
			foreach ($entries AS $entry)
			{
				$str 	=  '"'.$entry[0].'" => array(';
				$str	.= '"'.$entry[1].'"';
				$str	.= ',"'.$entry[2].'"';
				$str	.= ',"'.$entry[3].'"';
				$str	.= ',"'.$entry[4].'"';
				$str	.= ','.floatval($entry[5]);
				$str	.= ','.floatval($entry[6]);
				$str	.= ','.intval($entry[7]);
				$str	.= ','.intval($entry[8]);
				$str	.= "),\n";
				fputs($f, $str);
			}
		
			fclose($f);
		}		 
	}
	
	
	private function finishFile($filename)
	{
		$f = fopen($filename, 'a');
		fputs($f, ');');
		fclose($f);	
	}
	
	public function parseCSV($filename = 'data.csv')
	{
		$f = fopen($filename, 'r');
		$db = array();
		fgets($f);fgets($f);
		//parse into array
		while (!feof($f))
		{
			$s = fgets($f);
			if (substr($s, 0, 1) == '#') continue;
			
			$temp = explode(',', $s);
			//if (count($temp)<7) continue;
			
			list($from, $to, $code) = $temp;
			
			$from = trim($from, '"');
			$to = trim($to, '"');
			$code = trim($code, '"');	
				
			$piece = substr($from, 0, 3);
			
			$db[$piece][] = array($from, $to, $code);		
		}
		fclose($f);
		
		//dump array into many PHP files
		foreach ($db AS $piece=>$entries)
		{ 
			$f = fopen($this->dir . $piece . '.php', 'w');
			fputs($f, '<?php $entries = array(' . "\n");
			
			foreach ($entries AS $from=>$entry)
			{                                        
				fputs($f, "array('" . $entry[0] . "','" . $entry[1] . "','" . $entry[2] . "'),\n");
			}
			
			fputs($f, ');');
			fclose($f);
		}
	}
	
	public function parseCSV2($filename = 'data.csv')
	{
		$f = fopen($filename, 'r');
		$db = array();
		$dbSize = 0;
		fgets($f);fgets($f);	
		//parse into array
		while (!feof($f))
		{
			$s = fgets($f);
			
			if (substr($s, 0, 1) == '#') continue;
			
			$temp = explode(',', $s);
			if ( count($temp)<3 ) continue;
			
			list($from, $to, $code) = $temp;
			
			$from = trim($from, '"');
			$to = trim($to, '"');
			$code = trim($code, "\"\x00..\x1F");
				
			$piece = substr($from, 0, 3);		
		
			$db[$piece][] = array($from, $to, $code);
			$dbSize++;
			
			if ($dbSize>10000)
			{
				$this->appendToFile($db);
				unset($db);
				$dbSize = 0;
			}		
		}
		fclose($f);
		
		$this->appendToFile($db);
		
		//now "finish" all files
		if (is_dir($this->dir)) 
		{
		    if ($dh = opendir($this->dir)) 
			{
		        while (($file = readdir($dh)) !== false) 
				{
		        	if ($file == '.' or $file == '..')
					{
						continue;
					}
					$this->finishFile($this->dir . $file);   
		        }
		        closedir($dh);
		    }
		}

		//echo memory_get_usage();		
	}

	public function parseCSV_loc($filename = 'loc_data.csv')
	{
		$f = fopen($filename, 'r');
		$loc = array();
		$locSize = 0;
		fgets($f);fgets($f);	
		//parse into array
		while (!feof($f))
		{
			$s = fgets($f);
			
			if (substr($s, 0, 1) == '#') continue;
			
			$temp = explode(',', $s);
			if ( count($temp)<9 ) continue;
			list($locId,$country,$region,$city,$postalCode,$latitude,$longitude,$metroCode,$areaCode ) = $temp;
			$locId	= trim($locId, '"');
			$country= trim($country, '"');
			$region = trim($region, '"');
			$city	= trim($city, '"');
			$postalCode = trim($postalCode, '"');
			$latitude 	= floatval($latitude);
			$longitude 	= floatval($longitude);
			$metroCode  = intval($metroCode);
			$areaCode	= intval($areaCode);	
				
			$piece = substr($locId, 0, 3);		
		
			$loc[$piece][] = array($locId,$country,$region,$city,$postalCode,$latitude,$longitude,$metroCode,$areaCode );
			$locSize++;
			
			if ($locSize>1000)
			{
				$this->appendToFile_loc($loc);
				unset($loc);
				$locSize = 0;
			}		
		}
		fclose($f);
		
		$this->appendToFile_loc($loc);
		
		//now "finish" all files
		if (is_dir($this->loc_dir)) 
		{
		    if ($dh = opendir($this->loc_dir)) 
			{
		        while (($file = readdir($dh)) !== false) 
				{
		        	if ($file == '.' or $file == '..')
					{
						continue;
					}
					$this->finishFile($this->loc_dir . $file);   
		        }
		        closedir($dh);
		    }
		}

		//echo memory_get_usage();		
	}
	
	public function load($ip)
	{
		$ip = floatval($this->ip2int($ip));
		$piece = substr($ip, 0, 3);
		$fields= array("countryCode","region","city","postalCode","latitude","longitude","metroCode","areaCode" );

		if (!file_exists($this->dir . $piece . '.php'))
		{
			return array("error" => "IP part $piece not found");
		}
		
		include $this->dir . $piece . '.php';
		
		foreach ($entries AS $e)
		{	
			$e[0] = floatval($e[0]);
			
			if ($e[0] <= $ip and $e[1] >= $ip)
			{
				$locId	=	$e[2];
				$piece = substr($locId, 0, 3);
				if (!file_exists($this->loc_dir . $piece . '.php'))
				{
					return array("error" => "Location part $piece not found");
				}
				include $this->loc_dir . $piece . '.php';
				$record = @$loc_entries[$locId];
				if (is_array($record)) {
					$this->result = array_combine($fields, $record);
					$this->result['country'] = $this->codes[$record[0]];
					return $this->result; 
				} else {
					return array();
				}
			}
		}
		
		return array();
	}

    private function ip2int($ip)
	{
		//In case you wonder how it works...
		//$t = explode('.', $ip);
		//return $t[0] * 256*256*256 + $t[1]*256*256 + $t[2]*256 + $t[3];
		return sprintf("%u\n", ip2long($ip));
	}
		
	public function __get($var)
	{
		if (isset($this->property[$var]))
		{
			if ($this->property[$var] != false)
			{ 
				return $this->property[$var];
			}
			$this->error = 'No IP specified';
		}
		return false;
	}

}