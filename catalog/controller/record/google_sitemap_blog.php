<?php
class ControllerRecordGoogleSitemapBlog extends Controller
{
	protected  $data;
	protected  $languages;
	protected  $config_language_id;
	protected  $config_language_code;
	protected  $config_language_original;
	protected  $google_sitemap_blog_language_status;
	protected  $cache;
	protected  $expire;

	public function __construct($registry)
	{
		parent::__construct($registry);

			if ($this->config->get('ascp_settings_sitemap') != '') {
				$this->data['ascp_settings_sitemap'] = $this->config->get('ascp_settings_sitemap');
			} else {
				$this->data['ascp_settings_sitemap'] = Array();
			}
            if (!isset($this->data['ascp_settings_sitemap']['google_sitemap_blog_language_status']) || !$this->data['ascp_settings_sitemap']['google_sitemap_blog_language_status']) {
             	$this->google_sitemap_blog_language_status = false;
            } else {
               $this->google_sitemap_blog_language_status = true;
            }

            if (!isset($this->data['ascp_settings_sitemap']['google_sitemap_blog_cache_status']) || !$this->data['ascp_settings_sitemap']['google_sitemap_blog_cache_status']) {
             	$this->cache = false;
            } else {
               $this->cache = true;
            }

            if (!isset($this->data['ascp_settings_sitemap']['google_sitemap_blog_cache_expire']) || $this->data['ascp_settings_sitemap']['google_sitemap_blog_cache_expire']=='') {
            	$this->expire = 360000;
            } else {
               $this->expire = $this->data['ascp_settings_sitemap']['google_sitemap_blog_cache_expire'];
            }

            if (!$this->google_sitemap_blog_language_status) {
             	$sql_language = " AND language_id = '".$this->config->get('config_language_id')."'";
            } else {
	            $sql_language = '';
            }

            $sql = "SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ".$sql_language ;
			$query_lang = $this->db->query($sql);
			foreach ($query_lang->rows as $result_lang) {
				$this->languages[$result_lang['code']] = $result_lang;
			}
			$this->config_language_id = $this->config->get('config_language_id');
            $this->config_language_code = $this->config->get('config_language');
            $this->config_language_original	= $this->registry->get('language');

            if (!$this->registry->get('admin_work')) {
            	if (!defined('DIR_CATALOG')) {
            		define('DIR_CATALOG', DIR_APPLICATION);
            	}
            }

            if ($this->registry->get('admin_work')) {

            		require_once(DIR_CATALOG . 'controller/common/seoblog.php');
					$seoBlog = new ControllerCommonSeoBlog ($this->registry);

	                $seo_type = $this->config->get('config_seo_url_type');
					if (!$seo_type) {
					  $seo_type = 'seo_url';
					}

					require_once(DIR_CATALOG . 'controller/common/' . $seo_type . '.php');
	                $classSeo = 'ControllerCommon'.str_replace('_','',$seo_type);
					$seoUrl = new $classSeo($this->registry);

					$this->config->set('config_ssl', HTTPS_CATALOG );
					$this->config->set('config_url', HTTP_CATALOG);

                	$this->url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG);
                	$this->url->addRewrite($seoUrl);
                	$this->url->addRewrite($seoBlog);

            }
	}


	public function get_sitemap_content()
	{
		if ($this->config->get('google_sitemap_blog_status'))
		{
       		$ver = VERSION;
			if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);

			$cache = new sitemapCache($this->expire );

			$output = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$this->load->model('catalog/product', DIR_CATALOG);
			$output .= '<url>';
			$output .= '<loc>' . $this->config->get('config_url') . '</loc>';
			$output .= '<changefreq>always</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';


			$cache_file = 'blog.sitemap.products.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');
			$product_cache = $cache->get($cache_file);
			if (!isset($product_cache) || !$this->cache) {
				$product_output = '';
				$products       = $this->model_catalog_product->getProducts();
				$url_tmp = $url = '';
				foreach ($products as $product) {


		            foreach ($this->languages as $code => $lang) {
		             if ($this->google_sitemap_blog_language_status) {
		             	$this->switchLanguage($this->languages[$code]['language_id'], $code);
		             }

		             $url = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $product['product_id'])));
		             if ($url!=$url_tmp) {
							$product_output .= '<url>';
							$product_output .= '<loc>' .rawurldecode($url). '</loc>';
							$product_output .= '<lastmod>' . substr(max($product['date_added'], $product['date_modified']), 0, 10) . '</lastmod>';
							$product_output .= '<changefreq>weekly</changefreq>';
							$product_output .= '<priority>1.0</priority>';
							$product_output .= '</url>';
					 }
					 $url_tmp = $url;

					}

				} //$products as $product
				$cache->set($cache_file, $product_output);
				$output .= $product_output;
			} else {
				$output .= $product_cache;
			}



			$this->load->model('catalog/category', DIR_CATALOG);
			$cache_file = 'blog.sitemap.categories.'  .  (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$categories_cache = $cache->get($cache_file);
			if (!isset($categories_cache)  || !$this->cache) {
				$categories_output = $this->getCategories(0);
				$cache->set($cache_file, $categories_output);
				$output .= $categories_output;
			} //!isset($categories_cache)
			else {
				$output .= $categories_cache;
			}



			$this->load->model('catalog/manufacturer', DIR_CATALOG);
			$cache_file = 'blog.sitemap.manufacturer.'  .  (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$manufacturer_cache = $cache->get($cache_file);
			if (!isset($manufacturer_cache)  || !$this->cache) {
				$manufacturers_output = '';
			 	$url_tmp = $url = '';
				$manufacturers        = $this->model_catalog_manufacturer->getManufacturers();
				foreach ($manufacturers as $manufacturer) {
            foreach ($this->languages as $code => $lang) {
             		 if ($this->google_sitemap_blog_language_status) {
		             	$this->switchLanguage($this->languages[$code]['language_id'], $code);
		             }

					if (SCP_VERSION < 2) {
						$url = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/manufacturer/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'])));
					} else {
						$url = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])));
					}

                   if ($url!=$url_tmp) {
						$manufacturers_output .= '<url>';
						$manufacturers_output .= '<loc>' . rawurldecode($url) . '</loc>';
						$manufacturers_output .= '<changefreq>weekly</changefreq>';
						$manufacturers_output .= '<priority>0.7</priority>';
						$manufacturers_output .= '</url>';
                    }
					$url_tmp = $url;
			}

				} //$manufacturers as $manufacturer
				$cache->set($cache_file, $manufacturers_output);
				$output .= $manufacturers_output;
			} //!isset($manufacturer_cache)
			else {
				$output .= $manufacturer_cache;
			}



			$this->load->model('catalog/information', DIR_CATALOG);
			$cache_file = 'blog.sitemap.information.'  .  (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

			$information_cache = $cache->get($cache_file);
			if (!isset($information_cache)  || !$this->cache) {
				$information_output = '';
				$url_tmp = $url = '';
				$informations       = $this->model_catalog_information->getInformations();
				foreach ($informations as $information) {
            foreach ($this->languages as $code => $lang) {
             		 if ($this->google_sitemap_blog_language_status) {
		             	$this->switchLanguage($this->languages[$code]['language_id'], $code);
		             }
					$url =str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('information/information', 'information_id=' . $information['information_id'])));
                   if ($url!=$url_tmp) {

					$information_output .= '<url>';
					$information_output .= '<loc>' . rawurldecode($url) . '</loc>';
					$information_output .= '<changefreq>weekly</changefreq>';
					$information_output .= '<priority>0.5</priority>';
					$information_output .= '</url>';
}
					$url_tmp = $url;
			}
				} //$informations as $information
				$cache->set($cache_file, $information_output);
				$output .= $information_output;
			} //!isset($information_cache)
			else {
				$output .= $information_cache;
			}


 	        $output .= $this->getascp($output);

            $this->switchLanguage($this->config_language_id, $this->config_language_code);

  			$this->registry->set('language', $this->config_language_original);

			$output .= '</urlset>';

    	    return $output;

		} //$this->config->get('google_sitemap_status')
	}

	public function index()
    {
			$output = $this->get_sitemap_content();
			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
    }


	public function gen_sitemap()
    {
			$this->load->language('record/google_sitemap_blog');

			$token = $this->session->data['token'];
	   		if (($this->request->server['REQUEST_METHOD'] == 'POST')&& $this->request->post['token'] == $token) {
				$output = $this->get_sitemap_content();
                file_put_contents(str_replace("../", "", $this->request->post['path']), $output);
                $html = $this->language->get('succes_create_file');
			} else {
				$html = $this->language->get('error_permission');
			}
			$this->response->setOutput($html);
    }



	public function switchLanguage($language_id, $code)
	{
			$this->session->data['language'] = $code;
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
			$this->config->set('config_language_id', $language_id);
			$this->config->set('config_language', $code);
			$language = new Language($this->langcode_all[$code]['directory']);
			$this->registry->set('language', $language);

	}

	public function getascp()
	{
            $output = '';
            $url_tmp = $url = '';
            $cache_status = true;
            $cache = new sitemapCache($this->expire);
            $this->load->model('catalog/record', DIR_CATALOG);
			$this->load->model('catalog/blog', DIR_CATALOG);


				$cache_file = 'blog.sitemap.records.'  . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');
				$records_cache = $cache->get($cache_file);

				if (!isset($records_cache) || !$this->cache) {
					$records_output = '';

    	        if (!$this->registry->get('admin_work')) {
					if (SCP_VERSION > 1) {
						$this->load->controller('common/seoblog');
					} else {
		 	            $this->getChild('common/seoblog');
		            }
	            }
					$records = $this->model_catalog_record->getRecords();

					if ($records) {
						foreach ($records as $record) {

            foreach ($this->languages as $code => $lang) {
                     if ($this->google_sitemap_blog_language_status) {
		             	$this->switchLanguage($this->languages[$code]['language_id'], $code);
		             }
					$url = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('record/record', 'record_id=' . $record['record_id'])));
                   if ($url!=$url_tmp) {
							$records_output .= '<url>';
							$records_output .= '<loc>'. rawurldecode($url) . '</loc>';
							$records_output .= '<lastmod>' . substr(max($record['date_available'], $record['date_modified']), 0, 10) . '</lastmod>';
							$records_output .= '<changefreq>weekly</changefreq>';
							$records_output .= '<priority>1.0</priority>';
							$records_output .= '</url>';
					}
				$url_tmp = $url;
			}

						} //$records as $record
					} //$records
					$cache->set($cache_file, $records_output);
					$output .= $records_output;
				} //!isset($records_cache)
				else {
					$output .= $records_cache;
				}



				$cache_file = 'blog.sitemap.blogies.' . (int) $this->config->get('config_store_id') . '.' . (int) $this->config->get('config_language_id');

				$blogies_cache = $cache->get($cache_file);
				if (!isset($blogies_cache) || !$this->cache) {

	            if (!$this->registry->get('admin_work')) {
					if (SCP_VERSION > 1) {
						$this->load->controller('common/seoblog');
					} else {
		 	            $this->getChild('common/seoblog');
		            }
                }
					$blogies_output = $this->getBlogies(0);
					$cache->set($cache_file, $blogies_output);
					$output .= $blogies_output;
				} //!isset($blogies_cache)
				else {
					$output .= $blogies_cache;
				}



            $this->switchLanguage($this->config_language_id, $this->config_language_code);
            return $output;

	}

	protected function getCategories($parent_id, $current_path = '')
	{
		$output  = '';
		$array_of  = array();
	 	$url_tmp = $url = '';
		$results = $this->model_catalog_category->getCategories($parent_id);

		if ($results) {
		 foreach ($results as $result) {

            foreach ($this->languages as $code => $lang) {
                if ($this->google_sitemap_blog_language_status) {
		          	$this->switchLanguage($this->languages[$code]['language_id'], $code);
		        }

				if (!$current_path) {
					$new_path = $result['category_id'];
				} else {
					$new_path = $current_path . '_' . $result['category_id'];
				}
				$url = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $new_path)));

				if ($url!=$url_tmp) {
					$output .= '<url>';
					$output .= '<loc>' . rawurldecode($url) . '</loc>';
					$output .= '<lastmod>' . substr(max($result['date_added'], $result['date_modified']), 0, 10) . '</lastmod>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>0.7</priority>';
					$output .= '</url>';

					if (!isset($array_of[$result['category_id']]) || $array_of[$result['category_id']]!=$new_path) {
						$output .= $this->getCategories($result['category_id'], $new_path);
						$array_of[$result['category_id']] = $new_path;
					}
                }
                $url_tmp = $url;
              }

			} //$results as $result
		} //$results
		return $output;
	}
	public function getBlogies($parent_id, $current_path = '')
	{
		$output  = '';
		$url_tmp = $url = '';
		$array_of  = array();
		$results = $this->model_catalog_blog->getBlogies($parent_id);
		foreach ($results as $result) {
            foreach ($this->languages as $code => $lang) {
             		 if ($this->google_sitemap_blog_language_status) {
		             	$this->switchLanguage($this->languages[$code]['language_id'], $code);
		             }

			if (!$current_path) {
				$new_path = $result['blog_id'];
			} 	else {
				$new_path = $current_path . '_' . $result['blog_id'];
			}
			$url = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('record/blog', 'blog_id=' . $new_path)));
            if ($url!=$url_tmp) {
			$output .= '<url>';
			$output .= '<loc>' . rawurldecode($url) . '</loc>';
			$output .= '<lastmod>' . substr(max($result['date_added'], $result['date_modified']), 0, 10) . '</lastmod>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';
			if (!isset($array_of[$result['blog_id']]) || $array_of[$result['blog_id']]!=$new_path) {
				$output .= $this->getBlogies($result['blog_id'], $new_path);
				$array_of[$result['blog_id']] = $new_path;
			}

   }
                $url_tmp = $url;
              }
		} //$results as $result
		return $output;
	}
}

class sitemapCache {
	public $expire = 360000;

  	public function __construct($expire) {
		$files = glob(DIR_CACHE . 'cache.blog.*');
        $this->expire = $expire;

		if ($files) {
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

      			if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);
					}
      			}
    		}
		}
  	}

	public function get($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$cache = file_get_contents($files[0]);

			return unserialize($cache);
		}
	}

  	public function set($key, $value) {
    	$this->delete($key);

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
				}
    		}
		}
  	}
}

?>