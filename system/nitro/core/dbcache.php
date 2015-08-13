<?php

function getNitroDBCache($cache) {
	$result = false;
	$file = NITRO_DBCACHE_FOLDER . $cache . '.nitro';
	$nitroPersistence = getNitroPersistence();
	$expire = !empty($nitroPersistence['Nitro']['DBCache']['ExpireTime']) ? (int)$nitroPersistence['Nitro']['DBCache']['ExpireTime'] : 0;
	
	if (!empty($nitroPersistence['Nitro']['DBCache']['CacheDepo'])) {
		if ($nitroPersistence['Nitro']['DBCache']['CacheDepo'] == 'hdd') {
			if ($expire > 0 && file_exists($file) && is_readable($file) && time() - $expire < filemtime($file)) {
				$result = unserialize(file_get_contents($file));
			} else {
				if (file_exists($file) && is_writeable($file)) unlink($file);
			}
		} else {
				$result = unserialize(getRamCache($file,$nitroPersistence['Nitro']['DBCache']['CacheDepo']));
		}
		
	
	}
	return $result;
}

function getRamCache($key, $depo) {
	if ($depo == 'ram_eaccelerator') {
		$data = eaccelerator_get($key);
	}
	if ($depo == 'ram_xcache') {
		$data = xcache_get($key);
	}
	if ($depo == 'ram_memcache') {
		$memcache_obj = memcache_connect('127.0.0.1', 11211);
		$data = memcache_get($memcache_obj, $key);
	}

	return $data;
}

function setRamCache($key, $data, $depo) {
	if ($depo == 'ram_eaccelerator') {
		return eaccelerator_put($key,$data);
	}
	if ($depo == 'ram_xcache') {
		return xcache_set($key,$data);
	}
	if ($depo == 'ram_memcache') {
		$memcache_obj = memcache_connect('127.0.0.1', '11211');
		return memcache_set($memcache_obj, $key, $data, 0, 86400);
	}

	return false;
}

function setNitroDBCache($cache, $data, $expire=3600) {
	$data = serialize($data);
	$file = NITRO_DBCACHE_FOLDER . $cache . '.nitro';
	
	$nitroPersistence = getNitroPersistence();
	
	if (!empty($nitroPersistence['Nitro']['DBCache']['CacheDepo'])) {
		if ($nitroPersistence['Nitro']['DBCache']['CacheDepo'] == 'hdd') {
	
			if (!is_dir(NITRO_DBCACHE_FOLDER)) {
				if (!mkdir(NITRO_DBCACHE_FOLDER)) return false;
			}
			
			if (is_writeable(NITRO_DBCACHE_FOLDER)) {
				if (file_put_contents($file, $data)) return true;
			}
			
		} else {
			setRamCache($file,$data,$nitroPersistence['Nitro']['DBCache']['CacheDepo']);
		}
	}
	
	return false;
}

?>