<?php
if (!class_exists('rdate')) {
	    function rdate($th, $param, $time = 0)
		{
			$th->language->load('record/blog');
			if (intval($time) == 0)
				$time = time();
			$MonthNames = array(
				$th->language->get('text_january'),
				$th->language->get('text_february'),
				$th->language->get('text_march'),
				$th->language->get('text_april'),
				$th->language->get('text_may'),
				$th->language->get('text_june'),
				$th->language->get('text_july'),
				$th->language->get('text_august'),
				$th->language->get('text_september'),
				$th->language->get('text_october'),
				$th->language->get('text_november'),
				$th->language->get('text_december')
			);
			if (strpos($param, ' M ') === false)
				return date($param, $time);
			else {
				$str_begin  = date(utf8_substr($param, 0, utf8_strpos($param, 'M')), $time);
				$str_middle = $MonthNames[date('n', $time) - 1];
				$str_end    = date(utf8_substr($param, utf8_strpos($param, 'M') + 1, utf8_strlen($param)), $time);
				$str_date   = $str_begin . $str_middle . $str_end;
				return $str_date;
			}
		}
}

if (!class_exists('cmp_my_comment')) {
	class cmp_my_comment
	{
		var $key;
		var $ord;
		function __construct($key, $ord)
		{
			$this->key = $key;
			$this->ord = $ord;
		}
		function my_cmp($a, $b)
		{
			$key = $this->key;
			$ord = $this->ord;
			if ($key == 'date_available') {
				if (strtotime($a[$key]) > strtotime($b[$key])) {
					if ($ord == 'asc')
						return 1;
					if ($ord == 'desc')
						return -1;
				} //strtotime($a[$key]) > strtotime($b[$key])
				if (strtotime($b[$key]) > strtotime($a[$key])) {
					if ($ord == 'asc')
						return -1;
					if ($ord == 'desc')
						return 1;
				} //strtotime($b[$key]) > strtotime($a[$key])
			} //$key == 'date_available'
			if ($a[$key] > $b[$key]) {
				if ($ord == 'asc')
					return 1;
				if ($ord == 'desc')
					return -1;
			} //$a[$key] > $b[$key]
			if ($b[$key] > $a[$key]) {
				if ($ord == 'asc')
					return -1;
				if ($ord == 'desc')
					return 1;
			} //$b[$key] > $a[$key]
			return 0;
		}
	}
}

if (!function_exists('comp_field')) {
	function comp_field($a, $b)
	{
		if (!isset($a['field_order']) || $a['field_order'] == '') $a['field_order'] = '9999999';
		if (!isset($b['field_order']) || $b['field_order'] == '') $b['field_order'] = '9999999';
		$a['field_order'] = (int) $a['field_order'];
		$b['field_order'] = (int) $b['field_order'];
		if ($a['field_order'] > $b['field_order'])
			return 1;
		if ($b['field_order'] > $a['field_order'])
			return -1;
		return 0;
	}
}

if (!function_exists('sdesc')) {
	function sdesc($a, $b)
	{
		return (strcmp($a['sorthex'], $b['sorthex']));
	}
}

if (!function_exists('compare')) {
	function compare($a, $b)
	{
		if ($a['comment_id'] > $b['comment_id'])
			return 1;
		if ($b['comment_id'] > $a['comment_id'])
			return -1;
		return 0;
	}
}
if (!function_exists('compared')) {
	function compared($a, $b)
	{
		if ($a['comment_id'] > $b['comment_id'])
			return -1;
		if ($b['comment_id'] > $a['comment_id'])
			return 1;
		return 0;
	}
}

if (!function_exists('commd')) {
	function commd($a, $b)
	{
		if ($a['sort_order'] == '')
			$a['sort_order'] = 1000;
		if ($b['sort_order'] == '')
			$b['sort_order'] = 1000;
		if ($a['sort_order'] > $b['sort_order'])
			return 1;
		else
			return -1;
	}
}

if (!function_exists('comma')) {
	function comma($a, $b)
	{
		if ($a['sort_order'] == '')
			$a['sort_order'] = 1000;
		if ($b['sort_order'] == '')
			$b['sort_order'] = 1000;

		if ((int)$a['sort_order'] > (int)$b['sort_order'])
			return -1;
		else
			return 1;
	}
}



if (!function_exists('compareblogs')) {
	function compareblogs($a, $b)
	{
		if ($a['sort'] > $b['sort'])
			return 1;
		if ($b['sort'] > $a['sort'])
			return -1;
		return 0;
	}
}
if (!function_exists('my_sort_div_blogs')) {
	function my_sort_div_blogs($data, $parent = 0, $lev = -1)
	{
		$arr = $data[$parent];
		usort($arr, 'compareblogs');
		$lev = $lev + 1;
		for ($i = 0; $i < count($arr); $i++) {
			$arr[$i]['level']               = $lev;
			$z[]                            = $arr[$i];
			$z[count($z) - 1]['flag_start'] = 1;
			$z[count($z) - 1]['flag_end']   = 0;
			if (isset($data[$arr[$i]['blog_id']])) {
				$m = my_sort_div_blogs($data, $arr[$i]['blog_id'], $lev);
				$z = array_merge($z, $m);
			}
			if (isset($z[count($z) - 1]['flag_end']))
				$z[count($z) - 1]['flag_end']++;
			else
				$z[count($z) - 1]['flag_end'] = 1;
		}
		return $z;
	}
}

if (!function_exists('mkdirs')) {
	 function mkdirs($pathname, $mode = 0777, $index = FALSE)
	{
		$flag_save = false;
		$path_file = dirname($pathname);
		$name_file = basename($pathname);
		if (is_dir(dirname($path_file))) {
		} else {
			$this->mkdirs(dirname($pathname), $mode, $index);
		}
		if (is_dir($path_file)) {
			if (file_exists($path_file)) {
				$flag_save = true;
			}
		} else {
			umask(0);
			@mkdir($path_file, $mode);
			if (file_exists($path_file)) {
				$flag_save = true;
			}
			if ($index) {
				$accessFile = $path_file . "/" . $name_file;
				touch($accessFile);
				$accessWrite = fopen($accessFile, "wb");
				fwrite($accessWrite, 'access denied');
				fclose($accessWrite);
				if (file_exists($accessFile)) {
					$flag_save = true;
				} else {
					$flag_save = false;
				}
			}
		}
		return $flag_save;
	}
}


if (!function_exists('getCSSDir')) {

	function getCSSDir($css_dir)
	{
		$array_dir_cache = str_split($css_dir);
		$array_dir_app   = str_split(DIR_APPLICATION);
		$i               = 0;
		$dir_root        = '';
		while ($array_dir_cache[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_cache[$i];
			$i++;
		}
		$dir_cache = str_replace($dir_root, '', $css_dir);

		return $dir_cache;
	}
}


if (!function_exists('strpos_offset')) {
	function strpos_offset($needle, $haystack, $occurrence)
	{
		$arr = explode($needle, $haystack);
		switch ($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}
}

if (!function_exists('getCSSimage')) {
	function getCSSimage($file, $config_template)
	{
		$filecss = '';
		$httptheme = getHttpTheme($config_template);
		if (file_exists(DIR_TEMPLATE . $config_template . '/image/'.$file)) {
			$filecss = $httptheme . $config_template . '/image/'.$file;
		} else {
			$filecss = $httptheme.'default/image/'.$file;
		}
		return $filecss;
	}
}

if (!function_exists('getHttpTheme')) {
	function getHttpTheme($thi)
	{
		$array_dir_image = str_split(DIR_TEMPLATE);
		$array_dir_app   = str_split(DIR_SYSTEM);
		$i               = 0;
		$dir_root        = '';
		while ($array_dir_image[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_image[$i];
			$i++;
		}
		$dir_image = str_replace($dir_root, '', DIR_TEMPLATE);
		return "/".$dir_image;
	}
}
if (!function_exists('getHttpImage')) {
	function getHttpImage($thi)
	{
		$array_dir_image = str_split(DIR_IMAGE);
		$array_dir_app   = str_split(DIR_APPLICATION);
		$i               = 0;
		$dir_root        = '';
		while ($array_dir_image[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_image[$i];
			$i++;
		}
		$dir_image = str_replace($dir_root, '', DIR_IMAGE);
		if (isset($thi->request->server['HTTPS']) && (($thi->request->server['HTTPS'] == 'on') || ($thi->request->server['HTTPS'] == '1'))) {
			$http_image = HTTPS_SERVER . $dir_image;
		} else {
			$http_image = HTTP_SERVER . $dir_image;
		}
		return $http_image;
	}
}

if (!function_exists('print_my')) {
	function print_my($data)
	{
		print_r("<PRE>");
		print_r($data);
		print_r("</PRE>");
	}
}
?>