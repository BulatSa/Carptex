<?php
class ModelToolNitro extends Model {
	private $session_closed = false;
	
	public function clearImageCache() {
		$this->loadConfig();
		$output = array();
		
		if ($this->exec_enabled()) {
			exec('rm -rf '.DIR_IMAGE . 'cache/',$output);
		} else {
			try {
				$this->delete_folder(DIR_IMAGE . 'cache/');
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearPageCache() {
		$this->loadConfig();
		$output = array();
		//N.B. This deletes the /pagecache/ folder as well; Later the Nitro automatically recreates it.
		
		if ($this->exec_enabled()) {
			exec('rm -rf '.NITRO_PAGECACHE_FOLDER,$output);
		} else {
			try {
				$this->delete_folder(NITRO_PAGECACHE_FOLDER);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearDBCache() {
		$this->loadConfig();
		$output = array();
		//N.B. This deletes the /dbcache/ folder as well; Later the Nitro automatically recreates it.
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . NITRO_DBCACHE_FOLDER, $output);
		} else {
			try {
				$this->delete_folder(NITRO_DBCACHE_FOLDER);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearJSCache() {
		$this->loadConfig();
		$output = array();
		//N.B. This deletes the /assets/script folder as well; Later the Nitro automatically recreates it.
		$srcFolder = dirname(DIR_APPLICATION) . DS . 'assets' . DS . 'script';
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder, $output);
		} else {
			try {
				$this->delete_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearCSSCache() {
		$this->loadConfig();
		$output = array();
		//N.B. This deletes the /assets/style folder as well; Later the Nitro automatically recreates it.
		$srcFolder = dirname(DIR_APPLICATION) . DS . 'assets' . DS . 'style';
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder, $output);
		} else {
			try {
				$this->delete_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearVqmodCache() {
		$this->loadConfig();
		$output = array();
		$srcFolder = dirname(DIR_APPLICATION) . DS . 'vqmod' . DS . 'vqcache' . DS . '*';
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder, $output);
		} else {
			try {
				$this->delete_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			$mods_cache = dirname(dirname($srcFolder)).DS.'mods.cache';
			if (file_exists($mods_cache)) {
				if (unlink($mods_cache)) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		} else {
			return false;	
		}
	}
	
	public function loadConfig() {
		require_once(DIR_SYSTEM.'nitro/config.php');
	}
	
	public function loadCore() {
		require_once(DIR_SYSTEM.'nitro/core/core.php');
	}
	
	public function setPersistence($data) {
		$this->loadConfig();
		$this->loadCore();
		return setNitroPersistence($data);
	}
	
	public function getPersistence() {
		$this->loadConfig();
		$this->loadCore();
		return getNitroPersistence();
	}
	
	public function getSmushitPersistence() {
		$this->loadConfig();
		$this->loadCore();
		return getNitroSmushitPersistence();
	}
	
	public function setSmushitPersistence($data) {
		$this->loadConfig();
		$this->loadCore();
		return setNitroSmushitPersistence($data);
	}
	
	public function refreshGooglePageSpeedReport() {
		$this->loadConfig();
		$this->loadCore();
		return refreshGooglePageSpeedReport();
	}
	
	public function getGoogleRawData() {
		$this->loadConfig();
		$this->loadCore();
		refreshGooglePageSpeedReport();
		return getGooglePageSpeedReport();
	}
	
	public function getGooglePageSpeedReport($setting = null) {
		$this->loadConfig();
		$this->loadCore();
		return getGooglePageSpeedReport($setting);
	}
	
	public function smushCachedImages() {
		$this->closeSession();
		require_once(DIR_APPLICATION.'../assets/smushit.php');
		$cacheImagesDir = DIR_IMAGE.'cache';
		$images = $this->onlyImages($this->directoryToArray($cacheImagesDir , true));
		$total_images = count($images);
		$smushedNumber = 0;
		$files = array_chunk($images, 3);
		
		//$file = DIR_SYSTEM . 'nitro/data/smush_refresh.cache';
		
		//file_put_contents($file, '');
		
		$this->openSession();
		$_SESSION['smush_progress'] = array(
			'smushed_images_count' => 0,
			'total_images' => $total_images,
			'kb_saved' => 0,
			'last_smush_timestamp' => 0,
			'smushed_files' => array(),
			'messages' => array()
		);
		$this->setSmushitPersistence($_SESSION['smush_progress']);
		$this->closeSession();
		
		// Take a batch of three files
		foreach($files as $batch) {
			try {
				// Compress the batch 
				//$this->setSmushProgress($smushedNumber, 0, time(), $file);
				$smushit = new SmushIt($batch, SmushIt::LOCAL_ORIGIN);
				set_time_limit(30);
				// And finaly, replace original files by their compressed version
				foreach($smushit->get() as $k => $file) {
					if (!$this->smushCanContinue()) return $smushedNumber;
					
					// Sometimes, Smush.it convert files. We don't want that to happen.
					
					$src = pathinfo($file[0]->source, PATHINFO_EXTENSION);
					$dst = pathinfo($file[0]->destination, PATHINFO_EXTENSION);
					if ($src == $dst AND copy($file[0]->destination, $file[0]->source)) {
						// Success !
						//echo 'Smushed File: '.$source.'<br>';
						$smushedNumber++;
						$this->setSmushProgress($smushedNumber, ($file[0]->sourceSize - $file[0]->destinationSize)/1024, time(), $file[0]->source, $file[0]->savings);
					} else {
						$this->setSmushProgressMessage('Skip: SmushIt converted from  ' . $src . ' to ' . $dst);
					}
				}
			} catch(Exception $e) {
				$this->setSmushProgressMessage($e->getMessage());
				//$this->log->write($e->getMessage());
				continue;
			}
		}

		return $smushedNumber;
	}
	
	private function onlyImages($files) {
		$imgs = array();
		foreach($files as $file) {
			$tmpfile = strtolower($file);
			if (strstr($tmpfile,'.png') === false && strstr($tmpfile,'.gif') === false && strstr($tmpfile,'.jpg') === false && strstr($tmpfile,'.jpeg') === false) {
				
			} else {
				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
					$catalogBase = HTTPS_CATALOG;
				} else {
					$catalogBase = HTTP_CATALOG;
				}
				$imgs[] = $file;
			}
		}
		return $imgs;
	}
	
	private function directoryToArray($directory, $recursive) {
		$array_items = array();
		if (file_exists($directory) && is_dir($directory) && $handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, $this->directoryToArray($directory. "/" . $file, $recursive));
						}
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					} else {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}
	
	public function getCloudFlareStats() {
		$this->loadConfig();
		$this->loadCore();
	}
	
	
	public function applyNitroCacheHTRules($post) {
		$this->loadConfig();
		$this->loadCore();
		$pers = getNitroPersistence();
		
		$htaccessFileContent = $this->getHtaccessFileContent();
		$old_content = $this->extractNitrocodeFromHtaccessFile($htaccessFileContent);
		
		if ((string)$old_content != '') {
			$newHtaccessFileContent = str_replace($old_content,'',$htaccessFileContent);
		} else {
			$newHtaccessFileContent = $htaccessFileContent;
		}
		
		$this->setHtaccessFileContent($newHtaccessFileContent);
		
		if (empty($post['Nitro']['BrowserCache']['Enabled']) || $post['Nitro']['BrowserCache']['Enabled'] == 'no') {
			return false;
		}

		$htrules = PHP_EOL.'# STARTNITRO'.PHP_EOL;
		
		$htrules .= 'ExpiresActive On'.PHP_EOL;
		
		if (!empty($post['Nitro']['BrowserCache']['CSSJS']['Period']) && $post['Nitro']['BrowserCache']['CSSJS']['Period'] != 'no-cache' && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$maxage = $post['Nitro']['BrowserCache']['CSSJS']['Period'];
			$htrules .= ''.PHP_EOL;
			$htrules .= '#CSS JS XML TXT - '.strtoupper($maxage).PHP_EOL;
			$htrules .= '<FilesMatch "\.(xml|txt|css|js)$">'.PHP_EOL;
			$htrules .= 'Header set Cache-Control "max-age='.(string)(strtotime($maxage)-time()).', public"'.PHP_EOL;
			$htrules .= 'ExpiresDefault "access plus '.$maxage.'"'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}
		
		if (!empty($post['Nitro']['BrowserCache']['Images']['Period']) && $post['Nitro']['BrowserCache']['Images']['Period'] != 'no-cache' && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$maxage = $post['Nitro']['BrowserCache']['Images']['Period'];
			$htrules .= ''.PHP_EOL;
			$htrules .= '#JPG JPEG PNG GIF SWF - '.strtoupper($maxage).PHP_EOL;
			$htrules .= '<FilesMatch "\.(jpg|jpeg|png|gif|swf|JPG|JPEG|PNG|GIF|SWF)$">'.PHP_EOL;
			$htrules .= 'Header set Cache-Control "max-age='.(string)(strtotime($maxage)-time()).', public"'.PHP_EOL;
			$htrules .= 'Header set Last-Modified "Wed, 05 Jun 2009 06:40:46 GMT"'.PHP_EOL;
			$htrules .= 'ExpiresDefault "access plus '.$maxage.'"'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}

		if (!empty($post['Nitro']['BrowserCache']['Icons']['Period']) && $post['Nitro']['BrowserCache']['Icons']['Period'] != 'no-cache' && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$maxage = $post['Nitro']['BrowserCache']['Icons']['Period'];
			$htrules .= ''.PHP_EOL;
			$htrules .= '#OTF ICO PDF FLV - '.strtoupper($maxage).PHP_EOL;
			$htrules .= '<FilesMatch "\.(otf|ico|pdf|flv)$">'.PHP_EOL;
			$htrules .= 'Header set Cache-Control "max-age='.(string)(strtotime($maxage)-time()).', public"'.PHP_EOL;
			$htrules .= 'ExpiresDefault "access plus '.$maxage.'"'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}

		if (!empty($post['Nitro']['BrowserCache']['Enabled']) && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$htrules .= ''.PHP_EOL;
			$htrules .= '#HTML HTM PHP'.PHP_EOL;
			$htrules .= '<FilesMatch "\.(html|htm|php)$">'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}

		$htrules .= '# ENDNITRO'.PHP_EOL;

		$newHtaccessFileContent = $this->getHtaccessFileContent() . $htrules;

		return $this->setHtaccessFileContent($newHtaccessFileContent);
	}
	
	public function applyNitroCacheHTCompressionRules($post) {
		$this->loadConfig();
		$this->loadCore();
		$pers = getNitroPersistence();
		
		$htaccessFileContent = $this->getHtaccessFileContent();
		$old_content = $this->extractNitrocodeCompressFromHtaccessFile($htaccessFileContent);
		
		if ((string)$old_content != '') {
			$newHtaccessFileContent = str_replace($old_content,'',$htaccessFileContent);
		} else {
			$newHtaccessFileContent = $htaccessFileContent;
		}
		
		$this->setHtaccessFileContent($newHtaccessFileContent);
		
		if (empty($post['Nitro']['Compress']['Enabled']) || $post['Nitro']['Compress']['Enabled'] == 'no') {
			return false;
		}

		$htrules = '# STARTCOMPRESSNITRO'.PHP_EOL;
		
		if (!empty($post['Nitro']['Compress']['CSS']) && $post['Nitro']['Compress']['CSS'] == 'yes') {
			$htrules .= ''.PHP_EOL;
			$htrules .= 'RewriteCond %{SCRIPT_FILENAME} !-d'.PHP_EOL;
			$htrules .= 'RewriteRule ^(\/?((catalog)|(assets)).+)\.css$ assets/style.php?l=4&p=$1 [NC,L]'.PHP_EOL;
		}
		
		if (!empty($post['Nitro']['Compress']['JS']) && $post['Nitro']['Compress']['JS'] == 'yes') {
			$htrules .= ''.PHP_EOL;
			$htrules .= 'RewriteCond %{SCRIPT_FILENAME} !-d'.PHP_EOL;
			$htrules .= 'RewriteRule ^(\/?((catalog)|(assets)).+)\.js$ assets/script.php?l=4&p=$1 [NC,L]'.PHP_EOL;
		}

		$htrules .= PHP_EOL.'# ENDCOMPRESSNITRO'.PHP_EOL;

		$newHtaccessFileContent = $htrules . $this->getHtaccessFileContent();

		return $this->setHtaccessFileContent($newHtaccessFileContent);
	}
	
	public function ftp_upload() {
		$persistence = $this->getPersistence();
		
		$data = $persistence['Nitro']['CDNStandardFTP'];
		
		$this->ftp_set_progress('Initializing connection...', 0, 0, true);
		
		if ($data['Protocol'] == 'ftps' && !function_exists('ftp_ssl_connect')) {
			throw new Exception('Your server does not support FTPS.');
		}
		if ($data['Protocol'] == 'ftp' && !function_exists('ftp_connect')) {
			throw new Exception('Your server does not support FTP.');
		}
		
		$port = !empty($data['Port']) ? (int)trim($data['Port']) : 21;
		
		$server = parse_url(trim($data['Host']));
		if (empty($server['scheme'])) {
			$server = $server['path'];
		} else $server = $server['host'];
		
		if (!empty($server)) {
			
			if ($data['Protocol'] == 'ftps') {
				$connection = ftp_ssl_connect($server, $port);
			} else {
				$connection = ftp_connect($server, $port);
			}
			
			if ($connection !== FALSE) {
				
				if (ftp_login($connection, $data['Username'], $data['Password'])) {
					
					$root = '/' . implode('/', array_filter(explode('/', $data['Root']))) . '/';
					if (ftp_chdir($connection, $root)) {
						$this->loadConfig();
						$this->loadCore();
						
						// The connection is successful. We can now start to upload :)
						// clearFTPPersistence();
						
						$this->ftp_set_progress('Scanning files...');
						
						$files = array();
						$site_root = dirname(DIR_SYSTEM) . '/';
						
						if (!empty($data['SyncCSS'])) {
							$files = array_merge($files, $this->list_files_with_ext($site_root, 'css'));
						}
						
						if (!empty($data['SyncJavaScript'])) {
							$files = array_merge($files, $this->list_files_with_ext($site_root, 'js'));
						}
						
						if (!empty($data['SyncImages'])) {
							$files = array_merge($files, $this->list_files_with_ext($site_root, array('png', 'jpg', 'jpeg', 'gif', 'tiff', 'bmp')));
						}
						
						$all_size = 0;
						$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
						$admin_folder = array_pop($admin_folder_parts) . '/';
						$site_root = dirname(DIR_SYSTEM) . '/';
						
						clearstatcache(true);
						foreach ($files as $i => $file) {
							$destination = substr($file, strlen($site_root));
							// If in admin folder, omit
							if (stripos($destination, $admin_folder) === 0) {
								unset($files[$i]);
								continue;
							}
							if (file_exists($file) && is_file($file)) {
								$all_size += filesize($file);
							} else {
								unset($files[$i]);
							}
						}
						
						$this->ftp_set_progress('Uploading files...', 0, $all_size);
						
						$this->ftp_upload_files($connection, $root, $files);
						
						$this->ftp_set_progress('Task finished!');
						
						if ($this->session_closed) {
							session_start();
							$this->session_closed = false;
						}
					} else throw new Exception('Could not go to the specified directory.');
					
				} else throw new Exception('Could not login with the specified credentials.');
				
			} else throw new Exception('Could not connect to the specified server and port.');
			
		} else throw new Exception('Invalid server name.');
		
	}
	
	private function ftp_upload_files($connection, $root, &$output) {
		$this->loadConfig();
		$this->loadCore();
		
		$site_root = dirname(DIR_SYSTEM) . '/';
		
		foreach ($output as $file) {
			
			if ($this->session_closed) {
				session_start();
				$this->session_closed = false;
			}
			if (!empty($_SESSION['nitro_ftp_cancel'])) {
				unset($_SESSION['nitro_ftp_cancel']);
				session_write_close();
				$this->session_closed = true;
				break;
			}
			
			$source = $file;
			$destination = substr($file, strlen($site_root));
			
			$destination_folders = array_filter(explode('/', dirname($destination)));
			$destination_file = basename($destination);
			$destination_folder = $root;
			foreach ($destination_folders as $folder) {
				$listing = ftp_rawlist($connection, $destination_folder);
				
				$has_destination = false;
				
				foreach ($listing as $element) {
					if (preg_match('~^d(.*?) \d{1,2} \d{1,2}:\d\d ' . $folder . '$~', $element, $matches) !== FALSE && !empty($matches)) {
						$has_destination = true;
						break;
					}
				}
				
				$destination_folder .= $folder . '/';
				
				if (!$has_destination) {
					ftp_mkdir($connection, $destination_folder);
				}
				
				ftp_chdir($connection, $destination_folder);
			}
			
			if (ftp_put($connection, $destination_file, $source, FTP_BINARY)) {
				$this->ftp_set_progress('Uploaded ' . $destination_folder . $destination_file, filesize($source));
				setFTPPersistence($destination);
			} else {
				throw new Exception('Could not upload ' . $destination_folder . $destination_file);
			}
		}
	}
	
	private function ftp_set_progress($message, $uploaded = 0, $all = 0, $init = false) {
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
		if (!$this->session_closed) {
			if ($init) unset($_SESSION['nitro_ftp_progress']);
			
			if ($all) {
				$_SESSION['nitro_ftp_progress']['all_size'] = $all;
				$_SESSION['nitro_ftp_progress']['uploaded_size'] = 0;
				$_SESSION['nitro_ftp_progress']['percent'] = 0;
				$_SESSION['nitro_ftp_progress']['init_time'] = time();
			}
			
			unset($_SESSION['nitro_ftp_progress']['message']);
			$_SESSION['nitro_ftp_progress']['message'] = $message;
			
			if (!empty($_SESSION['nitro_ftp_progress']['all_size']) && !empty($uploaded)) {
				$_SESSION['nitro_ftp_progress']['uploaded_size'] += $uploaded;
				$_SESSION['nitro_ftp_progress']['percent'] = ceil(100*$_SESSION['nitro_ftp_progress']['uploaded_size']/$_SESSION['nitro_ftp_progress']['all_size']);
				$delta = (time() - $_SESSION['nitro_ftp_progress']['init_time']);
				$_SESSION['nitro_ftp_progress']['speed'] = $delta ? $_SESSION['nitro_ftp_progress']['uploaded_size']/(time() - $_SESSION['nitro_ftp_progress']['init_time']) : $_SESSION['nitro_ftp_progress']['uploaded_size'];
				$_SESSION['nitro_ftp_progress']['message'] .= '<br />Progress: ' . $_SESSION['nitro_ftp_progress']['percent'] . '%';
				$_SESSION['nitro_ftp_progress']['message'] .= '<br />Speed: ' . $this->sizeToString($_SESSION['nitro_ftp_progress']['speed']) . '/s';
				$time = ceil($_SESSION['nitro_ftp_progress']['all_size'] - $_SESSION['nitro_ftp_progress']['uploaded_size'])/$_SESSION['nitro_ftp_progress']['speed'];
				
				$_SESSION['nitro_ftp_progress']['message'] .= '<br />Time remaining: ' . (str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time % 60), 2, '0', STR_PAD_LEFT));
				
			}
			
			session_write_close();
			$this->session_closed = true;
		}
	}
	
	private function sizeToString($size) {
		$count = 0;
		for ($i = $size; $i >= 1024; $i /= 1024) $count++;
		switch ($count) {
			case 0 : $suffix = ' B'; break;
			case 1 : $suffix = ' KB'; break;
			case 2 : $suffix = ' MB'; break;
			case 3 : $suffix = ' GB'; break;
			case ($count >= 4) : $suffix = ' TB'; break;
		}
		return round($i, 2) . $suffix;
	}
	
	public function amazon_upload() {
		$persistence = $this->getPersistence();
		
		$data = $persistence['Nitro']['CDNAmazon'];
		
		$this->amazon_set_progress('Initializing connection...', 0, 0, true);
		
		if (!class_exists('S3')) require_once(DIR_SYSTEM . 'nitro/lib/S3.php');
		
		$s3 = new S3($data['AccessKeyID'], $data['SecretAccessKey']);
		
		$buckets = $s3->listBuckets();
		if (is_array($buckets) && in_array($data['Bucket'], $buckets)) {
			
			$this->loadConfig();
			$this->loadCore();
			
			// The connection is successful. We can now start to upload :)
			// clearAmazonPersistence();
			
			$this->amazon_set_progress('Scanning files...');
			
			$files = array();
			$site_root = dirname(DIR_SYSTEM) . '/';
			
			if (!empty($data['SyncCSS'])) {
				$files = array_merge($files, $this->list_files_with_ext($site_root, 'css'));
			}
			
			if (!empty($data['SyncJavaScript'])) {
				$files = array_merge($files, $this->list_files_with_ext($site_root, 'js'));
			}
			
			if (!empty($data['SyncImages'])) {
				$files = array_merge($files, $this->list_files_with_ext($site_root, array('png', 'jpg', 'jpeg', 'gif', 'tiff', 'bmp')));
			}
			
			$all_size = 0;
			$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
			$admin_folder = array_pop($admin_folder_parts) . '/';
			$site_root = dirname(DIR_SYSTEM) . '/';
			
			clearstatcache(true);
			foreach ($files as $i => $file) {
				$destination = substr($file, strlen($site_root));
				// If in admin folder, omit
				if (stripos($destination, $admin_folder) === 0) {
					unset($files[$i]);
					continue;
				}
				if (file_exists($file) && is_file($file)) {
					$all_size += filesize($file);
				} else {
					unset($files[$i]);
				}
			}
			
			$this->amazon_set_progress('Starting upload...', 0, $all_size);
			
			$this->amazon_upload_files($s3, $data['Bucket'], $files);
			
			$this->amazon_set_progress('Task finished!', 'success');
			
			if ($this->session_closed) {
				session_start();
				$this->session_closed = false;
			}
			
		} else throw new Exception('The specified bucket does not exist. Please create it.');
	}
	
	private function amazon_upload_files($s3, $bucket, &$files) {
		$this->loadConfig();
		$this->loadCore();
		
		$site_root = dirname(DIR_SYSTEM) . '/';
		//if (!function_exists('exec')) throw new Exception('Your server does not support the exec() function.');
		
		// Get local files
		
		// Copy files
		foreach ($files as $file) {
			
			if ($this->session_closed) {
				session_start();
				$this->session_closed = false;
			}
			if (!empty($_SESSION['nitro_amazon_cancel'])) {
				unset($_SESSION['nitro_amazon_cancel']);
				session_write_close();
				$this->session_closed = true;
				break;
			}
			
			$source = $file;
			$destination = substr($file, strlen($site_root));
			
			if ($s3->putObjectFile($source, $bucket, $destination, S3::ACL_PUBLIC_READ)) {
				$this->amazon_set_progress('Uploaded ' . $destination, filesize($source));
				setAmazonPersistence($destination);
			} else {
				throw new Exception('Could not upload ' . $destination);
			}
		}
	}
	
	private function amazon_set_progress($message, $uploaded = 0, $all = 0, $init = false) {
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
		if (!$this->session_closed) {
			if ($init) unset($_SESSION['nitro_amazon_progress']);
			
			if ($all) {
				$_SESSION['nitro_amazon_progress']['all_size'] = $all;
				$_SESSION['nitro_amazon_progress']['uploaded_size'] = 0;
				$_SESSION['nitro_amazon_progress']['percent'] = 0;
				$_SESSION['nitro_amazon_progress']['init_time'] = time();
			}
			
			unset($_SESSION['nitro_amazon_progress']['message']);
			$_SESSION['nitro_amazon_progress']['message'] = $message;
			
			if (!empty($_SESSION['nitro_amazon_progress']['all_size']) && !empty($uploaded)) {
				$_SESSION['nitro_amazon_progress']['uploaded_size'] += $uploaded;
				$_SESSION['nitro_amazon_progress']['percent'] = ceil(100*$_SESSION['nitro_amazon_progress']['uploaded_size']/$_SESSION['nitro_amazon_progress']['all_size']);
				$delta = (time() - $_SESSION['nitro_amazon_progress']['init_time']);
				$_SESSION['nitro_amazon_progress']['speed'] = $delta ? $_SESSION['nitro_amazon_progress']['uploaded_size']/(time() - $_SESSION['nitro_amazon_progress']['init_time']) : $_SESSION['nitro_amazon_progress']['uploaded_size'];
				$_SESSION['nitro_amazon_progress']['message'] .= '<br />Progress: ' . $_SESSION['nitro_amazon_progress']['percent'] . '%';
				$_SESSION['nitro_amazon_progress']['message'] .= '<br />Speed: ' . $this->sizeToString($_SESSION['nitro_amazon_progress']['speed']) . '/s';
				$time = ceil($_SESSION['nitro_amazon_progress']['all_size'] - $_SESSION['nitro_amazon_progress']['uploaded_size'])/$_SESSION['nitro_amazon_progress']['speed'];
				
				$_SESSION['nitro_amazon_progress']['message'] .= '<br />Time remaining: ' . (str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time % 60), 2, '0', STR_PAD_LEFT));
				
			}
			
			session_write_close();
			$this->session_closed = true;
		}
	}
	
	public function rackspace_upload() {
		$persistence = $this->getPersistence();
		
		$data = $persistence['Nitro']['CDNRackspace'];
		
		$this->rackspace_set_progress('Initializing connection...', 0, 0, true);
		
		require_once(DIR_SYSTEM . 'nitro/lib/rackspace/php-opencloud.php');
		
		$connection = new \OpenCloud\Rackspace(RACKSPACE_US, array('username' => $data['Username'], 'apiKey' => $data['APIKey'] ));
		$objstore = $connection->ObjectStore('cloudFiles', $data['ServerRegion'], "publicURL");
		
		$buckets = $objstore->ContainerList();
		$b = array();
		
		while($con = $buckets->Next()) {
			$b[] = $con->Name();
		}
		
		if (!empty($data['SyncImages']) && !in_array($data['ImagesContainer'], $b)) throw new Exception('The Image container &quot;' . $data['ImagesContainer'] . '&quot; does not exist. Please create it.');
		if (!empty($data['SyncCSS']) && !in_array($data['CSSContainer'], $b)) throw new Exception('The CSS container &quot;' . $data['CSSContainer'] . '&quot; does not exist. Please create it.');
		if (!empty($data['SyncJavaScript']) && !in_array($data['JavaScriptContainer'], $b)) throw new Exception('The JavaScript container &quot;' . $data['JavaScriptContainer'] . '&quot; does not exist. Please create it.');
		
		$this->loadConfig();
		$this->loadCore();
		
		// The connection is successful. We can now start to upload :)
		// clearRackspacePersistence();
		
		$this->rackspace_set_progress('Scanning files...');
		
		$files = array();
		$site_root = dirname(DIR_SYSTEM) . '/';
		
		if (!empty($data['SyncCSS'])) {
			$files = array_merge($files, $this->list_files_with_ext($site_root, 'css'));
		}
		
		if (!empty($data['SyncJavaScript'])) {
			$files = array_merge($files, $this->list_files_with_ext($site_root, 'js'));
		}
		
		if (!empty($data['SyncImages'])) {
			$files = array_merge($files, $this->list_files_with_ext($site_root, array('png', 'jpg', 'jpeg', 'gif', 'tiff', 'bmp')));
		}
		
		$all_size = 0;
		$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
		$admin_folder = array_pop($admin_folder_parts) . '/';
		$site_root = dirname(DIR_SYSTEM) . '/';
		
		clearstatcache(true);
		foreach ($files as $i => $file) {
			$destination = substr($file, strlen($site_root));
			// If in admin folder, omit
			if (stripos($destination, $admin_folder) === 0) {
				unset($files[$i]);
				continue;
			}
			if (file_exists($file) && is_file($file)) {
				$all_size += filesize($file);
			} else {
				unset($files[$i]);
			}
		}
		
		$this->rackspace_set_progress('Starting upload...', 0, $all_size);
		
		$this->rackspace_upload_files($objstore, $data, $files);
		
		$this->rackspace_set_progress('Task finished!', 'success');
		
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
			
		
	}
	
	private function rackspace_upload_files(&$ostore, &$data, &$files) {
		$this->loadConfig();
		$this->loadCore();
		
		$site_root = dirname(DIR_SYSTEM) . '/';
		//if (!function_exists('exec')) throw new Exception('Your server does not support the exec() function.');
		
		$containers = array(
			'js' => NULL,
			'image' => NULL,
			'css' => NULL
		);
		
		$mimeTypes = $this->generateUpToDateMimeArray('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');
		
		// Copy files
		foreach ($files as $file) {
			
			if ($this->session_closed) {
				session_start();
				$this->session_closed = false;
			}
			if (!empty($_SESSION['nitro_rackspace_cancel'])) {
				unset($_SESSION['nitro_rackspace_cancel']);
				session_write_close();
				$this->session_closed = true;
				break;
			}
			
			$source = $file;
			$destination = substr($file, strlen($site_root));
			
			$source_info = pathinfo($source);
			$container = '';
			if ($source_info['extension'] == 'js') {
				$container = $data['JavaScriptContainer'];
				if (empty($containers['js'])) $containers['js'] = $ostore->Container($container);
				$container = $containers['js'];
			} else if ($source_info['extension'] == 'css') {
				$container = $data['CSSContainer'];
				if (empty($containers['css'])) $containers['css'] = $ostore->Container($container);
				$container = $containers['css'];
			} else {
				$container = $data['ImagesContainer'];
				if (empty($containers['image'])) $containers['image'] = $ostore->Container($container);
				$container = $containers['image'];
			}
			
			$obj = $container->DataObject();
			$response = $obj->Create(array('name' => $destination, 'content_type' => $mimeTypes[$source_info['extension']]), $source);
			
			if ($response->errno() == 0) {
				$this->rackspace_set_progress('Uploaded ' . $destination, filesize($source));
				setRackspacePersistence($destination);
			} else {
				throw new Exception('Could not upload ' . $destination);
			}
		}
	}
	
	private function rackspace_set_progress($message, $uploaded = 0, $all = 0, $init = false) {
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
		if (!$this->session_closed) {
			if ($init) unset($_SESSION['nitro_rackspace_progress']);
			
			if ($all) {
				$_SESSION['nitro_rackspace_progress']['all_size'] = $all;
				$_SESSION['nitro_rackspace_progress']['uploaded_size'] = 0;
				$_SESSION['nitro_rackspace_progress']['percent'] = 0;
				$_SESSION['nitro_rackspace_progress']['init_time'] = time();
			}
			
			unset($_SESSION['nitro_rackspace_progress']['message']);
			$_SESSION['nitro_rackspace_progress']['message'] = $message;
			
			if (!empty($_SESSION['nitro_rackspace_progress']['all_size']) && !empty($uploaded)) {
				$_SESSION['nitro_rackspace_progress']['uploaded_size'] += $uploaded;
				$_SESSION['nitro_rackspace_progress']['percent'] = ceil(100*$_SESSION['nitro_rackspace_progress']['uploaded_size']/$_SESSION['nitro_rackspace_progress']['all_size']);
				$delta = (time() - $_SESSION['nitro_rackspace_progress']['init_time']);
				$_SESSION['nitro_rackspace_progress']['speed'] = $delta ? $_SESSION['nitro_rackspace_progress']['uploaded_size']/(time() - $_SESSION['nitro_rackspace_progress']['init_time']) : $_SESSION['nitro_rackspace_progress']['uploaded_size'];
				$_SESSION['nitro_rackspace_progress']['message'] .= '<br />Progress: ' . $_SESSION['nitro_rackspace_progress']['percent'] . '%';
				$_SESSION['nitro_rackspace_progress']['message'] .= '<br />Speed: ' . $this->sizeToString($_SESSION['nitro_rackspace_progress']['speed']) . '/s';
				$time = ceil($_SESSION['nitro_rackspace_progress']['all_size'] - $_SESSION['nitro_rackspace_progress']['uploaded_size'])/$_SESSION['nitro_rackspace_progress']['speed'];
				
				$_SESSION['nitro_rackspace_progress']['message'] .= '<br />Time remaining: ' . (str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time % 60), 2, '0', STR_PAD_LEFT));
				
			}
			
			session_write_close();
			$this->session_closed = true;
		}
	}
	
	private function generateUpToDateMimeArray($url){ //FUNCTION FROM Josh Sean @ http://www.php.net/manual/en/function.mime-content-type.php
		$s=array('gz' => 'application/x-gzip');
		foreach(@explode("\n",@file_get_contents($url))as $x)
			if(isset($x[0])&&$x[0]!=='#'&&preg_match_all('#([^\s]+)#',$x,$out)&&isset($out[1])&&($c=count($out[1]))>1)
				for($i=1;$i<$c;$i++)
					$s[$out[1][$i]]=$out[1][0];
		return ($s)?$s:false;
	}
	
	public function getServerInfo($permission) {
		$text_no_permission = '<div class="info-error">You do not have permissions to view this.</div>';
		$result = array();
		
		/* PHP VERSION */
		if (!$permission) $result['php_version'] = $text_no_permission;
		else {
			$result['php_version'] = PHP_VERSION;
		}
		
		/* WEB SERVER */
		if (!$permission) $result['web_server'] = $text_no_permission;
		else {
			if (ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'off') {
				if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
					$url = HTTP_CATALOG;
				} else {
					$url = HTTPS_CATALOG;
				}
				
				$status_results = file_get_contents('http://tools.seobook.com/server-header-checker/?page=bulk&url=' . $url . '&useragent=1&typeProtocol=11');
				preg_match('~\<strong\>SERVER RESPONSE\<\/strong\>[^h]*(.*?)\<\/p\>~i', $status_results, $matches);
				$status = array_pop($matches);
				if (stripos($status, 'HTTP/1.1 2') !== FALSE) $type = 'success';
				else if (stripos($status, 'HTTP/1.1 3') !== FALSE) $type = 'warning';
				else $type = 'error';
				
				$status = '<span class="info-' . $type . '"><strong>' . $status . '</strong></span>';
				
			} else $status = '<span class="info-warning"><strong>Unknown (allow_url_fopen is Off)</strong></span>';
			$result['web_server'] = 'OS: ' . PHP_OS . ' | SAPI: ' . PHP_SAPI . ' | Status: ' . $status;
		}
		
		/* FTP FUNCTIONS */
		if (!$permission) $result['ftp_functions'] = $text_no_permission;
		else {
			$ftp = array();
			
			if (function_exists('ftp_ssl_connect')) {
				$ftp[] = 'ftp_ssl_connect()';
			}
			if (function_exists('ftp_connect')) {
				$ftp[] = 'ftp_connect()';
			}
			
			$result['ftp_functions'] = empty($ftp) ? 'No FTP functions available.' : implode(', ', $ftp);
		}
		
		/* OpenSSL */
		if (!$permission) $result['openssl'] = $text_no_permission;
		else {
			$result['openssl'] = function_exists('openssl_open') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* CURL */
		if (!$permission) $result['curl'] = $text_no_permission;
		else {
			if (function_exists('curl_init')) {
				$info = curl_version();
				$curl = '<span class="info-success"><strong>YES</strong></span> | Version: ' . $info['version'] . ' | Protocols: ' . implode(', ', $info['protocols']);
			} else {
				$curl = '<span class="info-error"><strong>NO</strong></span>';
			}
			
			$result['curl'] = $curl;
		}
		
		/* MemCache */
		if (!$permission) $result['memcache'] = $text_no_permission;
		else {
			$result['memcache'] = class_exists('Memcache') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* exec() */
		if (!$permission) $result['exec'] = $text_no_permission;
		else {
			$exec_enabled = $this->exec_enabled();
			
			$result['exec'] = $exec_enabled ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* zlib */
		if (!$permission) $result['zlib'] = $text_no_permission;
		else {
			$result['zlib'] = function_exists('gzencode') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* safe mode */
		if (!$permission) $result['safe_mode'] = $text_no_permission;
		else {
			$safe_mode = (strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0);
			
			$result['safe_mode'] = $safe_mode ? '<span><strong>Enabled</strong></span>' : '<span><strong>Disabled</strong></span>';
		}
		
		if (function_exists('apache_get_modules')) {
			$modules = strtolower(implode('|', apache_get_modules()));
		} else {
			$shell_exec_enabled =
				 function_exists('shell_exec') &&
				 !in_array('shell_exec', array_map('trim',explode(', ', ini_get('disable_functions')))) &&
						  !(strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0);
						  
		    if ($shell_exec_enabled) {
				$modules = strtolower(shell_exec('/usr/local/apache/bin/apachectl -l'));
			} else {
				$modules = false;
			}
		}
		
		/* mod_deflate */
		if (!$permission) $result['mod_deflate'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_deflate') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_deflate'] = $mod_result;
		}
		
		/* mod_env */
		if (!$permission) $result['mod_env'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_env') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_env'] = $mod_result;
		}
		
		/* mod_expires */
		if (!$permission) $result['mod_expires'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_expires') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_expires'] = $mod_result;
		}
		
		/* mod_headers */
		if (!$permission) $result['mod_headers'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_headers') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_headers'] = $mod_result;
		}
		
		/* mod_mime */
		if (!$permission) $result['mod_mime'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_mime') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_mime'] = $mod_result;
		}
		
		/* mod_rewrite */
		if (!$permission) $result['mod_rewrite'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_rewrite') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_rewrite'] = $mod_result;
		}
		
		/* mod_setenvif */
		if (!$permission) $result['mod_setenvif'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_setenvif') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_setenvif'] = $mod_result;
		}
		
		/* path_system_nitro_cache */
		if (!$permission) $result['path_system_nitro_cache'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/cache') && is_writable(DIR_SYSTEM . 'nitro/cache')) $result['path_system_nitro_cache'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/cache')) $result['path_system_nitro_cache'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_cache'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_assets */
		if (!$permission) $result['path_assets'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . '../assets') && is_writable(DIR_SYSTEM . 'nitro/cache')) $result['path_assets'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . '../assets')) $result['path_assets'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_assets'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data */
		if (!$permission) $result['path_system_nitro_data'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data') && is_writable(DIR_SYSTEM . 'nitro/data')) $result['path_system_nitro_data'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data')) $result['path_system_nitro_data'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_googlepagespeed */
		if (!$permission) $result['path_system_nitro_data_googlepagespeed'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/googlepagespeed.tpl')) $result['path_system_nitro_data_googlepagespeed'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed.tpl')) $result['path_system_nitro_data_googlepagespeed'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_googlepagespeed'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_persistence */
		if (!$permission) $result['path_system_nitro_data_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/persistence.tpl')) $result['path_system_nitro_data_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/persistence.tpl')) $result['path_system_nitro_data_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_amazon_persistence */
		if (!$permission) $result['path_system_nitro_data_amazon_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl')) $result['path_system_nitro_data_amazon_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl')) $result['path_system_nitro_data_amazon_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_amazon_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_ftp_persistence */
		if (!$permission) $result['path_system_nitro_data_ftp_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl')) $result['path_system_nitro_data_ftp_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl')) $result['path_system_nitro_data_ftp_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_ftp_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		return $result;
	}
	
	private function setSmushProgress($smushedNumber, $kb_saved = 0, $smush_timestamp = false, $file = false, $percent = false) {
		$this->openSession();
		$_SESSION['smush_progress']['smushed_images_count'] = $smushedNumber;
		
		if (!empty($kb_saved)) $_SESSION['smush_progress']['kb_saved'] += (float)number_format($kb_saved, 2);
		if (!empty($smush_timestamp)) $_SESSION['smush_progress']['last_smush_timestamp'] = $smush_timestamp;
		if (!empty($file)) {
			$_SESSION['smush_progress']['smushed_files'][] = array(
				'file' => $file,
				'percent' => (!empty($percent) ? $percent : 0)
			);
		}
		$this->setSmushitPersistence($_SESSION['smush_progress']);
		$this->closeSession();
		
		//$file = DIR_SYSTEM . 'nitro/data/smush_refresh.cache';
		
		//$data = '<div class="ftp-alert-' . $type . '">' . $data . '</div>';
		
		//file_put_contents($file, $data . "<br />", FILE_APPEND);
	}
	
	private function setSmushProgressMessage($msg) {
		$this->openSession();
		$_SESSION['smush_progress']['messages'][] = $msg;
		$this->closeSession();
	}
	
	private function setHtaccessFileContent($newcontent) {
		$htaccessFile = DIR_SYSTEM.'../.htaccess';
		$htaccessFileBackup = DIR_SYSTEM.'../.htaccess-backup';
		if (!is_writable($htaccessFile)) {
			$this->session->data['error'] = 'Your PHP user does not have write permission to the .htaccess file. Please set it or contact your hosting provider.';	
			return false;
		}
		if (!file_exists($htaccessFile)) {
			file_put_contents($htaccessFile,'');
		}
		if (!file_exists($htaccessFileBackup)) {
			copy($htaccessFile,$htaccessFileBackup);
		}

		file_put_contents($htaccessFile,$newcontent);
		chmod($htaccessFile,0644);
		return true;
	}
	
	private function getHtaccessFileContent() {
		$htaccessFile = DIR_SYSTEM.'../.htaccess';
		if (!file_exists($htaccessFile)) {
			file_put_contents($htaccessFile,'');
		}
		
		return file_get_contents($htaccessFile);
	}
	
	private function extractNitrocodeFromHtaccessFile($htaccessContent) {
		$nitrocode = $this->getStringBetween(PHP_EOL.'# STARTNITRO'.PHP_EOL,'# ENDNITRO'.PHP_EOL,$htaccessContent);
		if (strpos($nitrocode,'STARTNITRO') == false) {
			return '';
		}
		return $nitrocode;
	}
	
	private function extractNitrocodeCompressFromHtaccessFile($htaccessContent) {
		$nitrocode = $this->getStringBetween('# STARTCOMPRESSNITRO'.PHP_EOL,'# ENDCOMPRESSNITRO'.PHP_EOL,$htaccessContent);
		if (strpos($nitrocode,'STARTCOMPRESSNITRO') == false) {
			return '';
		}
		return $nitrocode;
	}
	
	private function getStringBetween($var1="",$var2="",$pool){
		$temp1 = strpos($pool,$var1);
		$result = substr($pool,$temp1,strlen($pool));
		$dd=strpos($result,$var2);
		if($dd == 0){
			$dd = strlen($result);
		}
		return substr($result,0,$dd+strlen($var2));
	}
	
	private function delete_folder($folder) {
		if (in_array($folder, array('.', '..'))) return;
		
		if (file_exists($folder)) {
			if (is_writeable($folder)) {
				if (is_dir($folder)) {
					if (substr($folder, strlen($folder) - 1, 1) == DS) $folder = substr($folder, 0, strlen($folder) - 1);
					
					$files = scandir($folder);
					foreach ($files as $file) {
						if (in_array($file, array('.', '..'))) continue;
						$this->delete_folder($folder . DS . $file);
					}
					
					if (!rmdir($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				} else {
					if (!unlink($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				}
			} else throw new Exception('Delete not successful. The path ' . $folder . ' is not writable.');
		}
	}
	
	private function list_files_with_ext($site_root, $ext) {
		$output = array();
		
		if ($this->exec_enabled()) {
			if (!is_array($ext)) {
				exec('find ' . $site_root . ' -type f -name "*.' . $ext . '"', $output);
			} else {
				$output = array();
				foreach ($ext as $ex) {
					exec('find ' . $site_root . ' -type f -name "*.' . $ex . '"', $sub_output);
					$output = array_merge($output, $sub_output);
				}
			}
		} else {
			if (!is_array($ext)) {
				$output = $this->list_files_with_ext_rec($site_root, $ext);
			} else {
				$output = array();
				foreach ($ext as $ex) {
					$output = array_merge($output, $this->list_files_with_ext_rec($site_root, $ex));
				}
			}
		}
		
		// The images need to be served only from image/cache
		if (is_array($ext)) {
			foreach ($output as $i => $file) {
				if (stripos($file, $site_root . 'image/cache') !== 0) unset($output[$i]);
			}
		}
		
		return $output;
	}
	
	private function list_files_with_ext_rec($site_root, $ext) {
		$result = array();
		
		if (is_dir($site_root)) {
			if (substr($site_root, strlen($site_root) - 1, 1) == DS) $site_root = substr($site_root, 0, strlen($site_root) - 1);
					
			$files = scandir($site_root);
			foreach ($files as $file) {
				if (in_array($file, array('.', '..'))) continue;
				$result = array_merge($result, $this->list_files_with_ext_rec($site_root . DS . $file, $ext));
			}
		} else {
			if (substr($site_root, strlen($site_root) - strlen($ext)) == $ext) $result[] = $site_root;
		}
		
		return $result;
	}
	
	private function exec_enabled() {
		return function_exists('exec') &&
			!in_array('exec', array_map('trim',explode(', ', ini_get('disable_functions')))) &&
			!(strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0) && strtolower(PHP_OS) == 'linux';
	}
	
	private function isSessionClosed() {
		return $this->session_closed;
	}
	
	private function closeSession() {
		if (session_id() && !$this->session_closed) session_write_close();
		$this->session_closed = true;
	}
	
	private function openSession() {
		if ($this->session_closed) session_start();
		$this->session_closed = false;
		return session_id();
	}
	
	private function smushCanContinue() {
		$this->openSession();
		$stop_smushing = $_SESSION['stop_smushing'];
		$this->closeSession();
		return !$stop_smushing;
	}
}
?>