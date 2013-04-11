<?php

function reduce_double_slashes($str) {
	return preg_replace("#([^:])//+#", "\\1/", $str);
}

//copy_directory('dirnameSource','dirnameDestination');

function copy_directory($source, $destination) {
	if (is_dir($source)) {
		@mkdir($destination);
		$directory = dir($source);
		while (FALSE !== ($readdirectory = $directory->read())) {
			if ($readdirectory == '.' || $readdirectory == '..') {
				continue;
			}
			$PathDir = $source . '/' . $readdirectory;
			if (is_dir($PathDir)) {
				copy_directory($PathDir, $destination . '/' . $readdirectory);
				continue;
			}
			copy($PathDir, $destination . '/' . $readdirectory);
		}

		$directory->close();
	} else {
		copy($source, $destination);
	}
}


// A much better and accurate version can be found
// in Aidan's PHP Repository:
// http://aidanlister.com/repos/v/function.size_readable.php

/**
 * Returns a human readable filesize
 *
 * @author      wesman20 (php.net)
 * @author      Jonas John
 * @version     0.3
 * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
 */
function file_size_nice($size) {
	// Adapted from: http://www.php.net/manual/en/function.filesize.php

	$mod = 1024;

	$units = explode(' ', 'B KB MB GB TB PB');
	for ($i = 0; $size > $mod; $i++) {
		$size /= $mod;
	}

	return round($size, 2) . ' ' . $units[$i];
}


function character_limiter($str, $length,$dots='...', $minword = 3) {
	$sub = '';
	$len = 0;


	if(strlen($str) < intval($length)){
		return $str;
	}

	foreach (explode(' ', $str) as $word) {
		$part = (($sub != '') ? ' ' : '') . $word;
		$sub .= $part;
		$len += strlen($part);

		if (strlen($word) > $minword && strlen($sub) >= $length) {
			break;
		}
	}

	return $sub . (($len < strlen($str)) ? $dots : '');
}

 function array_pp($arr){
    $retStr = '<ul>';
    if (is_array($arr)){
        foreach ($arr as $key=>$val){
        	
			$key = str_replace('_', ' ', $key);
			$key = ucwords($key);
			
            if (is_array($val)){
                $retStr .= '<li>' . $key . ': ' . array_pp($val) . '</li>';
            }else{
                $retStr .= '<li>' . $key . ': ' . $val . '</li>';
            }
        }
    }
    $retStr .= '</ul>';
    return $retStr;
}
 
function array_change_key($array, $search, $replace) {
	$arr = array();
	if (isset($array[0]) and is_arr($array[0])) {
		foreach ($array as $item) {
			$item = array_change_key($item, $search, $replace);

			$arr[] = $item;
		}
		return $arr;
	} else {
		if (is_arr($array)) {
			if (isset($array[$search])) {
				$array[$replace] = $array[$search];
			}
			return $array;
		}
	}
	// return TRUE; // Swap complete
}

/**
 * Makes directory recursive, returns TRUE if exists or made and false on error
 *
 * @param string $pathname
 *        	The directory path.
 * @return boolean returns TRUE if exists or made or FALSE on failure.
 */
function mkdir_recursive($pathname) {
	if ($pathname == '') {
		return false;
	}
	is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));
	return is_dir($pathname) || @mkdir($pathname);
}

function array_rpush($arr, $item) {
	$arr = array_pad($arr, -(count($arr) + 1), $item);
	return $arr;
}

/**
 * Converts a path in the appropriate format for win or linux
 *
 * @param string $path
 *        	The directory path.
 * @param boolean $slash_it
 *        	If true, ads a slash at the end, false by default
 * @return string The formated string
 */
function normalize_path($path, $slash_it = true) {

	$parser_mem_crc = 'normalize_path' . crc32($path.$slash_it);

	$ch = mw_var($parser_mem_crc);
	if ($ch != false) {

		$path = $ch;
	} else {





		$path_original = $path;
		$s             = DIRECTORY_SEPARATOR;
		$path          = preg_replace('/[\/\\\]/', $s, $path);
	// $path = preg_replace ( '/' . $s . '$/', '', $path ) . $s;
		$path          = str_replace($s . $s, $s, $path);
		if (strval($path) == '') {
			$path = $path_original;
		}
		if ($slash_it == false) {
			$path = rtrim($path, DIRECTORY_SEPARATOR);
		} else {
			$path .= DIRECTORY_SEPARATOR;
			$path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
		}
		if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
			$path = $path_original;
		}
		if ($slash_it == false) {
		} else {
			$path = $path . DIRECTORY_SEPARATOR;
			$path = reduce_double_slashes($path);
		// $path = rtrim ( $path, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR );
		}

		mw_var($parser_mem_crc, $path);

	}

	return $path;
}

function string_nice($var) {
	$var = html_entity_decode($var);
	$var = str_replace('-', ' ', $var);
	$var = str_replace('_', ' ', $var);


	return $var;
}
function string_get_between($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function string_clean($var) {
	if (is_array($var)) {
		foreach ($var as $key => $val) {
			$output[$key] = string_clean($val);
		}
	} else {
		$var = html_entity_decode($var);
		$var = strip_tags(trim($var));

		$output = stripslashes($var);
	}
	if (!empty($output))
		return $output;
}

function object_2_array($result) {
	$array = array();
	foreach ($result as $key => $value) {
		if (is_object($value)) {
			$array[$key] = object_2_array($value);
		}
		if (is_array($value)) {
			$array[$key] = object_2_array($value);
		} else {
			$array[$key] = $value;
		}
	}
	return $array;
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access public
 * @param
 *        	string
 * @return string
 */
if (!function_exists('remove_invisible_characters')) {
	function remove_invisible_characters($str, $url_encoded = TRUE) {
		$non_displayables = array();

		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)

		if ($url_encoded) {
			$non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12,
			// 14, 15
			$non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08,
		// 11, 12,
		// 14-31,
		// 127

		do {
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		} while ($count);

		return $str;
	}

}

function add_slashes_to_array($arr) {
	if (!empty($arr)) {
		$ret = array();

		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				$v = add_slashes_to_array($v);
			} else {
				// $v =utfString( $v );
				// $v =
				// preg_replace("/[^[:alnum:][:space:][:alpha:][:punct:]]/","",$v);
				// $v = htmlentities ( $v, ENT_NOQUOTES, 'UTF-8' );
				// $v = htmlspecialchars ( $v );
				$v = addslashes($v);
				$v = htmlspecialchars($v);
			}

			$ret[$k] = ($v);
		}

		return $ret;
	}
}

function ago($time, $granularity = 2) {
	$date       = strtotime($time);
	$difference = time() - $date;
	$retval     = '';
	$periods    = array(
		'decade' => 315360000,
		'year' => 31536000,
		'month' => 2628000,
		'week' => 604800,
		'day' => 86400,
		'hour' => 3600,
		'minute' => 60,
		'second' => 1
		);
	foreach ($periods as $key => $value) {
		if ($difference >= $value) {
			$time = floor($difference / $value);
			$difference %= $value;
			$retval .= ($retval ? ' ' : '') . $time . ' ';
			$retval .= (($time > 1) ? $key . 's' : $key);
			$granularity--;
		}
		if ($granularity == '0') {
			break;
		}
	}
	return '' . $retval . ' ago';
}
function mw_warning($text, $exit = false) {
	return mw_warn($text, $exit);
}
function mw_warn($text, $exit = false) {
	include(ADMIN_VIEWS_PATH . 'mw_warning.php');
	if ($exit == true) {
		die();
	}
}

function mw_notification($text, $exit = false) {
	return mw_notif($text, $exit);
}
function mw_notif_le($text, $exit = false){
	return mw_notif_live_edit($text, $exit);
}
function mw_notif_live_edit($text, $exit = false){
	 $editmode_sess = session_get('editmode');
	
	 if($editmode_sess == true){
	 		return mw_notif($text, $exit);
		
	 }
}

function mw_notif($text, $exit = false) {
	include(ADMIN_VIEWS_PATH . 'mw_notification.php');
	if ($exit == true) {
		die();
	}
}
function json_error($text) {
	$arr = array(
		'error' => $text
		);
	print json_encode($arr);
	exit();
}
function json_html($text) {
	$arr = array(
		'html' => $text
		);
	print json_encode($arr);
	exit();
}
function json_success($text) {
	$arr = array(
		'success' => $text
		);
	print json_encode($arr);
	exit();
}



function mw_error($text, $exit = false) {
	include(ADMIN_VIEWS_PATH . 'mw_error.php');
	if ($exit == true) {
		die();
	}
}

function debug_info() {
	//if (c('debug_mode')) {

	return include(ADMIN_VIEWS_PATH . 'debug.php');
	// }
}

function remove_slashes_from_array($arr) {
	if (!empty($arr)) {
		$ret = array();

		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				$v = remove_slashes_from_array($v);
			} else {
				$v = htmlspecialchars_decode($v);
				// $v = htmlspecialchars_decode ( $v );
				// $v = html_entity_decode ( $v, ENT_NOQUOTES );
				// $v = html_entity_decode( $v );
				$v = stripslashes($v);
			}

			$ret[$k] = $v;
		}

		return $ret;
	}
}

if (!function_exists('pathToURL')) {
	function pathToURL($path) {
		// var_dump($path);
		$path = str_ireplace(MW_ROOTPATH, '', $path);
		$path = str_replace('\\', '/', $path);
		$path = str_replace('//', '/', $path);
		//var_dump($path);
		return site_url($path);
	}

}
if (!function_exists('dir2url')) {
	function dir2url($path) {
		return pathToURL($path);
	}

}
if (!function_exists('dirToURL')) {
	function dirToURL($path) {
		return pathToURL($path);
	}

}


function __encrypt($sData, $sKey = 'mysecretkey') {
	$sResult = '';
	for ($i = 0; $i < strlen($sData); $i++) {
		$sChar    = substr($sData, $i, 1);
		$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
		$sChar    = chr(ord($sChar) + ord($sKeyChar));
		$sResult .= $sChar;
	}
	return encode_base64($sResult);
}

function __decrypt($sData, $sKey = 'mysecretkey') {
	$sResult = '';
	$sData   = decode_base64($sData);
	for ($i = 0; $i < strlen($sData); $i++) {
		$sChar    = substr($sData, $i, 1);
		$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
		$sChar    = chr(ord($sChar) - ord($sKeyChar));
		$sResult .= $sChar;
	}
	return $sResult;
}


function encode_base64($sData) {
	$sBase64 = base64_encode($sData);
	return substr(strtr($sBase64, '+/', '-_'), 0, -2);
}

function decode_base64($sData) {
	$sBase64 = strtr($sData, '-_', '+/');
	return base64_decode($sBase64 . '==');
}



function simple_crypt($key, $string, $action = 'encrypt') {
	$res = '';
	if ($action !== 'encrypt') {
		$string = base64_decode($string);
	}
	for ($i = 0; $i < strlen($string); $i++) {
		$c = ord(substr($string, $i));
		if ($action == 'encrypt') {
			$c += ord(substr($key, (($i + 1) % strlen($key))));
			$res .= chr($c & 0xFF);
		} else {
			$c -= ord(substr($key, (($i + 1) % strlen($key))));
			$res .= chr(abs($c) & 0xFF);
		}
	}
	if ($action == 'encrypt') {
		$res = base64_encode($res);
	}
	return $res;
}


function encrypt_var($var, $key = false) {
	if ($var == '') {
		return '';
	}
	if ($key == false) {
		$key = md5(dirname(__FILE__));
	}
	$var = encode_var($var);


	return __encrypt($var, $key);




	//  $var = base64_encode($var);
	if (function_exists('mcrypt_encrypt')) {
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $var, MCRYPT_MODE_CBC, md5(md5($key))));
	} else {
		$encrypted = base64_encode($var);
	}

	return $encrypted;
}

function decrypt_var($var, $key = false) {
	if ($var == '') {
		return '';
	}
	if ($key == false) {
		$key = md5(dirname(__FILE__));
	}





	$var = __decrypt($var, $key);


	try {
		$var = decode_var($var);
	}
	catch (Exception $exc) {
		return false;
	}

	return $var;



	if (function_exists('mcrypt_decrypt')) {
		$var = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($var), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	} else {
		$var = base64_decode($var);
	}



	//  $var = base64_decode($var);
	//$var = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($var), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

	try {
		$var = @unserialize($var);
	}
	catch (Exception $exc) {
		return false;
	}




	return $var;
}




function str_replace_once($needle, $replace, $haystack) {
	// Looks for the first occurence of $needle in $haystack
	// and replaces it with $replace.
	$pos = strpos($haystack, $needle);
	if ($pos === false) {
		// Nothing found
		return $haystack;
	}
	return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function get_file_extension($LoSFileName) {
	$LoSFileExtensions = substr($LoSFileName, strrpos($LoSFileName, '.') + 1);
	return $LoSFileExtensions;
}


function no_ext($filename) {
	$filebroken = explode('.', $filename);
	array_pop($filebroken);

	return implode('.', $filebroken);
	;
}

function encode_var($var) {
	if ($var == '') {
		return '';
	}

	$var = json_encode($var);
	$var = base64_encode($var);
	return $var;
}

function decode_var($var) {
	if ($var == '') {
		return '';
	}
	$var = base64_decode($var);

	try {
		$var = @json_decode($var, 1);
	}
	catch (Exception $exc) {
		return false;
	}



	//$var = unserialize($var);
	return $var;
}

/**
 * Trims a entire array recursivly.
 *
 * @author Jonas John
 * @version 0.2
 * @link http://www.jonasjohn.de/snippets/php/trim-array.htm
 * @param array $Input
 *        	Input array
 */
function array_trim($Input) {
	if (!is_array($Input))
		return trim($Input);
	return array_map('array_trim', $Input);
}

function safe_redirect($url) {
	if (trim($url) == '') {
		return false;
	}
	$url = str_ireplace('Location:', '', $url);
	$url = trim($url);
	if (headers_sent()) {
		print '<meta http-equiv="refresh" content="0;url=' . $url . '">';
	} else {
		header('Location: ' . $url);
	}
	exit();
}

function session_set($name, $val) {
	if (!headers_sent()) {
		if (!isset($_SESSION)) {
			session_start();
		}
		if ($val == false) {
			session_del($name);
		} else {
			$is_the_same = session_get($name);
			if ($is_the_same != $val) {
				$_SESSION[$name] = $val;
			}
		}
	}
}

function session_get($name) {
 
	if (!headers_sent()) {
		if (!isset($_SESSION)) {
			session_start();
		}
	}
	
	// 
	if (isset($_SESSION[$name])) {
		return $_SESSION[$name];
	} else {
		return false;
	}
}

function session_del($name) {
	if (isset($_SESSION[$name])) {
		unset($_SESSION[$name]);
	}
}

function session_end() {
	$_SESSION = array();
	session_destroy();
}



$mw_static_option_groups = array();
function static_option_get($key, $option_group = "global") {
	$option_group_disabled = mw_var('static_option_disabled_' . $option_group);
	if ($option_group_disabled == true) {
		return false;
	}
	global $mw_static_option_groups;
	$option_group = trim($option_group);
	$option_group = str_replace('..', '', $option_group);

	$fname = $option_group . '.php';

	$dir_name = DBPATH_FULL . 'options' . DS;
	$dir_name_and_file = $dir_name . $fname;
	$key = trim($key);
	
	
	if(isset($mw_static_option_groups[$option_group]) and isset($mw_static_option_groups[$option_group][$key])){
		return ($mw_static_option_groups[$option_group][$key]);
	}
	
	
	
	
	
	if (is_file($dir_name_and_file)) {
		$ops_array = file_get_contents($dir_name_and_file);
		if ($ops_array != false) {
			$ops_array = str_replace(CACHE_CONTENT_PREPEND, '', $ops_array);
			if ($ops_array != '') {
				$ops_array = unserialize($ops_array);
				if (isarr($ops_array)) {
					$all_options = $ops_array;
					$mw_static_option_groups[$option_group] = $all_options;
					//mw_var('option_disabled_' . $option_group);
					if(isset($mw_static_option_groups[$option_group]) and isset($mw_static_option_groups[$option_group][$key])){
						return ($mw_static_option_groups[$option_group][$key]);
					} else {
						$mw_static_option_groups[$option_group][$key] = false;
					}
	
				}
			}
		}
	} else {
		mw_var('static_option_disabled_' . $option_group, true);
	}

}

function static_option_save($data) {
	 	
		if (MW_IS_INSTALLED == true) {
	// only_admin_access();
		}
	$data = parse_params($data);

	if (!isset($data['option_key']) or !isset($data['option_value'])) {
		exit("Error: no option_key or option_value");
	}
	if (!isset($data['option_group'])) {
		$data['option_group'] = 'global';
	}
	$data['option_group'] = trim($data['option_group']);
	$data['option_key'] = trim($data['option_key']);
	$data['option_value'] = (htmlentities($data['option_value']));
	//d($data);

	$data['option_group'] = str_replace('..', '', $data['option_group']);

	$fname = $data['option_group'] . '.php';

	//	$dir_name = DBPATH_FULL . 'options' . DS . $data['option_group'] . DS;

	$dir_name = DBPATH_FULL . 'options' . DS;
	$dir_name_and_file = $dir_name . $fname;
	if (is_dir($dir_name) == false) {
		mkdir_recursive($dir_name);
	}
	$data_to_serialize = array();
	if (is_file($dir_name_and_file)) {
		$ops_array = file_get_contents($dir_name_and_file);
		if ($ops_array != false) {
			$ops_array = str_replace(CACHE_CONTENT_PREPEND, '', $ops_array);
			if ($ops_array != '') {
				$ops_array = unserialize($ops_array);
				if (isarr($ops_array)) {
					$data_to_serialize = $ops_array;
				}
			}
		}
		//d($ops_array);
	}

	$data_to_serialize[$data['option_key']] = $data['option_value'];
	//	d($data_to_serialize);
	$data_to_serialize = serialize($data_to_serialize);

	$option_save_string = CACHE_CONTENT_PREPEND . $data_to_serialize;

	$cache = file_put_contents($dir_name_and_file, $option_save_string);
	return $cache;
}

function recursive_remove_directory($directory, $empty = true) {
	// if the path has a slash at the end we remove it here
	if (substr($directory, -1) == DIRECTORY_SEPARATOR) {
		$directory = substr($directory, 0, -1);
	}

	// if the path is not valid or is not a directory ...
	if (!file_exists($directory) || !is_dir($directory)) {
		// ... we return false and exit the function
		return FALSE;

		// ... if the path is not readable
	} elseif (!is_readable($directory)) {
		// ... we return false and exit the function
		return FALSE;

		// ... else if the path is readable
	} else {
		// we open the directory
		$handle = opendir($directory);

		// and scan through the items inside
		while (FALSE !== ($item = readdir($handle))) {
			// if the filepointer is not the current directory
			// or the parent directory
			if ($item != '.' && $item != '..') {
				// we build the new path to delete
				$path = $directory . DIRECTORY_SEPARATOR . $item;

				// if the new path is a directory
				if (is_dir($path)) {
					// we call this function with the new path
					recursive_remove_directory($path, $empty);
					// if the new path is a file
				} else {
					//   $path = normalize_path($path, false);
					try {
						@unlink($path);
					}
					catch (Exception $e) {
					}
				}
			}
		}

		// close the directory
		closedir($handle);

		// if the option to empty is not set to true
		if ($empty == FALSE) {
			@rmdir($directory);
			// try to delete the now empty directory
			//            if (!rmdir($directory)) {
			//
			//                // return false if not possible
			//                return FALSE;
			//            }
		}

		// return success
		return TRUE;
	}
}

function is_arr($var) {
	return isarr($var);
}

function isarr($var) {
	if (is_array($var) and !empty($var)) {
		return true;
	} else {
		return false;
	}
}

/**
 *
 *
 * Recursive glob()
 *
 * @access public
 * @package utils
 * @subpackage	files
 * @category	files
 *
 * @version 1.0
 *
 *
 * @param int $pattern
 * the pattern passed to glob()
 * @param int $flags
 * the flags passed to glob()
 * @param string $path
 * the path to scan
 * @return mixed
 * an array of files in the given path matching the pattern.
 */
function rglob($pattern = '*', $flags = 0, $path = '') {
	if (!$path && ($dir = dirname($pattern)) != '.') {
		if ($dir == '\\' || $dir == '/')
			$dir = '';
		return rglob(basename($pattern), $flags, $dir . DS);
	}
	$paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
	$files = glob($path . $pattern, $flags);
	foreach ($paths as $p)
		$files = array_merge($files, rglob($pattern, $flags, $p . DS));
	return $files;



}

















function directory_tree_grep($q, $path) {
	$ret = array();
	$fp  = opendir($path);
	while ($f = readdir($fp)) {
		if (preg_match("#^\.+$#", $f))
			continue; // ignore symbolic links
		$file_full_path = $path . DS . $f;

		if (is_dir($file_full_path)) {
			$ret[] = directory_tree_grep($q, $file_full_path);
		} else if (stristr(file_get_contents($file_full_path), $q)) {
			$ret[] = $file_full_path;
		}
	}
	return $ret;
}


function array_values_recursive($ary) {
	$lst = array();
	foreach (array_keys($ary) as $k) {
		$v = $ary[$k];
		if (is_scalar($v)) {
			$lst[] = $v;
		} elseif (is_array($v)) {
			$lst = array_merge($lst, array_values_recursive($v));
		}
	}
	return $lst;
}
function directory_tree($path = '.', $search = false) {
	$return = '';
	if ($search == false) {
		$return = directory_tree_full_path($path);
	} else {
		$res = directory_tree_grep($search, $path);
		if (!empty($res)) {
			$res1 = array_values_recursive($res);
			foreach ($res1 as $value) {
				$return .= directory_tree_full_path($value);
			}
		}
	}


	$return = str_replace($path, '', $return);
	$path   = str_replace('\\', '/', $path);
	$return = str_replace($path, '', $return);
	return $return;
}


function directory_tree_full_path($path = '.') {
	$return = '';
	$queue  = array();

	$is_hidden = substr($path, 0, 2);
	if ($is_hidden == '__') {
		return $return;
	}


	$return .= "<ul class='directory_tree'>";

	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if (is_dir($path . $file) && $file != '.' && $file != '..')
					$return .= directory_tree_printSubDir($file, $path, $queue);
				else if ($file != '.' && $file != '..')
					$queue[] = $file;
				asort($queue);
			}

			$return .= directory_tree_printQueue($queue, $path);

			//	 $return = str_replace($path, '', $return);

		}
	} else {
		if (is_file($path)) {
			//	$path1 = dirname($path);
			$queue[] = $path;
			$return .= directory_tree_printQueue($queue);

		}
	}
	$return .= "</ul>";

	return $return;
}




function directory_tree_printQueue($queue, $path = '') {
	$return = '';
	if (empty($queue)) {
		return $return;
	}
	foreach ($queue as $file) {
		$return .= directory_tree_printFile($file, $path);
	}
	return $return;
}

function directory_tree_printFile($file, $path) {
	$return = '';
	$file1  = no_ext($file);
	$file1  = ltrim($file1, '_');
	
	
	
	
	if($file1 != 'index'){
	if(strlen($file1) > 3){
 	$pos = strpos($file1, '_', 1);
 
	if ($pos != false) {
	     $substr = substr($file1, 0, $pos);
		if(intval($substr) > 0){
			 $file1 = substr($file1, $pos, strlen($file1));
			 $file1  = ltrim($file1, '_');
		}
		//
		}
	}
	
	$file1  = str_replace('_', ' ', $file1);
	
	//$fp = dirname($file);

	$path = str_replace('\\', '/', $path);

	$return .= "<li class='directory_tree_is_file'><a href=\"?file=" . $path . $file . "\">$file1</a></li>";
	}
	
	
	
	
	return $return;
}

function directory_tree_printSubDir($dir, $path) {
	$path = str_replace('\\', '/', $path);
	
	$file1 = $dir;
	$pos = strpos($file1, '_', 1);
 
	if ($pos != false) {
	     $substr = substr($file1, 0, $pos);
		if(intval($substr) > 0){
			 $file1 = substr($file1, $pos, strlen($file1));
			 $file1  = ltrim($file1, '_');
		}
		//
	}
		
	
	$file1  = str_replace('_', ' ', $file1);
	
	
	

	$return = '';
	$return .= "<li class='directory_tree_is_folder'><a href=\"?path=" . $path . $dir . "\" class=\"toggle\">$file1</a>";
	$return .= directory_tree_full_path($path . $dir . DS);
	$return .= "</li>";
	return $return;
}



// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE, $full_path = false) {
	if ($fp = @opendir($source_dir)) {
		$filedata   = array();
		$new_depth  = $directory_depth - 1;
		$source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		while (FALSE !== ($file = readdir($fp))) {
			// Remove '.', '..', and hidden files [optional]
			if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
				continue;
			}

			if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
				$filedata[$file] = directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden, $full_path);
			} else {
				if ($full_path == false) {
					$filedata[] = $file;
				} else {
					$filedata[] = $source_dir . $file;
				}

			}
		}

		closedir($fp);
		return $filedata;
	}

	return FALSE;
}


function percent($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count  = number_format($count2, 0);
	echo $count;
}

function lipsum($number_of_characters = false) {
  if($number_of_characters == false){
    $number_of_characters = 100;
  }

  $lipsum = array(
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et sapien varius gravida. Fusce porttitor consectetur risus ut tincidunt. Maecenas pellentesque nulla sodales enim consectetur commodo. Aliquam non dui leo, adipiscing posuere metus. Duis adipiscing auctor lorem ut pulvinar. Donec non magna massa, feugiat commodo felis. Donec ut nibh elit. Nulla pellentesque nulla diam, vitae consectetur neque.',
    'Etiam sed lorem augue. Vivamus varius tristique bibendum. Phasellus vitae tempor augue. Maecenas consequat commodo euismod. Aenean a lorem nec leo dignissim ultricies sed quis nisi. Fusce pellentesque tellus lectus, eu varius felis. Mauris lacinia facilisis metus, sed sollicitudin quam faucibus id.',
    'Donec ultrices cursus erat, non pulvinar lectus consectetur eu. Proin sodales risus a ante aliquet vel cursus justo viverra. Duis vel leo felis. Praesent hendrerit, sem vitae scelerisque blandit, enim neque pulvinar mi, vel lobortis elit dui vel dui. Donec ac sem sed neque consequat egestas. Curabitur pellentesque consequat ante, quis laoreet enim gravida eu. Donec varius, nisi non bibendum pellentesque, felis metus pretium ipsum, non vulputate eros magna ac sapien. Donec tincidunt porta tortor, et ornare enim facilisis vitae. Nulla facilisi. Cras ut nisi ac dolor lacinia tempus at sed eros. Integer vehicula arcu in augue adipiscing accumsan. Morbi placerat consectetur sapien sed gravida. Sed fringilla elit nisl, nec molestie felis. Nulla aliquet diam vitae diam iaculis porttitor.',
    'Integer eget tortor nulla, non dapibus erat. Sed ultrices consectetur quam at scelerisque. Nullam varius hendrerit nisl, ac cursus mi bibendum eu. Phasellus varius fermentum massa, sit amet ornare quam malesuada in. Quisque ac massa sem. Nulla eu erat metus, non tincidunt nibh. Nam consequat interdum nulla, at congue libero tincidunt eget. Sed cursus nulla eu felis faucibus porta. Nam sed lacus eros, nec pellentesque lorem. Sed dapibus, sapien mattis sollicitudin bibendum, libero augue dignissim felis, eget elementum felis nulla in velit. Donec varius, lectus non suscipit sollicitudin, urna est hendrerit nulla, vel vehicula arcu sem volutpat sapien. Ut nisi ipsum, accumsan vestibulum pulvinar eu, sodales id lacus. Nulla iaculis eros sit amet lectus tincidunt mattis. Ut eu nisl sit amet eros vestibulum imperdiet ut vel lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
    'In hac habitasse platea dictumst. Aenean vehicula auctor eros non tincidunt. Donec tempor arcu ac diam sagittis mattis. Aenean eget augue nulla, non volutpat lorem. Praesent ut cursus magna. Mauris consequat suscipit nisi. Integer eu venenatis ligula. Maecenas leo risus, lacinia et auctor aliquet, aliquet in mi.',
    'Aliquam tincidunt dapibus augue, et vulputate dui aliquet et. Praesent pharetra mauris eu justo dignissim venenatis ornare nec nisl. Aliquam justo quam, varius eget congue vel, congue eget est. Ut nulla felis, luctus imperdiet molestie et, commodo vel nulla. Morbi at nulla dapibus enim bibendum aliquam non et ipsum. Phasellus sed cursus justo. Praesent sit amet metus lorem. Vivamus ut lorem dapibus turpis rhoncus pharetra. Donec in lacus sagittis nisl tempor sagittis quis a orci. Nam volutpat condimentum ante ac facilisis. Cras sem magna, vulputate id consequat rhoncus, suscipit non justo. In fringilla dignissim cursus.',
    'Nunc fringilla orci tellus, et euismod lorem. Ut quis turpis lacus, ac elementum lorem. Praesent fringilla, metus nec tincidunt consequat, sem sapien hendrerit nisi, nec feugiat libero risus a nisl. Duis arcu magna, ullamcorper et semper vitae, tincidunt nec libero. Etiam sed lacus ante. In imperdiet arcu eget elit commodo ut malesuada sem congue. Quisque porttitor porta sagittis. Nam porta elit sit amet mauris fermentum eu feugiat ipsum pretium. Maecenas sollicitudin aliquam eros, ut pretium nunc faucibus quis. Mauris id metus vitae libero viverra adipiscing quis ut nulla. Pellentesque posuere facilisis nibh, facilisis vehicula felis facilisis nec.',
    'Etiam pharetra libero nec erat pellentesque laoreet. Sed eu libero nec nisl vehicula convallis nec non orci. Aenean tristique varius nisl. Cras vel urna eget enim placerat vehicula quis sed velit. Quisque lacinia sagittis lectus eget sagittis. Pellentesque cursus suscipit massa vel ultricies. Quisque hendrerit lobortis elit interdum feugiat. Sed posuere volutpat erat vel lobortis. Vivamus laoreet mattis varius. Fusce tincidunt accumsan lorem, in viverra lectus dictum eu. Integer venenatis tristique dolor, ac porta lacus pellentesque pharetra. Suspendisse potenti. Ut dolor dolor, sollicitudin in auctor nec, facilisis non justo. Mauris cursus euismod gravida. In at orci in sapien laoreet euismod.',
    'Mauris purus urna, vulputate in malesuada ac, varius eget ante. Integer ultricies lacus vel magna dictum sit amet euismod enim dictum. Aliquam iaculis, ipsum at tempor bibendum, dolor tortor eleifend elit, sed fermentum magna nibh a ligula. Phasellus ipsum nisi, porta quis pellentesque sit amet, dignissim vel felis. Quisque condimentum molestie ligula, ac auctor turpis facilisis ac. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent molestie leo velit. Sed sit amet turpis massa. Donec in tortor quis metus cursus iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac habitasse platea dictumst. Proin leo nisl, faucibus non sollicitudin et, commodo id diam. Aliquam adipiscing, lorem a fringilla blandit, felis dui tristique ligula, vitae eleifend orci diam eget quam. Aliquam vulputate gravida leo eget eleifend. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;',
    'Etiam et consectetur justo. Integer et ante dui, quis rutrum massa. Fusce nibh nisl, congue sit amet tempor vitae, ornare et nisi. Nulla mattis nisl ut ligula sagittis aliquam. Curabitur ac augue at velit facilisis venenatis quis sit amet erat. Donec lacus elit, auctor sed lobortis aliquet, accumsan nec mi. Quisque non est ante. Morbi vehicula pulvinar magna, quis luctus tortor varius et. Donec hendrerit nulla posuere odio lobortis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dapibus magna id ante sodales tempus. Maecenas at eleifend nulla.',
    'Sed eget gravida magna. Quisque vulputate diam nec libero faucibus vitae fringilla ligula lobortis. Aenean congue, dolor ut dapibus fermentum, justo lectus luctus sem, et vestibulum lectus orci non mauris. Vivamus interdum mauris at diam scelerisque porta mollis massa hendrerit. Donec condimentum lacinia bibendum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam neque dolor, faucibus sed varius sit amet, vulputate vitae nunc.',
    'Etiam in lorem congue nunc sollicitudin rhoncus vel in metus. Integer luctus semper sem ut interdum. Sed mattis euismod diam, at porta mauris laoreet quis. Nam pellentesque enim id mi vestibulum gravida in vel libero. Nulla facilisi. Morbi fringilla mollis malesuada. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum sagittis consectetur auctor. Phasellus convallis turpis eget diam tristique feugiat. In consectetur quam faucibus purus suscipit euismod quis sed quam. Curabitur eget sodales dui. Quisque egestas diam quis sapien aliquet tincidunt.',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam velit est, imperdiet ac posuere non, dictum et nunc. Duis iaculis lacus in libero lacinia ut consectetur nisi facilisis. Fusce aliquet nisl id eros dapibus viverra. Phasellus eget ultrices nisl. Nullam euismod tortor a metus hendrerit convallis. Donec dolor magna, fringilla in sollicitudin sit amet, tristique eget elit. Praesent adipiscing magna in ipsum vulputate non lacinia metus vestibulum. Aenean dictum suscipit mollis. Nullam tristique commodo dapibus. Fusce in tellus sapien, at vulputate justo. Nam ornare, lorem sit amet condimentum ultrices, ipsum velit tempor urna, tincidunt convallis sapien enim eget leo. Proin ligula tellus, ornare vitae scelerisque vitae, fringilla fermentum sem. Phasellus ornare, diam sed luctus condimentum, nisl felis ultricies tortor, ac tempor quam lacus sit amet lorem. Nunc egestas, nibh ornare dictum iaculis, diam nisl fermentum magna, malesuada vestibulum est mauris quis nisl. Ut vulputate pharetra laoreet.',
    'Donec mattis mauris et dolor commodo et pellentesque libero congue. Sed tristique bibendum augue sed auctor. Sed in ante enim. In sed lectus massa. Nulla imperdiet nisi at libero faucibus sagittis ac ac lacus. In dui purus, sollicitudin tempor euismod euismod, dapibus vehicula elit. Aliquam vulputate, ligula non dignissim gravida, odio elit ornare risus, a euismod est odio nec ipsum. In hac habitasse platea dictumst. Mauris posuere ultrices mattis. Etiam vitae leo vitae nunc porta egestas at vitae nibh. Sed pharetra, magna nec bibendum aliquam, dolor sapien consequat neque, sit amet euismod orci elit vitae enim. Sed erat metus, laoreet quis posuere id, congue id velit. Mauris ac velit vel ipsum dictum ornare eget vitae arcu. Donec interdum, neque at lacinia imperdiet, ante libero convallis quam, pellentesque faucibus quam dolor id est. Ut cursus facilisis scelerisque. Sed vitae ligula in purus malesuada porta.',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vestibulum vestibulum metus. Integer ultrices ultricies pellentesque. Nulla gravida nisl a magna gravida ullamcorper. Vestibulum accumsan eros vel massa euismod in aliquam felis suscipit. Ut et purus enim, id congue ante. Mauris magna lectus, varius porta pellentesque quis, dignissim in est. Nulla facilisi. Nullam in malesuada mauris. Ut fermentum orci neque. Aliquam accumsan justo a lacus vestibulum fermentum. Donec molestie, quam id adipiscing viverra, massa velit aliquam enim, vitae dapibus turpis libero id augue. Quisque mi magna, mollis vel tincidunt nec, adipiscing sed metus. Maecenas tincidunt augue quis felis dapibus nec elementum justo fringilla. Sed eget massa at sapien tincidunt porta eu id sapien.'
  );
  $rand = rand(0, (sizeof($lipsum)-1));

  return character_limiter($lipsum[$rand], $number_of_characters, '');
}

api_expose('pixum_img');


function _d($a) {
	$rand = uniqid();
	//echo "<div style='display:none' id='d-" . $rand . "'>";
	//var_dump($a);
	$js = json_encode($a);
	//print "</div>";
	print "<script>
	$(document).ready(function(){
	window.console && console.log
 
&& console.log(".$js.");
});
</script>
";
	
	
	//print "<script>$(document).ready(function(){var x = mw.$('#d-" . $rand . "').html();var xx = mw.tools.modal.init({html:'<pre>'+x+'</pre>'});$(xx.main).css({left:'auto',right:0})});</script>";
}



function mw_date($date){
	 	$date_format = get_option('date_format','website');
		if($date_format == false){
		$date_format = "Y-m-d H:i:s";	
		}
		
		if(isset( $date) and  trim($date) != ''){
			$date=  date($date_format, strtotime($date) );
			return $date;
			}
}

/***********************************
* string_format
 *
 *
 * To Use:

print string_format("(###)###-####", "4015551212");
will print out:
(401)555-1212

Hope this helps someone,
***********************************/
function string_format($format, $string, $placeHolder = "#")
{
	$numMatches = preg_match_all("/($placeHolder+)/", $format, $matches);
	foreach ($matches[0] as $match)
	{
		$matchLen = strlen($match);
		$format = preg_replace("/$placeHolder+/", substr($string, 0, $matchLen), $format, 1);
		$string = substr($string, $matchLen);
	}
	return $format;
}





if(!function_exists('money_format')){
	function money_format($format, $number)
	{
		$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
			'(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
			if (setlocale(LC_MONETARY, 0) == 'C') {
				setlocale(LC_MONETARY, '');
			}
			$locale = localeconv();
			preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
			foreach ($matches as $fmatch) {
				$value = floatval($number);
				$flags = array(
					'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
					$match[1] : ' ',
					'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
					'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
						$match[0] : '+',
						'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
						'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
						);
				$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
				$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
				$right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
				$conversion = $fmatch[5];

				$positive = true;
				if ($value < 0) {
					$positive = false;
					$value  *= -1;
				}
				$letter = $positive ? 'p' : 'n';

				$prefix = $suffix = $cprefix = $csuffix = $signal = '';

				$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
				switch (true) {
					case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
					$prefix = $signal;
					break;
					case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
					$suffix = $signal;
					break;
					case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
					$cprefix = $signal;
					break;
					case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
					$csuffix = $signal;
					break;
					case $flags['usesignal'] == '(':
						case $locale["{$letter}_sign_posn"] == 0:
						$prefix = '(';
							$suffix = ')';
break;
}
if (!$flags['nosimbol']) {
	$currency = $cprefix .
	($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
	$csuffix;
} else {
	$currency = '';
}
$space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

$value = number_format($value, $right, $locale['mon_decimal_point'],
	$flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
$value = @explode($locale['mon_decimal_point'], $value);

$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
if ($left > 0 && $left > $n) {
	$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
}
$value = implode($locale['mon_decimal_point'], $value);
if ($locale["{$letter}_cs_precedes"]) {
	$value = $prefix . $currency . $space . $value . $suffix;
} else {
	$value = $prefix . $value . $space . $currency . $suffix;
}
if ($width > 0) {
	$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
		STR_PAD_RIGHT : STR_PAD_LEFT);
}

$format = str_replace($fmatch[0], $value, $format);
}
return $format;
}

}
function currency_symbol($curr,$key=3) {
	return curency_symbol($curr,$key);
}
function curency_symbol($curr=false,$key=3) {


	if($curr == false){
		$curr = get_option('currency', 'payments');
	}



	$all_cur = curencies_list();
	if(isarr($all_cur)){
		foreach ($all_cur as $value) {
			if(in_array($curr, $value)){
				if($key == false){
					return $value;
				} else {
					return $value[$key];
				}

			}
		}
	}

}
function currencies_list_paypal() {
	$curencies = array('USD','EUR','GBP','AUD','CAD','JPY','NZD','CHF','HKD','SGD','SEK','DKK','PLN','NOK','HUF','CZK','ILS','MXN','MYR','BRL','PHP','TWD','THB','TRY');

	return $curencies;
}
function currencies_list() {
	return currencies_list();
}

function curencies_list() {



	$curencies_list_memory = mw_var('curencies_list');
	if($curencies_list_memory != false){
		return $curencies_list_memory;
	}

	$row = 1;

	$cur_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'libs' . DS . 'currencies.csv';
	//d($cur_file);
	if (is_file($cur_file)) {
		if (($handle = fopen($cur_file, "r")) !== FALSE) {
			$res = array();
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$itm = array();

				$num = count($data);
				//   echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c = 0; $c < $num; $c++) {
					//echo $data[$c] . "<br />\n";
					$itm[] = $data[$c];
				}

				$res[] = $itm;
			}

			fclose($handle);
			mw_var('curencies_list', $res);
			return $res;
		}
	}
}
function currency_online_convert_rate($from, $to){



	$remote_host = 'http://api.microweber.net';
	$service = "/service/currency/?from=" . $from."&to=" . $to;
	$remote_host_s = $remote_host . $service;
	// d($remote_host_s);
	$get_remote = url_download($remote_host_s);
	if ($get_remote != false) {
		return floatval($get_remote);
	}

}
function currency_format($amount, $curr = false){

	if($curr == false){

		$curr = get_option('currency', 'payments');
	}



	$amount = floatval($amount);
	$sym = curency_symbol($curr);
	switch ($curr){
		case "EUR":
		$ret = "&euro; ".number_format($amount, 2, ",", " ");
		break;
		case "BGN":
		case "RUB":
		$ret = number_format($amount, 2, ".", " ").' '.$sym;
		break;
		case "US":
		case "USD":
		$ret = "&#36; ".number_format($amount, 2, ".", ",");
		break;
		default:
		//  print $sym;
		$ret =  $sym.' '.number_format($amount, 2, ".", ",");
		break;
	}
	return $ret;

}










// Thanks to http://www.eval.ca/articles/php-pluralize (MIT license)
//           http://dev.rubyonrails.org/browser/trunk/activesupport/lib/active_support/inflections.rb (MIT license)
//           http://www.fortunecity.com/bally/durrus/153/gramch13.html
//           http://www2.gsu.edu/~wwwesl/egw/crump.htm
//
// Changes (12/17/07)
//   Major changes
//   --
//   Fixed irregular noun algorithm to use regular expressions just like the original Ruby source.
//       (this allows for things like fireman -> firemen
//   Fixed the order of the singular array, which was backwards.
//
//   Minor changes
//   --
//   Removed incorrect pluralization rule for /([^aeiouy]|qu)ies$/ => $1y
//   Expanded on the list of exceptions for *o -> *oes, and removed rule for buffalo -> buffaloes
//   Removed dangerous singularization rule for /([^f])ves$/ => $1fe
//   Added more specific rules for singularizing lives, wives, knives, sheaves, loaves, and leaves and thieves
//   Added exception to /(us)es$/ => $1 rule for houses => house and blouses => blouse
//   Added excpetions for feet, geese and teeth
//   Added rule for deer -> deer

// Changes:
//   Removed rule for virus -> viri
//   Added rule for potato -> potatoes
//   Added rule for *us -> *uses
function pluralize( $string )
{

	return MWText::pluralize($string);
}
class MWText
{
	static $plural = array(
		'/(quiz)$/i'               => "$1zes",
		'/^(ox)$/i'                => "$1en",
		'/([m|l])ouse$/i'          => "$1ice",
		'/(matr|vert|ind)ix|ex$/i' => "$1ices",
		'/(x|ch|ss|sh)$/i'         => "$1es",
		'/([^aeiouy]|qu)y$/i'      => "$1ies",
		'/(hive)$/i'               => "$1s",
		'/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
		'/(shea|lea|loa|thie)f$/i' => "$1ves",
		'/sis$/i'                  => "ses",
		'/([ti])um$/i'             => "$1a",
		'/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
		'/(bu)s$/i'                => "$1ses",
		'/(alias)$/i'              => "$1es",
		'/(octop)us$/i'            => "$1i",
		'/(ax|test)is$/i'          => "$1es",
		'/(us)$/i'                 => "$1es",
		'/s$/i'                    => "s",
		'/$/'                      => "s"
		);

	static $singular = array(
		'/(quiz)zes$/i'             => "$1",
		'/(matr)ices$/i'            => "$1ix",
		'/(vert|ind)ices$/i'        => "$1ex",
		'/^(ox)en$/i'               => "$1",
		'/(alias)es$/i'             => "$1",
		'/(octop|vir)i$/i'          => "$1us",
		'/(cris|ax|test)es$/i'      => "$1is",
		'/(shoe)s$/i'               => "$1",
		'/(o)es$/i'                 => "$1",
		'/(bus)es$/i'               => "$1",
		'/([m|l])ice$/i'            => "$1ouse",
		'/(x|ch|ss|sh)es$/i'        => "$1",
		'/(m)ovies$/i'              => "$1ovie",
		'/(s)eries$/i'              => "$1eries",
		'/([^aeiouy]|qu)ies$/i'     => "$1y",
		'/([lr])ves$/i'             => "$1f",
		'/(tive)s$/i'               => "$1",
		'/(hive)s$/i'               => "$1",
		'/(li|wi|kni)ves$/i'        => "$1fe",
		'/(shea|loa|lea|thie)ves$/i'=> "$1f",
		'/(^analy)ses$/i'           => "$1sis",
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",
		'/([ti])a$/i'               => "$1um",
		'/(n)ews$/i'                => "$1ews",
		'/(h|bl)ouses$/i'           => "$1ouse",
		'/(corpse)s$/i'             => "$1",
		'/(us)es$/i'                => "$1",
		'/s$/i'                     => ""
		);

static $irregular = array(
	'move'   => 'moves',
	'foot'   => 'feet',
	'goose'  => 'geese',
	'sex'    => 'sexes',
	'child'  => 'children',
	'man'    => 'men',
	'tooth'  => 'teeth',
	'person' => 'people'
	);

static $uncountable = array(
	'sheep',
	'fish',
	'deer',
	'series',
	'species',
	'money',
	'rice',
	'information',
	'equipment'
	);

public static function pluralize( $string )
{
        // save some time in the case that singular and plural are the same
	if ( in_array( strtolower( $string ), self::$uncountable ) )
		return $string;

        // check for irregular singular forms
	foreach ( self::$irregular as $pattern => $result )
	{
		$pattern = '/' . $pattern . '$/i';

		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string);
	}

        // check for matches using regular expressions
	foreach ( self::$plural as $pattern => $result )
	{
		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string );
	}

	return $string;
}

public static function singularize( $string )
{
        // save some time in the case that singular and plural are the same
	if ( in_array( strtolower( $string ), self::$uncountable ) )
		return $string;

        // check for irregular plural forms
	foreach ( self::$irregular as $result => $pattern )
	{
		$pattern = '/' . $pattern . '$/i';

		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string);
	}

        // check for matches using regular expressions
	foreach ( self::$singular as $pattern => $result )
	{
		if ( preg_match( $pattern, $string ) )
			return preg_replace( $pattern, $result, $string );
	}

	return $string;
}

public static function pluralize_if($count, $string)
{
	if ($count == 1)
		return "1 $string";
	else
		return $count . " " . self::pluralize($string);
}
}


function random_color(){
  return "#".sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
}
