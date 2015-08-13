<?php
class Cache { 
	private $expire = 3600; 

	public function get($key) {

				require_once(VQMod::modCheck(DIR_SYSTEM.'nitro/config.php'));
				require_once(VQMod::modCheck(DIR_SYSTEM.'nitro/core/core.php'));
				$nitroPersistence = getNitroPersistence();
				if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && (empty($nitroPersistence['Nitro']['OpenCartCache']['Enabled']) || $nitroPersistence['Nitro']['OpenCartCache']['Enabled'] == 'no')) {
					return;
				}
			
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$cache = file_get_contents($files[0]);
			
			$data = unserialize($cache);
			
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

      			if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);

				clearstatcache();
			
					}
      			}
    		}
			
			return $data;			
		}
	}

  	public function set($key, $value) {
    	$this->delete($key);

				require_once(VQMod::modCheck(DIR_SYSTEM.'nitro/config.php'));
				require_once(VQMod::modCheck(DIR_SYSTEM.'nitro/core/core.php'));
				$nitroPersistence = getNitroPersistence();
				
				if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && (empty($nitroPersistence['Nitro']['OpenCartCache']['Enabled']) || $nitroPersistence['Nitro']['OpenCartCache']['Enabled'] == 'no')) {
					return;
				}
				$nitro_expire = !empty($nitroPersistence['Nitro']['OpenCartCache']['ExpireTime']) ? (int)$nitroPersistence['Nitro']['OpenCartCache']['ExpireTime'] : 0;
				$this->expire = $nitro_expire;
			
		
		$file = DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);
    	
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));
		
    	fclose($handle);
  	}
	
  	public function delete($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);

				clearstatcache();
			
				}
    		}
		}
  	}
}
?>