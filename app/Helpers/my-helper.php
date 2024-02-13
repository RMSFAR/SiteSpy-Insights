<?php

if(! function_exists('base_url')){
  function base_url(){
      return $base_url =  url_convert_to_domain(url('/'));
  }
}



//=====================converts a number to word====================== 
//====================================================================
if ( ! function_exists('numtowords'))

{ function numtowords($number) {
   
    $hyphen      = '-';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'numtowords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . numtowords(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numtowords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numtowords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numtowords($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
   
   return  $string;
  }
}
//=====================converts a number to word====================== 
//====================================================================


//=====================converts a number to phrase====================== 
//======================================================================
if ( ! function_exists('numtophrase'))
{   
  function numtophrase($num) {
   
   if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
}


if(!function_exists('is_valid_url'))
{
  function is_valid_url($url)
  {
    if (!filter_var($url, FILTER_VALIDATE_URL) === false) :
      return true;
    endif;
    return false;
  }
}
if(!function_exists('is_valid_domain_name'))
{
  function is_valid_domain_name($domain_name)
  {
    if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain_name)){
      return false;
    }
    else
      return true;
  }
}  

if(!function_exists('url_convert_to_domain'))
{
  if ( ! function_exists('url_convert_to_domain'))
  {
    function url_convert_to_domain($url,$http=false) {
          $return = $url;
      if(!$http){
              $url=str_replace("www.","",$url);
              $url=str_replace("WWW.","",$url);
  
              if (!preg_match("@^https?://@i", $url) && !preg_match("@^ftps?://@i", $url)) {
                  $url = "http://" . $url;
              }
              $parsed=@parse_url($url);
              $return = $parsed['host'] ?? '';
          }
      else{
              $result = @parse_url($url);
              if(isset($result['scheme'])) $return = $result['scheme']."://".$result['host'];
              else $return = $url;
          }
  
          $return = trim($return);
          $return = trim($return,'/');
          return $return;
    }
  }
}
//=====================converts a number to phrase====================== 
//======================================================================


//=========================== age calculator ===========================
//======================================================================

if ( ! function_exists('calculate_date_differece'))
{   
  function calculate_date_differece($end,$start)
  {
  $diff = abs(strtotime($end) - strtotime($start));
  $years = floor($diff / (365*60*60*24));
  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
  return $years." Years ".$months." Months ".$days. " Days";
  }
}



if ( ! function_exists('date_time_calculator'))
{
  function date_time_calculator($input_datetime, $format=false,$current_datetime=null)
  {
    // pass current_datetime if the input_datetime is not in UTC
    // expected date format YYY-MM-DD H:i:s
    if(empty($current_datetime)) $current_datetime = date("Y-m-d H:i:s");
    $current_datetime = strtotime($current_datetime);
    $input_datetime = strtotime($input_datetime);

    $difference = abs($current_datetime - $input_datetime);

    $years   = floor($difference / (365*60*60*24));
    $months  = floor(($difference - $years * 365*60*60*24) / (30*60*60*24));
    $days    = floor(($difference - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours   = floor(($difference - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    $minutes = floor(($difference - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    $seconds = floor(($difference - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));

    $result = array(
      "years"   => $years,
      "months"  => $months,
      "days"    => $days,
      "hours"   => $hours,
      "minutes" => $minutes,
      "seconds" => $seconds
    );

    if ($format == true) {

      $years_plular=$months_plular=$days_plular=$hours_plular=$minutes_plular=$seconds_plular="";
      if($result['years']!="" && $result['years']>1) $years_plular='s';
      if($result['months']!="" && $result['months']>1) $months_plular='s';
      if($result['days']!="" && $result['days']>1) $days_plular='s';
      if($result['hours']!="" && $result['hours']>1) $hours_plular='s';
      if($result['minutes']!="" && $result['minutes']>1) $minutes_plular='s';
      if($result['seconds']!="" && $result['seconds']>1) $seconds_plular='s';

      if ($result['years'] > 0)
          return $result['years']." ".__("year").$years_plular." ".__('ago');
      else if ($result['months'] > 0)
          return $result['months']." ".__("month").$months_plular." ".__('ago');
      else if ($result['days'] > 0)
          return $result['days']." ".__("day").$days_plular." ".__('ago');
      else if ($result['hours'] > 0)
          return $result['hours']." ".__("hour").$hours_plular." ".__('ago');
      else if ($result['minutes'] > 0)
          return $result['minutes']." ".__("minute").$minutes_plular." ".__('ago');
      else if ($result['seconds'] > 0)
          return $result['seconds']." ".__("second").$seconds_plular." ".__('ago');
    }
    else return $result;

  }
}

if ( ! function_exists('calculate_date_differece'))
{   
  function calculate_date_differece($end,$start)
  {
  $diff = abs(strtotime($end) - strtotime($start)); 
  $years = floor($diff / (365*60*60*24)); 
  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
  return $years." Years ".$months." Months ".$days. " Days";
  }
}


//=========================== end of age calculator ====================
//======================================================================

//=====================converts a phrase to number====================== 
//======================================================================
if ( ! function_exists('phrasetonumber'))
{   
  function phrasetonumber($phrase) {   
   if(strlen($phrase)==4)
   return substr($phrase,0,2);
   else
   return substr($phrase,0,1);
  }
}
//=====================converts a phrase to number====================== 



//======================get data=================================
//===============================================================
// if ( ! function_exists('get_data_helper'))
// {   
//   function get_data_helper($table,$where='',$select='',$join='',$limit='',$start='',$order_by='',$group_by='',$num_rows=1,$single_value=1) 
//   {
//        $ci = &get_instance();
//        $ci->load->model('basic');  
//        $results=$ci->basic->get_data($table,$where,$select,$join,$limit,$start,$order_by,$group_by,$num_rows);
     
//        if($single_value==1) return $results[0];
//        else return $results;

//   }
// }


/*date Time Formating*/
 if ( ! function_exists('date_time_formating'))
{   
  function date_time_formating($date) 
  {
       return date('d/m/Y h:i:s a',strtotime($date));
  }
 }



/**Date Time formating**/
 if ( ! function_exists('date_formating'))
{   
  function date_formating($date) 
  {
       return date('d/m/Y',strtotime($date));
  }
 }



// if ( ! function_exists('format_data_dropdown'))
// {
//   function format_data_dropdown($result=array(), $index='id', $display='name',$empty_index=true)
//   {
//     $ci = &get_instance();
//     $map_array = array();
//     foreach ($result as $key => $value) 
//     {      
//       $map_array[$value[$index]] = $value[$display];
//     }
//     if($empty_index) $map_array[''] = $ci->lang->line("Select");
//     return $map_array;
//   }
// }

if ( ! function_exists('format_data_dropdown'))
{
  function format_data_dropdown($result, $index = 'id', $display = 'name', $emptyIndex = true)
  {
      $mapArray = [];
      foreach ($result as $value) {
          $mapArray[$value->$index] = $value->$display;
      }
      if ($emptyIndex) {
          $mapArray = ['' => __('Select')] + $mapArray;
      }
      return $mapArray;
  }
}


if ( ! function_exists('convertDataTableResult'))
{
  function convertDataTableResult($result=array(), $columns=array(), $start=0,$primary_key='id')
    {
        unset($columns[0]);
        $have_checkbox=false;
        if(in_array('CHECKBOX', $columns))
        {
          $have_checkbox=true;
          $indexof = array_search("CHECKBOX",$columns);
          unset($columns[$indexof]);
        }
        
        $final_result = array();

        $sl = $start+1;

        foreach ($result as $key => $single_row) {
            
            $temp = array(0=>$sl);
            $sl++;

            if($have_checkbox) 
            {
              $primary_val = isset($single_row->$primary_key) ? $single_row->$primary_key : 0;
              $str ='<input  name="datatableCheckboxRow[]" id="datatableCheckboxRow'.$primary_val.'" class="datatableCheckboxRow regular-checkbox"  type="checkbox" value="'.$primary_val.'"/> <label for="datatableCheckboxRow'.$primary_val.'" style="cursor:pointer;"></label>';
              $temp[1] = $str;
            }

            foreach ($columns as $key1 => $column_name) 
                array_push($temp, $single_row->$column_name);
            
            array_push($final_result, $temp);
        }

        return $final_result;
    }
}



// if ( ! function_exists('calcutate_age'))
// { 
//   function calcutate_age($dob)
//   {

//       $dob = date("Y-m-d",strtotime($dob));

//       $dobObject = new DateTime($dob);
//       $nowObject = new DateTime();

//       $diff = $dobObject->diff($nowObject);

//       return $diff->y;

//   }
// }

/**This function to take the original site url . Because we are using subdomain for same site.So for facebook page like we need to do a universal url**/

// if ( ! function_exists('get_current_url_without_subdomain'))
// { 
//   function get_current_url_without_subdomain()
//   {
//   		$CI =& get_instance();
		
// 		$url=current_url();
// 		$info = parse_url($url);
// 		$url_without_subdomain=$CI->config->item('fb_like_doamin').$info['path'];
// 		return $url_without_subdomain;
//   }
// }

//======================used for counting a field================
//===============================================================



if ( ! function_exists('random_value_from_array'))
{ 
 	 function random_value_from_array($array, $default=null)
		{
		    $k = mt_rand(0, count($array) - 1);
		    return isset($array[$k])? $array[$k]: $default;
		}
}



if ( ! function_exists('addHttp'))
{ 
function addHttp( $url ){
	
	    if ( !preg_match("~^(?:f|ht)tps?://~i", $url) )
	    {
	        $url = "http://" . $url;
	    }
	
	    return $url;
	}
}


  if ( ! function_exists('get_domain_only'))
  { 
  	function get_domain_only($url) {
  		$url=str_replace("www.","",$url);
  		$url=str_replace("WWW.","",$url);
  		
  	    if (!preg_match("@^https?://@i", $url) && !preg_match("@^ftps?://@i", $url)) {
  	        $url = "http://" . $url;
  	    }
  		
  		if($url!="")
  	    $parsed=@parse_url($url);

      if(isset($parsed['host']))
        return $parsed['host'];
      
      else return "";
  		
  	  
  	}
  }


if ( ! function_exists('get_domain_only_with_http'))
{ 
  function get_domain_only_with_http($url) {
  
    $result = @parse_url($url);

    if(isset($result['scheme']))
      return  $result['scheme']."://".$result['host'];
    else
      return $url; 
  }
}

if ( ! function_exists('get_country_names'))
{
  function get_country_names()
  {
      $array_countries = array (
        'AF' => 'AFGHANISTAN',
        'AX' => 'ÅLAND ISLANDS',
        'AL' => 'ALBANIA',
  
        'DZ' => 'ALGERIA (El Djazaïr)',
        'AS' => 'AMERICAN SAMOA',
        'AD' => 'ANDORRA',
        'AO' => 'ANGOLA',
        'AI' => 'ANGUILLA',
        'AQ' => 'ANTARCTICA',
        'AG' => 'ANTIGUA AND BARBUDA',
        'AR' => 'ARGENTINA',
        'AM' => 'ARMENIA',
        'AW' => 'ARUBA',
  
        'AU' => 'AUSTRALIA',
        'AT' => 'AUSTRIA',
        'AZ' => 'AZERBAIJAN',
        'BS' => 'BAHAMAS',
        'BH' => 'BAHRAIN',
        'BD' => 'BANGLADESH',
        'BB' => 'BARBADOS',
        'BY' => 'BELARUS',
        'BE' => 'BELGIUM',
        'BZ' => 'BELIZE',
        'BJ' => 'BENIN',
        'BM' => 'BERMUDA',
        'BT' => 'BHUTAN',
        'BO' => 'BOLIVIA',
  
        'BA' => 'BOSNIA AND HERZEGOVINA',
        'BW' => 'BOTSWANA',
        'BV' => 'BOUVET ISLAND',
        'BR' => 'BRAZIL',
  
        'BN' => 'BRUNEI DARUSSALAM',
        'BG' => 'BULGARIA',
        'BF' => 'BURKINA FASO',
        'BI' => 'BURUNDI',
        'KH' => 'CAMBODIA',
        'CM' => 'CAMEROON',
        'CA' => 'CANADA',
        'CV' => 'CAPE VERDE',
        'KY' => 'CAYMAN ISLANDS',
        'CF' => 'CENTRAL AFRICAN REPUBLIC',
        'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)',
        'CL' => 'CHILE',
        'CN' => 'CHINA',
        'CX' => 'CHRISTMAS ISLAND',
  
        'CO' => 'COLOMBIA',
        'KM' => 'COMOROS',
        'CG' => 'CONGO, REPUBLIC OF',
        'CK' => 'COOK ISLANDS',
        'CR' => 'COSTA RICA',
        'CI' => 'CÔTE D\'IVOIRE (Ivory Coast)',
        'HR' => 'CROATIA (Hrvatska)',
        'CU' => 'CUBA',
        'CW' => 'CURAÇAO',
        'CY' => 'CYPRUS',
        'CZ' => 'ZECH REPUBLIC',
        'DK' => 'DENMARK',
        'DJ' => 'DJIBOUTI',
        'DM' => 'DOMINICA',
        'DC' => 'DOMINICAN REPUBLIC',
        'EC' => 'ECUADOR',
        'EG' => 'EGYPT',
        'SV' => 'EL SALVADOR',
        'GQ' => 'EQUATORIAL GUINEA',
        'ER' => 'ERITREA',
        'EE' => 'ESTONIA',
        'ET' => 'ETHIOPIA',
        'FO' => 'FAEROE ISLANDS',
  
        'FJ' => 'FIJI',
        'FI' => 'FINLAND',
        'FR' => 'FRANCE',
        'GF' => 'FRENCH GUIANA',
  
        'GA' => 'GABON',
        'GM' => 'GAMBIA, THE',
        'GE' => 'GEORGIA',
        'DE' => 'GERMANY (DEUTSCHLAND)',
        'GH' => 'GHANA',
        'GI' => 'GIBRALTAR',
        // 'GB' => 'UNITED KINGDOM',
        'GR' => 'GREECE',
        'GL' => 'GREENLAND',
        'GD' => 'GRENADA',
        'GP' => 'GUADELOUPE',
        'GU' => 'GUAM',
        'GT' => 'GUATEMALA',
        'GG' => 'GUERNSEY',
        'GN' => 'GUINEA',
        'GW' => 'GUINEA-BISSAU',
        'GY' => 'GUYANA',
        'HT' => 'HAITI',
  
        'HN' => 'HONDURAS',
        'HK' => 'HONG KONG (Special Administrative Region of China)',
        'HU' => 'HUNGARY',
        'IS' => 'ICELAND',
        'IN' => 'INDIA',
        'ID' => 'INDONESIA',
        'IR' => 'IRAN (Islamic Republic of Iran)',
        'IQ' => 'IRAQ',
        'IE' => 'IRELAND',
        'IM' => 'ISLE OF MAN',
        'IL' => 'ISRAEL',
        'IT' => 'ITALY',
        'JM' => 'JAMAICA',
        'JP' => 'JAPAN',
        'JE' => 'JERSEY',
        'JO' => 'JORDAN (Hashemite Kingdom of Jordan)',
        'KZ' => 'KAZAKHSTAN',
        'KE' => 'KENYA',
        'KI' => 'KIRIBATI',
        'KP' => 'KOREA (Democratic Peoples Republic of [North] Korea)',
        'KR' => 'KOREA (Republic of [South] Korea)',
        'KW' => 'KUWAIT',
        'KG' => 'KYRGYZSTAN',
  
        'LV' => 'LATVIA',
        'LB' => 'LEBANON',
        'LS' => 'LESOTHO',
        'LR' => 'LIBERIA',
        'LY' => 'LIBYA (Libyan Arab Jamahirya)',
        'LI' => 'LIECHTENSTEIN (Fürstentum Liechtenstein)',
        'LT' => 'LITHUANIA',
        'LU' => 'LUXEMBOURG',
        'MO' => 'MACAO (Special Administrative Region of China)',
        'MK' => 'MACEDONIA (Former Yugoslav Republic of Macedonia)',
        'MG' => 'MADAGASCAR',
        'MW' => 'MALAWI',
        'MY' => 'MALAYSIA',
        'MV' => 'MALDIVES',
        'ML' => 'MALI',
        'MT' => 'MALTA',
        'MH' => 'MARSHALL ISLANDS',
        'MQ' => 'MARTINIQUE',
        'MR' => 'MAURITANIA',
        'MU' => 'MAURITIUS',
        'YT' => 'MAYOTTE',
        'MX' => 'MEXICO',
        'FM' => 'MICRONESIA (Federated States of Micronesia)',
        'MD' => 'MOLDOVA',
        'MC' => 'MONACO',
        'MN' => 'MONGOLIA',
        'ME' => 'MONTENEGRO',
        'MS' => 'MONTSERRAT',
        'MA' => 'MOROCCO',
        'MZ' => 'MOZAMBIQUE (Moçambique)',
        'MM' => 'MYANMAR (formerly Burma)',
        'NA' => 'NAMIBIA',
        'NR' => 'NAURU',
        'NP' => 'NEPAL',
        'NL' => 'NETHERLANDS',
        'AN' => 'NETHERLANDS ANTILLES (obsolete)',
        'NC' => 'NEW CALEDONIA',
        'NZ' => 'NEW ZEALAND',
        'NI' => 'NICARAGUA',
        'NE' => 'NIGER',
        'NG' => 'NIGERIA',
        'NU' => 'NIUE',
        'NF' => 'NORFOLK ISLAND',
        'MP' => 'NORTHERN MARIANA ISLANDS',
        'ND' => 'NORWAY',
        'OM' => 'OMAN',
        'PK' => 'PAKISTAN',
        'PW' => 'PALAU',
        'PS' => 'PALESTINIAN TERRITORIES',
        'PA' => 'PANAMA',
        'PG' => 'PAPUA NEW GUINEA',
        'PY' => 'PARAGUAY',
        'PE' => 'PERU',
        'PH' => 'PHILIPPINES',
        'PN' => 'PITCAIRN',
        'PL' => 'POLAND',
        'PT' => 'PORTUGAL',
        'PR' => 'PUERTO RICO',
        'QA' => 'QATAR',
        'RE' => 'RÉUNION',
        'RO' => 'ROMANIA',
        'RU' => 'RUSSIAN FEDERATION',
        'RW' => 'RWANDA',
        'BL' => 'SAINT BARTHÉLEMY',
        'SH' => 'SAINT HELENA',
        'KN' => 'SAINT KITTS AND NEVIS',
        'LC' => 'SAINT LUCIA',
  
        'PM' => 'SAINT PIERRE AND MIQUELON',
        'VC' => 'SAINT VINCENT AND THE GRENADINES',
        'WS' => 'SAMOA (formerly Western Samoa)',
        'SM' => 'SAN MARINO (Republic of)',
        'ST' => 'SAO TOME AND PRINCIPE',
        'SA' => 'SAUDI ARABIA (Kingdom of Saudi Arabia)',
        'SN' => 'SENEGAL',
        'RS' => 'SERBIA (Republic of Serbia)',
        'SC' => 'SEYCHELLES',
        'SL' => 'SIERRA LEONE',
        'SG' => 'SINGAPORE',
        'SX' => 'SINT MAARTEN',
        'SK' => 'SLOVAKIA (Slovak Republic)',
        'SI' => 'SLOVENIA',
        'SB' => 'SOLOMON ISLANDS',
        'SO' => 'SOMALIA',
        'ZA' => 'ZAMBIA (formerly Northern Rhodesia)',
        'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
        'SS' => 'SOUTH SUDAN',
        'ES' => 'SPAIN (España)',
        'LK' => 'SRI LANKA (formerly Ceylon)',
        'SD' => 'SUDAN',
        'SR' => 'SURINAME',
        'SJ' => 'SVALBARD AND JAN MAYE',
        'SZ' => 'SWAZILAND',
        'SE' => 'SWEDEN',
        'CH' => 'SWITZERLAND (Confederation of Helvetia)',
        'SY' => 'SYRIAN ARAB REPUBLIC',
        'TW' => 'TAIWAN ("Chinese Taipei" for IOC)',
        'TJ' => 'TAJIKISTAN',
        'TZ' => 'TANZANIA',
        'TH' => 'THAILAND',
        'TL' => 'TIMOR-LESTE (formerly East Timor)',
        'TG' => 'TOGO',
        'TK' => 'TOKELAU',
        'TO' => 'TONGA',
        'TT' => 'TRINIDAD AND TOBAGO',
        'TN' => 'TUNISIA',
        'TR' => 'TURKEY',
        'TM' => 'TURKMENISTAN',
        'TC' => 'TURKS AND CAICOS ISLANDS',
        'TV' => 'TUVALU',
        'UG' => 'UGANDA',
        'UA' => 'UKRAINE',
        'AE' => 'UNITED ARAB EMIRATES',
        'US' => 'UNITED STATES',
        'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
        'UK' => 'UNITED KINGDOM',
        'UY' => 'URUGUAY',
        'UZ' => 'UZBEKISTAN',
        'VU' => 'VANUATU',
        'VA' => 'VATICAN CITY (Holy See)',
        'VN' => 'VIET NAM',
        'VG' => 'VIRGIN ISLANDS, BRITISH',
        'VI' => 'VIRGIN ISLANDS, U.S.',
        'WF' => 'WALLIS AND FUTUNA',
        'EH' => 'WESTERN SAHARA (formerly Spanish Sahara)',
        'YE' => 'YEMEN (Yemen Arab Republic)',
        'ZW' => 'ZIMBABWE'
      );
      return $array_countries;
  }
}

if ( ! function_exists('get_language_names'))
{
  function get_language_names()
  {
      $array_languages = array(
      'ar-XA'=>'Arabic',
      'bg'=>'Bulgarian',
      'hr'=>'Croatian',
      'cs'=>'Czech',
      'da'=>'Danish',
      'de'=>'German',
      'el'=>'Greek',
      'en'=>'English',
      'et'=>'Estonian',
      'es'=>'Spanish',
      'fi'=>'Finnish',
      'fr'=>'French',
      'in'=>'Indonesian',
      'ga'=>'Irish',
      'hr'=>'Hindi',
      'hu'=>'Hungarian',
      'he'=>'Hebrew',
      'it'=>'Italian',
      'ja'=>'Japanese',
      'ko'=>'Korean',
      'lv'=>'Latvian',
      'lt'=>'Lithuanian',
      'nl'=>'Dutch',
      'no'=>'Norwegian',
      'pl'=>'Polish',
      'pt'=>'Portuguese',
      'sv'=>'Swedish',
      'ro'=>'Romanian',
      'ru'=>'Russian',
      'sr-CS'=>'Serbian',
      'sk'=>'Slovak',
      'sl'=>'Slovenian',
      'th'=>'Thai',
      'tr'=>'Turkish',
      'uk-UA'=>'Ukrainian',
      'zh-chs'=>'Chinese (Simplified)',
      'zh-cht'=>'Chinese (Traditional)'
      );
      return $array_languages;
  }
}







if ( ! function_exists('is_web_page'))
{ 
	function is_web_page($domain)
        {
            $ext=explode(".", $domain);
            $extension=array_pop($ext);
            $allowed_extension=array("html","htm","php","asp","jsp","py");
            
            if (in_array($extension, $allowed_extension)) {
                return 1;
            } else {
                return 0;
            }
        }
}


//add query string to url  Example: add_query_string_to_url("https://xeroneit.net/support","from","value") , If query index is already availabe, it doesn't update the value. 

if ( ! function_exists('add_query_string_to_url')){

    function add_query_string_to_url($url,$query_index,$query_value){
        
        $parameters_str = parse_url($url, PHP_URL_QUERY);
        parse_str($parameters_str, $parameters_array);

        $query_param="{$query_index}={$query_value}";

        if(!isset($parameters_array[$query_index])){

            if ($parameters_str)  $url .= "&{$query_param}";
            else  $url .= "?{$query_param}";
        } 

        return $url;

        }
        
}




if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( ! isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( ! isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}





// CSS Minifier => http://ideone.com/Q5USEF + improvement(s)



if(!function_exists('hash_equals'))
{
    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}


function convert_to_ascii($url)
  {
    $parts = parse_url($url);
    if (!isset($parts['host']))
      return $url; // missing http? makes parse_url fails
   
    if (mb_detect_encoding($parts['host']) != 'ASCII'  && function_exists("idn_to_ascii") ){
      $parts['host'] = idn_to_ascii($parts['host']);
      return $parts['scheme']."://".$parts['host'];
    }
    return $url;
  }

if ( ! function_exists('array_add'))
{ 

  function array_add($array1,$array2){
      $array_1=$array1;
      $array_2=$array2;
      $arra1_count=count($array_1);
      foreach($array_2 as $val){
        $array_1[$arra1_count]=$val;
        $arra1_count++;
      }
      return $array_1;
  }

}


if ( ! function_exists('convert_to_grid_data'))
{   
  function convert_to_grid_data($total_info,$total_result=10) 
  {
       $result["total"] = $total_result;
    $items = array();
    
    foreach($total_info as $index=>$info){
      if($index!=='extra_index'){
        $info_obj=(object)$info;
        array_push($items, $info_obj);
      }
      
    }
    $result["rows"] = $items;
    return json_encode($result);
  }
}



function raw_json_encode($input, $flags = 0) {
    $fails = implode('|', array_filter(array(
        '\\\\',
        $flags & JSON_HEX_TAG ? 'u003[CE]' : '',
        $flags & JSON_HEX_AMP ? 'u0026' : '',
        $flags & JSON_HEX_APOS ? 'u0027' : '',
        $flags & JSON_HEX_QUOT ? 'u0022' : '',
    )));
    $pattern = "/\\\\(?:(?:$fails)(*SKIP)(*FAIL)|u([0-9a-fA-F]{4}))/";
    $callback = function ($m) {
        return html_entity_decode("&#x$m[1];", ENT_QUOTES, 'UTF-8');
    };
    return preg_replace_callback($pattern, $callback, json_encode($input, $flags));
}


function SecToHHmmSSms( $input=0 )
{
  $input=$input*1000;

  $uSec = $input % 1000;
  $uSec=str_pad($uSec,3,"0",STR_PAD_LEFT);
  $input = floor($input / 1000);

  $seconds = $input % 60;
  $seconds=str_pad($seconds,2,"0",STR_PAD_LEFT);
  $input = floor($input / 60);

  $minutes = $input % 60;
  $minutes=str_pad($minutes,2,"0",STR_PAD_LEFT);

  $input = floor($input / 60); 
  $input = str_pad($input,2,"0",STR_PAD_LEFT);

  $out= "{$input}:{$minutes}:{$seconds},{$uSec}";
  return $out;
}



function custom_number_format($n, $precision = 2) {
    if ($n < 1000)
    {
        // Anything less than a thousand
        $n_format = number_format($n);
    }
    else if ($n < 1000000)
    {
        // Anything less than a million
        $n_format = number_format($n / 1000, $precision) . 'K';
    } 
    else if ($n < 1000000000)
    {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } 
    else if ($n < 1000000000000)
    {        
        // Anything less than a trillion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }
    else
    {
        // At least a trillion
        $n_format = number_format($n / 1000000000000, $precision) . 'T';
    }    

    return $n_format;
}




// function ultraresponse_addon_module_exist(){
	
// 	$ci = &get_instance();
//     $ci->load->model('basic');  
	   
	   
// 	$addon_id="29";
// 	$module_id="88";
// 	$addon_unique_name="comment_reply_enhancers";
	
// 	$is_module_access=0;  // initially no module access
// 	$is_addon_installed=0; // Initially ad on not installed
	
// 	$package_info = $ci->session->userdata("package_info");
// 	$module_acces= isset($package_info['module_ids']) ? $package_info['module_ids'] : "";
// 	$module_acces=explode(",",$module_acces);
	
// 	/* Check if the memeber have the module access*/
// 	if(in_array($module_id,$module_acces))
// 		 $is_module_access=1; 
		
// 	/**Check if the addon is installed **/
// 	$where['where']=array("unique_name"=>$addon_unique_name);
// 	$addon_info = $ci->basic->get_data("add_ons", $where);
	
// 	if(isset($addon_info[0]['id']))
// 		 $is_addon_installed=1; 
		
		
// 	/**If admin and have module installed, then return true***/
// 	if($ci->session->userdata("user_type")=="Admin" && $is_addon_installed==1)
// 		return TRUE;
// 	/**If member and have module installed and have module access, then true***/
// 	if($ci->session->userdata("user_type")=="Member" && $is_addon_installed==1 && $is_module_access==1)
// 		return TRUE;
	
// 	return FALSE;
// }


function spintax_process($text)
{
    return preg_replace_callback(
        '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
        "spintax_replace",
        $text
    );
}

function spintax_replace($text)
{
    $text = spintax_process($text[1]);
    $parts = explode('|', $text);
    return $parts[array_rand($parts)];
}

function hex2rgba($color, $opacity = false) {
 
  $default = 'rgb(0,0,0)';
 
  //Return default if no color provided
  if(empty($color))
          return $default; 
 
  //Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
          $color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
          if(abs($opacity) > 1)
            $opacity = 1.0;
          $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
          $output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}


if ( ! function_exists('youtube_time_to_time_duratio')) { 

    function youtube_time_to_time_duration($string) {

        $string = str_replace('PT', '', $string);

        $string = str_replace('H', ':', $string);
        $string = str_replace('M', ':', $string);
        $string = str_replace('S', 's', $string);

        return $string;
    }
}


if ( ! function_exists('is_mobile')) { 

  function is_mobile() {

      return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

}


if ( ! function_exists('pre'))
{ 
  function pre($val)
        {
            echo "<pre>";
            print_r($val);
            echo "</pre>";
        }
}


if ( ! function_exists('word_limiter'))
{
	/**
	 * Word Limiter
	 *
	 * Limits a string to X number of words.
	 *
	 * @param	string
	 * @param	int
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) === '')
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

		if (strlen($str) === strlen($matches[0]))
		{
			$end_char = '';
		}

		return rtrim($matches[0]).$end_char;
	}
}










