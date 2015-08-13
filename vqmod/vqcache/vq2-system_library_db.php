<?php
class DB {
	private $driver;
	
	public function __construct($driver, $hostname, $username, $password, $database) {
		if (file_exists(DIR_DATABASE . $driver . '.php')) {
			require_once(VQMod::modCheck(DIR_DATABASE . $driver . '.php'));
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}
				
		$this->driver = new $driver($hostname, $username, $password, $database);
	}
		
  	public function query($sql) {

				require_once(VQMod::modCheck(DIR_SYSTEM.'nitro/config.php'));
				require_once(VQMod::modCheck(DIR_SYSTEM.'nitro/core/core.php'));
				$nitroPersistence = getNitroPersistence();
				if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['DBCache']['Enabled']) && $nitroPersistence['Nitro']['DBCache']['Enabled'] == 'yes') {
					$nitro_matches = array();
					$nitro_match = false;
					
					// Product COUNT Queries
					if (!empty($nitroPersistence['Nitro']['DBCache']['ProductCountQueries']) && $nitroPersistence['Nitro']['DBCache']['ProductCountQueries'] == 'yes') $nitro_match = preg_match('~SELECT.*COUNT\(.*FROM.*[^0-9a-zA-Z_]' . DB_PREFIX . '(product)([\s]|$)~i', $sql, $nitro_matches);
					
					// Category COUNT Queries
					if (!$nitro_match && !empty($nitroPersistence['Nitro']['DBCache']['CategoryCountQueries']) && $nitroPersistence['Nitro']['DBCache']['CategoryCountQueries'] == 'yes') $nitro_match = preg_match('~SELECT.*COUNT\(.*FROM.*[^0-9a-zA-Z_]' . DB_PREFIX . '(category)([\s]|$)~i', $sql, $nitro_matches);
					
					// Category Queries
					if (!$nitro_match && !empty($nitroPersistence['Nitro']['DBCache']['CategoryQueries']) && $nitroPersistence['Nitro']['DBCache']['CategoryQueries'] == 'yes') $nitro_match = preg_match('~SELECT.*FROM.*[^0-9a-zA-Z_]' . DB_PREFIX . '(category)([\s]|$)~i', $sql, $nitro_matches);
					
					// SEO URLs Queries
					if (!$nitro_match && !empty($nitroPersistence['Nitro']['DBCache']['SeoUrls']) && $nitroPersistence['Nitro']['DBCache']['SeoUrls'] == 'yes') $nitro_match = preg_match('~SELECT.*FROM.*[^0-9a-zA-Z_]' . DB_PREFIX . '(url_alias)([\s]|$)~i', $sql, $nitro_matches);
					
					// Search Queries
					if (!$nitro_match && !empty($nitroPersistence['Nitro']['DBCache']['Search']) && $nitroPersistence['Nitro']['DBCache']['Search'] == 'yes') {
						$nitro_match = preg_match('~SELECT.*WHERE.*(LIKE|MATCH)~i', $sql, $nitro_matches);
						if ($nitro_match) {
							$nitro_match = false;
							if (!empty($nitroPersistence['Nitro']['DBCache']['SearchKeywords'])) {
								$nitro_keywords = explode(",", $nitroPersistence['Nitro']['DBCache']['SearchKeywords']);
								foreach ($nitro_keywords as $nitro_keyword) {
									if (stripos(trim($nitro_keyword), $sql) !== FALSE) {
										$nitro_match = true;
										break;
									}
								}
							}
						}
					}
					
					if ($nitro_match) {
						require_once(VQMod::modCheck(DIR_SYSTEM . 'nitro/core/dbcache.php'));
						$nitro_cache_selector = strtolower($nitro_matches[1]) . '.' . md5($sql); // category.6ef5cee93ce985fe9de730a1d837455d
						$nitro_result = getNitroDBCache($nitro_cache_selector);
						if ($nitro_result !== FALSE) return $nitro_result;
					}
				}
			

				if (!empty($nitro_cache_selector)) {
					$nitro_db_result = $this->driver->query($sql);
					
					setNitroDBCache($nitro_cache_selector, $nitro_db_result);
					
					return $nitro_db_result;
				}
			
		return $this->driver->query($sql);
  	}
	
	public function escape($value) {
		return $this->driver->escape($value);
	}
	
  	public function countAffected() {
		return $this->driver->countAffected();
  	}

  	public function getLastId() {
		return $this->driver->getLastId();
  	}	
}
?>