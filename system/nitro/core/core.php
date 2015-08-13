<?php 

function setNitroPersistence($data) {
	if (!is_dir(NITRO_FOLDER.'data')) {
		mkdir(NITRO_FOLDER.'data');	
	}
	file_put_contents(NITRO_PERSISTENCE, base64_encode(json_encode($data)));
	
	return true;
}

function getNitroPersistence($setting = null) {
	if (file_exists(NITRO_PERSISTENCE)) {
		$data = file_get_contents(NITRO_PERSISTENCE);
		$data = base64_decode($data);
		$returnData = json_decode($data, true);
	} else {
		$returnData = false;	
	}
	if (!empty($setting)) {
		if (!empty($returnData['Nitro'][$setting])) {
			$returnData = $returnData['Nitro'][$setting];
		}
	}
	return $returnData;
}

function getNitroSmushitPersistence() {
	$file = DIR_SYSTEM . 'nitro/data/smushit_persistence.tpl';
	$data = array(
		'smushed_images_count' => 0,
		'total_images' => false,
		'kb_saved' => 0,
		'last_smush_timestamp' => 0
	);
	if (file_exists($file)) {
		$data = json_decode(file_get_contents($file), true);
	} else {
		file_put_contents($file, json_encode($data));
	}
	return $data;
}

function setNitroSmushitPersistence($data) {
	if (is_array($data)) {
		$file = DIR_SYSTEM . 'nitro/data/smushit_persistence.tpl';
		$old_data = getNitroSmushitPersistence();
		$new_data = array_merge($old_data, $data);
		file_put_contents($file, json_encode($new_data));
		return true;
	}
	return false;
}

function clearFTPPersistence() {
	$file = DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl';
	file_put_contents($file, '');
	unset($_SESSION['nitro_ftp_persistence']);
}

function setFTPPersistence($value) {
	$file = DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl';
	$data = getFTPPersistence();
	if (!in_array($value, $data)) {
		$data[] = $value;
		file_put_contents($file, serialize($data));
	}
}

function getFTPPersistence() {
	$file = DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl';
	
	$data = array();
	if (file_exists($file)) {
		$data = unserialize(file_get_contents($file));
	}
	return empty($data) ? array() : $data;
}

function clearAmazonPersistence() {
	$file = DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl';
	file_put_contents($file, '');
}

function setAmazonPersistence($value) {
	$file = DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl';
	$data = getAmazonPersistence();
	if (!in_array($value, $data)) {
		$data[] = $value;
		file_put_contents($file, serialize($data));
	}
}

function getAmazonPersistence() {
	$file = DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl';
	
	$data = array();
	if (file_exists($file)) {
		$data = unserialize(file_get_contents($file));
	}
	return empty($data) ? array() : $data;
}

function clearRackspacePersistence() {
	$file = DIR_SYSTEM . 'nitro/data/rackspace_persistence.tpl';
	file_put_contents($file, '');
}

function setRackspacePersistence($value) {
	$file = DIR_SYSTEM . 'nitro/data/rackspace_persistence.tpl';
	$data = getRackspacePersistence();
	if (!in_array($value, $data)) {
		$data[] = $value;
		file_put_contents($file, serialize($data));
	}
}

function getRackspacePersistence() {
	$file = DIR_SYSTEM . 'nitro/data/rackspace_persistence.tpl';
	
	$data = array();
	if (file_exists($file)) {
		$data = unserialize(file_get_contents($file));
	}
	return empty($data) ? array() : $data;
}

function setGooglePageSpeedReport($data) {
	if (!is_dir(NITRO_FOLDER.'data')) {
		mkdir(NITRO_FOLDER.'data');	
	}
	//if (is_array($data) || is_object($data)) {
		//$data = json_encode($data);
	//}
	file_put_contents(NITRO_FOLDER.'data/googlepagespeed.tpl',base64_encode($data));
	return true;
}

function refreshGooglePageSpeedReport() {
	if (file_exists(NITRO_FOLDER.'data/googlepagespeed.tpl')) {
		if (unlink(NITRO_FOLDER.'data/googlepagespeed.tpl')) {
			return 'Google Page Speed Report was refreshed.';	
		} else {
			return 'There was a permission issue - please make sure the file system/nitro/data/googlepagespeed.tpl has at least 644 permissions!';	
		}
	}
}

function getGooglePageSpeedReport($setting = null) {
	if (file_exists(NITRO_FOLDER.'data/googlepagespeed.tpl')) {
		$data = file_get_contents(NITRO_FOLDER.'data/googlepagespeed.tpl');
		$data = base64_decode($data);
		//$data = json_decode($data,true);
		$returnData = $data;
	} else {		
		// Fetch the report and save it
		$catalogURL = (defined('HTTPS_CATALOG') && HTTPS_CATALOG != '') ? HTTPS_CATALOG : (defined('HTTP_CATALOG') && HTTP_CATALOG != '') ? HTTP_CATALOG : (defined('HTTPS_SERVER') && HTTPS_SERVER != '') ? HTTPS_SERVER : HTTP_SERVER;
		$catalogURL = $catalogURL .'#red' . rand(1000,99999);
		$persistence = getNitroPersistence();
		$key = !empty($persistence['Nitro']['GooglePageSpeedApiKey']) ? $persistence['Nitro']['GooglePageSpeedApiKey'] : 'AIzaSyCxptR6CbHYrHkFfsO_XN3nkf6FjoQp2Mg';
		$url = "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=".$catalogURL."&key=".$key;  
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		$output = curl_exec($ch);  
		curl_close($ch);  
		if (!empty($output)) {
			$returnData = $output;
			setGooglePageSpeedReport($returnData);
		} else {
			$returnData = false;
		}
	}
	if (!empty($setting)) {
		if (!empty($returnData['NitroCache'][$setting])) {
			$returnData = $returnData['NitroCache'][$setting];
		} else {
			$returnData = false;
		}
	}
	return $returnData;
}

function compressGzip($data, $level = 0) {
	$headers = array();
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
		$encoding = 'gzip';
	} 

	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
		$encoding = 'x-gzip';
	}

	if (!isset($encoding)) {
		return $data;
	}

	if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
		return $data;
	}

	if (headers_sent()) {
		//return $data;
	}

	if (connection_status()) { 
		return $data;
	}
	
	//$headers[] = ('Content-Encoding: ' . $encoding);
	//foreach ($headers as $header) {
	//	header($header, true);
	//}
	return gzencode($data, (int)$level);
}

function minifyHTML($html) {

	include NITRO_FOLDER.'lib'.DS.'minifier'.DS.'HTMLMin.php';
	$htmlMinifier = new Minify_HTML($html,array('jsCleanComments' => true));
	$html =  $htmlMinifier->process();
	//$html = str_replace(array("\n"), array(" "), trim($html));
	return $html;
}

function getSSLCachePrefix() {
	return isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1')) ? '1-' : '0-';
}

?>