<?php
class ModelToolImage extends Model {
	/**
	*	
	*	@param filename string
	*	@param width 
	*	@param height
	*	@param type char [default, w, h]
	*				default = scale with white space, 
	*				w = fill according to width, 
	*				h = fill according to height
	*	
	*/

				private function cdn_rewrite($host_url, $new_image) {
					$host_url = preg_replace('@image\/?$@', '', $host_url);
					require_once(DIR_SYSTEM.'nitro/config.php');
					require_once(DIR_SYSTEM.'nitro/core/core.php');
					$nitroPersistence = getNitroPersistence();
					$nitro_result = $host_url . 'image/' . $new_image;
					
					if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNStandard']['Enabled']) && $nitroPersistence['Nitro']['CDNStandard']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNStandard']['ServeImages']) && $nitroPersistence['Nitro']['CDNStandard']['ServeImages'] == 'yes' && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
					
						$nitro_has_ftp_persistence = false;
						
						$nitro_has_ftp_to_cdn = !empty($nitroPersistence['Nitro']['CDNStandardFTP']['Enabled']) && $nitroPersistence['Nitro']['CDNStandardFTP']['Enabled'] == 'yes';
						
						if ($nitro_has_ftp_to_cdn) {
							$nitro_ftp_persistence = getFTPPersistence();
							$nitro_has_ftp_persistence = in_array('image/' . $new_image, $nitro_ftp_persistence);
						} else {
							$nitro_has_ftp_persistence = true;
						}
					
						if ($nitro_has_ftp_persistence && !empty($nitroPersistence['Nitro']['CDNStandard']['ImagesHttpsUrl'])) {
							$nitro_url = substr($nitroPersistence['Nitro']['CDNStandard']['ImagesHttpsUrl'], strlen($nitroPersistence['Nitro']['CDNStandard']['ImagesHttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNStandard']['ImagesHttpsUrl'] : $nitroPersistence['Nitro']['CDNStandard']['ImagesHttpsUrl'] . '/';
							$nitro_result = $nitro_url . 'image/' . $new_image;
						} else if ($nitro_has_ftp_persistence && !empty($nitroPersistence['Nitro']['CDNStandard']['ImagesHttpUrl'])) {
							$nitro_url = substr($nitroPersistence['Nitro']['CDNStandard']['ImagesHttpUrl'], strlen($nitroPersistence['Nitro']['CDNStandard']['ImagesHttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNStandard']['ImagesHttpUrl'] : $nitroPersistence['Nitro']['CDNStandard']['ImagesHttpUrl'] . '/';
							$nitro_result = $nitro_url . 'image/' . $new_image;
						}
					} else if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNAmazon']['Enabled']) && $nitroPersistence['Nitro']['CDNAmazon']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNAmazon']['ServeImages']) && $nitroPersistence['Nitro']['CDNAmazon']['ServeImages'] == 'yes' && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
						
						$nitro_amazon_persistence = getAmazonPersistence();
						$nitro_has_amazon_persistence = in_array('image/' . $new_image, $nitro_amazon_persistence);
					
						if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'])) {
							$nitro_url = substr($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'], strlen($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'] : $nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'] . '/';
							$nitro_result = $nitro_url . 'image/' . $new_image;
						} else if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'])) {
							$nitro_url = substr($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'], strlen($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'] : $nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'] . '/';
							$nitro_result = $nitro_url . 'image/' . $new_image;
						}
					} else if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNRackspace']['Enabled']) && $nitroPersistence['Nitro']['CDNRackspace']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNRackspace']['ServeImages']) && $nitroPersistence['Nitro']['CDNRackspace']['ServeImages'] == 'yes' && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
						
						$nitro_amazon_persistence = getRackspacePersistence();
						$nitro_has_amazon_persistence = in_array('image/' . $new_image, $nitro_amazon_persistence);
					
						if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpsUrl'])) {
							$nitro_url = substr($nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpsUrl'], strlen($nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpsUrl'] : $nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpsUrl'] . '/';
							$nitro_result = $nitro_url . 'image/' . $new_image;
						} else if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpUrl'])) {
							$nitro_url = substr($nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpUrl'], strlen($nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpUrl'] : $nitroPersistence['Nitro']['CDNRackspace']['ImagesHttpUrl'] . '/';
							$nitro_result = $nitro_url . 'image/' . $new_image;
						}
					}
					
					return $nitro_result;
				}
			
	public function resize($filename, $width, $height, $type = "") {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);


				require_once(DIR_SYSTEM.'nitro/config.php');
				require_once(DIR_SYSTEM.'nitro/core/core.php');
				$nitroPersistence = getNitroPersistence();
				if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['ImageCache']['OverrideCompression']) && $nitroPersistence['Nitro']['ImageCache']['OverrideCompression'] == 'yes') {
					
				}
			
			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);

				if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['PageCache']['Enabled']) && $nitroPersistence['Nitro']['PageCache']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['SmushIt']['OnDemand']) && $nitroPersistence['Nitro']['SmushIt']['OnDemand'] == 'yes' && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
					include_once dirname(DIR_SYSTEM) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'smushit.php';
					try {
						$file = new SmushIt(DIR_IMAGE . $new_image, SmushIt::LOCAL_ORIGIN);
						// And finaly, replace original file by the compressed version
						// Sometimes, Smush.it convert files. We don't want that to happen.
						
						$src = pathinfo($file->source, PATHINFO_EXTENSION);
						$dst = pathinfo($file->destination, PATHINFO_EXTENSION);
						if ($src == $dst AND copy($file->destination, DIR_IMAGE . $new_image)) {
							// Success !
							//echo 'Smushed File: '.$source.'<br>';
						}
					} catch(Exception $e) {
						set_time_limit(30);
					}
				}
			
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);

				if (!empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['PageCache']['Enabled']) && $nitroPersistence['Nitro']['PageCache']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['SmushIt']['OnDemand']) && $nitroPersistence['Nitro']['SmushIt']['OnDemand'] == 'yes' && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
					include_once dirname(DIR_SYSTEM) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'smushit.php';
					try {
						$file = new SmushIt(DIR_IMAGE . $new_image, SmushIt::LOCAL_ORIGIN);
						// And finaly, replace original file by the compressed version
						// Sometimes, Smush.it convert files. We don't want that to happen.
						
						$src = pathinfo($file->source, PATHINFO_EXTENSION);
						$dst = pathinfo($file->destination, PATHINFO_EXTENSION);
						if ($src == $dst AND copy($file->destination, DIR_IMAGE . $new_image)) {
							// Success !
							//echo 'Smushed File: '.$source.'<br>';
						}
					} catch(Exception $e) {
						set_time_limit(30);
					}
				}
			
			}
		}

		return $this->getImageUrl($new_image);
		
	}

	protected function getImageUrl($new_image) {
		$parts = explode('/', $new_image);
		$new_url = implode('/', array_map('rawurlencode', $parts));
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_url;
		}
		else {
			return $this->config->get('config_url') . 'image/' . $new_url;
		}
	}
}
