<?php

class Util {

    function __construct() {
		$this->db = DB::instance();
    }

    // Base-16 to Base-64 conversion
    function base16_to_base64($base16) {
        return base64_encode(pack('H*', $base16));
    }

    // Base-64 to Base-16 conversion
    function base64_to_base16($base64) {
        return implode('', unpack('H*', base64_decode($base64)));
    }

    // Base-64 encoding with the URL and filename safe alphabet
    function base64_to_base64safe($base64) {
        return strtr($base64, '+/', '-_');
    }

    // Inverse function for Base-64 encoding with the URL and filename safe alphabet
    function base64safe_to_base64($base64safe) {
        return strtr($base64safe, '-_', '+/');
    }

    // compresses hexadecimal MD5 values using URL safe characters
    function compress_hash($hash) {
        return $this->base64_to_base64safe(rtrim($this->base16_to_base64($hash), '='));
    }

    // compresses hexadecimal MD5 values using URL safe characters
    function uncompress_hash($hash) {
        return $this->base64_to_base16($this->base64safe_to_base64($hash));
    }

    // load the list of countries
    function get_regions() {
        return $this->db->query("SELECT region_id, region_name, abbreviation from regions where active = 1 order by region_name", null, 6000);
    }

    function get_states($region_id = 1) {
        return $this->db->query("SELECT state_code, state_name from states where region_id = ? and active = 1 order by state_name", array($region_id), 6000);
    }

    function unset_array($array) {
        if (is_array($array))
            foreach($array as $a) unset($a);
    }

    public static function encode($user_id1, $user_id2) {
		if(empty($user_id1) || empty($user_id2)) return;
		$ch = str_split('abcdefghijkmnpqrstuvwxyz23456789');

		$lower4 = $user_id1 << 3;
		$lower4Bits = floor(log($lower4, 2))+1;
		$lower4 |= ceil($lower4Bits / 5);

		$str = '';
		do {
			$str = $ch[$lower4 & 31] . $str;
		} while($lower4 >>= 5);

		$upper4 = $user_id2;

		do {
			$str = $ch[$upper4 & 31] . $str;
		} while($upper4 >>= 5);

		return $str;
    }

    public static function decode($str, &$user_id1, &$user_id2) {
		if(empty($str)) return;
		$map = 'abcdefghijkmnpqrstuvwxyz23456789';
		$ch = array_flip(str_split($map));

		if(preg_match("/[^$map]/", $str)) return null;

		$uCount = $ch[substr($str,-1)] & 7;
		$pStr = substr($str, 0, strlen($str) - $uCount);
		$uStr = substr($str, -$uCount);

		$user_id1 = 0;
		foreach(str_split($uStr) as $c)
			if(isset($ch[$c]))
				$user_id1 = ($user_id1 << 5) | $ch[$c];
		$user_id1 >>= 3;

		$user_id2 = 0;
		foreach(str_split($pStr) as $c)
			if(isset($ch[$c]))
				$user_id2 = ($user_id2 << 5) | $ch[$c];
	}

    public static function simple_encrypt($string, $key = 'm0Ose') {
		if (defined('SIMPLE_ENCRYPTION_KEY') && strlen(SIMPLE_ENCRYPTION_KEY) > 0) { $key = SIMPLE_ENCRYPTION_KEY; }
		$key = sha1($key);
		$str_len = strlen($string);
		$key_len = strlen($key);
		$hash = $j = null;
		for ($i = 0; $i < $str_len; $i++) {
			$ord_str = ord(substr($string, $i, 1));
			if ($j == $key_len) { $j = 0; }
			$ord_key = ord(substr($key, $j, 1));
			$j++;
			$hash .= strrev(base_convert(dechex($ord_str + $ord_key),16,36));
		}
		return $hash;
    }

    public static function simple_decrypt($string, $key = 'm0Ose') {
		if (defined('SIMPLE_ENCRYPTION_KEY') && strlen(SIMPLE_ENCRYPTION_KEY) > 0) { $key = SIMPLE_ENCRYPTION_KEY; }
		$key = sha1($key);
		$str_len = strlen($string);
		$key_len = strlen($key);
		$hash = $j = null;
		for ($i = 0; $i < $str_len; $i+=2) {
			$ord_str = hexdec(base_convert(strrev(substr($string, $i, 2)),36,16));
			if ($j == $key_len) { $j = 0; }
			$ord_key = ord(substr($key, $j, 1));
			$j++;
			$hash .= chr($ord_str - $ord_key);
		}
		return $hash;
    }

	public static function monthNameToNumber($p_month) {
		if (empty($p_month)) {
			return "01";
		}
		else {
			$p_month = strtolower($p_month);
			if ($p_month == 'jan' || $p_month == 'january')        return '01';
			else if ($p_month == 'feb' || $p_month == 'february')  return '02';
			else if ($p_month == 'mar' || $p_month == 'march')     return '03';
			else if ($p_month == 'apr' || $p_month == 'april')     return '04';
			else if ($p_month == 'may' || $p_month == 'may')       return '05';
			else if ($p_month == 'jun' || $p_month == 'june')      return '06';
			else if ($p_month == 'jul' || $p_month == 'july')      return '07';
			else if ($p_month == 'aug' || $p_month == 'august')    return '08';
			else if ($p_month == 'sep' || $p_month == 'september') return '09';
			else if ($p_month == 'oct' || $p_month == 'october')   return '10';
			else if ($p_month == 'nov' || $p_month == 'november')  return '11';
			else if ($p_month == 'dec' || $p_month == 'december')  return '12';
		}
	}

	public static function mysqlDateToDate($mysql_date) {
		date_default_timezone_set('UTC');
		if ($mysql_date == '0000-00-00 00:00:00') return '';
		$date_only_components = explode(' ', $mysql_date);
		$date_components = explode('-', $date_only_components[0]);
		if (is_numeric($date_components[0]) && is_numeric($date_components[1]) && is_numeric($date_components[2]))
			return date("M d, Y", mktime(0, 0, 0, $date_components[1], $date_components[2], $date_components[0]));
		else
			return '';
	}

	public static function mysqlDateTimeToPrettyDateTime($mysql_date) {
		date_default_timezone_set('UTC');
		if ($mysql_date == '0000-00-00 00:00:00') return '';
		$date_only_components = explode(' ', $mysql_date);
		$date_components = explode('-', $date_only_components[0]);
		$time_components = explode(':', $date_only_components[1]);
		if (is_numeric($date_components[0]) && is_numeric($date_components[1]) && is_numeric($date_components[2]))
			return date('M d, Y g:i a', mktime($time_components[0], $time_components[1], 0, $date_components[1], $date_components[2], $date_components[0]));
		else
			return '';
	}

	public static function mysqlGetYear($mysql_date) {
		date_default_timezone_set('UTC');
		if ($mysql_date == '0000-00-00 00:00:00') return false;
		$date_only_components = explode(' ', $mysql_date);
		$date_components = explode('-', $date_only_components[0]);
		if (is_numeric($date_components[0]) && is_numeric($date_components[1]) && is_numeric($date_components[2]))
			return (int)$date_components[0];
		else
			return '';
	}

	public static function mysqlDateToPHPTimeObject($mysql_date) {
		date_default_timezone_set('UTC');
		if ($mysql_date == '0000-00-00 00:00:00') return '';
		$date_only_components = explode(' ', $mysql_date);
		$date_components = explode('-', $date_only_components[0]);
		$time_components = explode(':', $date_only_components[1]);

		if (is_numeric($date_components[0]) && is_numeric($date_components[1]) && is_numeric($date_components[2]) &&
			is_numeric($time_components[0]) && is_numeric($time_components[1]) && is_numeric($time_components[2])) {
				return mktime($time_components[0], $time_components[1], $time_components[2], $date_components[1], $date_components[2], $date_components[0]);
			}
		else
			return '';
	}

	public static function mysqlDateTimeToDateTime($mysql_date_time) {
		date_default_timezone_set('UTC');
		$mysql_date_time_components = explode(' ', $mysql_date_time);
		$date_components = explode('-', $mysql_date_time_components[0]);
		$time_components = explode(':', $mysql_date_time_components[1]);
		if (is_numeric($date_components[0]) &&
			is_numeric($date_components[1]) &&
			is_numeric($date_components[2])) {

			if (!is_numeric($time_components[0])) $time_components[0] = 0;
			if (!is_numeric($time_components[1])) $time_components[1] = 0;
			if (!is_numeric($time_components[2])) $time_components[2] = 0;

				return mktime($time_components[0], $time_components[1], $time_components[2], $date_components[1], $date_components[2], $date_components[0]);
			}
		else
			return '';
	}

	public static function array_sort($array, $on, $order='asc') {
        $new_array = array();
        $sortable_array = array();
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                $key_found = false;
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $key_found = true;
                            $sortable_array[$k] = $v2;
                        }
                    }
                    if (!$key_found) {
                        $sortable_array[$k] = 0;
                    }
                }
                else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case 'asc' : asort($sortable_array); break;
                case 'desc': arsort($sortable_array); break;
            }
            foreach ($sortable_array as $k => $v)
                $new_array[] = $array[$k];
        }
        return $new_array;
    }

	// $source_id can be PubMed id or any other id of the source of the article
	public static function createArticleHash($title, $article_date, $source_id) {
		return md5($title.$article_date.$source_id);
	}

	public function articleDate($article_date, $journal_date, $date_created) {
		$temp_article_date = $this->mysqlDateToPHPTimeObject($article_date);
		if (empty($temp_article_date))
			$temp_article_date = $this->mysqlDateToPHPTimeObject($journal_date);
			if (empty($temp_article_date))
				$temp_article_date = $this->mysqlDateToPHPTimeObject($date_created);
		return $temp_article_date;
	}

	public static function get_verbal_time_ago($time_value) {
		date_default_timezone_set('UTC');
		if (isset($time_value) && is_numeric($time_value)) {
			$hours = intval((time()-$time_value)/3600);
			if ($hours == 0) {
		        $hours = "less than an hour";
		        $hours_text = 'ago';
		    }
		    elseif ($hours > 0 && $hours <= 12) {
		        if ($hours == 1) $hours_text = 'hour ago'; else $hours_text = 'hours ago';
		    }
		    elseif ($hours > 12 && $hours <= 168) {
		        $hours = round($hours/24);
		        if ($hours == 0) $hours = 1;
		        if ($hours == 1) $hours_text = 'day ago'; else $hours_text = 'days ago';
		    }
		    elseif ($hours > 168 && $hours <= 672) {
		        $hours = round($hours/168);
		        if ($hours == 0) $hours = 1;
		        if ($hours == 1) $hours_text = 'week ago'; else $hours_text = 'weeks ago';
		    }
		    elseif ($hours > 672 && $hours <= 8064) {
		        $hours = round($hours/672);
		        if ($hours == 0) $hours = 1;
		        if ($hours == 1) $hours_text = 'month ago'; else $hours_text = 'months ago';
		    }
		    elseif ($hours > 8064) {
		        $hours = round($hours/8064);
		        if ($hours == 0) $hours = 1;
		        if ($hours == 1) $hours_text = 'year ago'; else $hours_text = 'years ago';
		    }

		    return $hours.' '.$hours_text;
		}
		else {
			return "Unknown time ago";
		}
	}

	public static function page_url() {
		$pageURL = 'https://';
		if ($_SERVER["SERVER_PORT"] != "80") {
	 		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 	} else {
	 		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	 	return $pageURL;
	}

	public static function error_var_dump($object) {
		ob_start();
		var_dump($object);
		$contents = ob_get_contents();
		ob_end_clean();
		error_log($contents);
	}

	public static function array_copy($arr) {
	    $newArray = array();
	    foreach($arr as $key => $value) {
	        if(is_array($value)) $newArray[$key] = Util::array_copy($value);
	        elseif(is_object($value)) $newArray[$key] = clone $value;
	        else $newArray[$key] = $value;
	    }
	    return $newArray;
	}
}
