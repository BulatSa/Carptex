<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();

				require_once(DIR_SYSTEM.'nitro/config.php');
				require_once(DIR_SYSTEM.'nitro/core/core.php');
				$nitroPersistence = getNitroPersistence();
				
				$nitro_has_ftp_to_cdn = !empty($nitroPersistence['Nitro']['CDNStandardFTP']['Enabled']) && $nitroPersistence['Nitro']['CDNStandardFTP']['Enabled'] == 'yes';
				
				if ($nitro_has_ftp_to_cdn) {
					$nitro_ftp_persistence = getFTPPersistence();
				} else {
					$nitro_ftp_persistence = array();
				}
				
				$nitro_amazon_persistence = getAmazonPersistence();
				$nitro_rackspace_persistence = getRackspacePersistence();
				
				$nitro_has_css_minification = (!empty($nitroPersistence['Nitro']['Mini']['Enabled']) && $nitroPersistence['Nitro']['Mini']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['Mini']['CSS']) && $nitroPersistence['Nitro']['Mini']['CSS'] == 'yes');
				
				$nitro_amazon_condition = !empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNAmazon']['Enabled']) && $nitroPersistence['Nitro']['CDNAmazon']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNAmazon']['ServeCSS']) && $nitroPersistence['Nitro']['CDNAmazon']['ServeCSS'] == 'yes';
				$nitro_rackspace_condition = !empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNRackspace']['Enabled']) && $nitroPersistence['Nitro']['CDNRackspace']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNRackspace']['ServeCSS']) && $nitroPersistence['Nitro']['CDNRackspace']['ServeCSS'] == 'yes';
				
				$nitro_css_minify_condition = ($nitro_has_css_minification && $nitro_has_ftp_to_cdn) || !$nitro_has_css_minification;

				if (($nitro_css_minify_condition || $nitro_amazon_condition || $nitro_rackspace_condition) && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
				
					foreach ($this->data['styles'] as $nitro_index => $nitro_value) {
					
						$nitro_relative = stripos($nitro_value['href'], 'http://') === 0 ? false : (stripos($nitro_value['href'], 'https://') === 0 ? false : true);
						
						if ($nitro_css_minify_condition && $nitro_relative && !empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNStandard']['Enabled']) && $nitroPersistence['Nitro']['CDNStandard']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNStandard']['ServeCSS']) && $nitroPersistence['Nitro']['CDNStandard']['ServeCSS'] == 'yes') {
							$nitro_has_ftp_persistence = false;
							if ($nitro_has_ftp_to_cdn) {
								$nitro_check_ftp_value = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_has_ftp_persistence = in_array($nitro_check_ftp_value, $nitro_ftp_persistence);
							} else {
								$nitro_has_ftp_persistence = true;
							}
						
							if ($nitro_has_ftp_persistence && !empty($nitroPersistence['Nitro']['CDNStandard']['CSSHttpsUrl']) && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
								$nitro_href = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_url = substr($nitroPersistence['Nitro']['CDNStandard']['CSSHttpsUrl'], strlen($nitroPersistence['Nitro']['CDNStandard']['CSSHttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNStandard']['CSSHttpsUrl'] : $nitroPersistence['Nitro']['CDNStandard']['CSSHttpsUrl'] . '/';
								
								$this->data['styles'][$nitro_index]['href'] = $nitro_url . $nitro_href;
							} else if ($nitro_has_ftp_persistence && !empty($nitroPersistence['Nitro']['CDNStandard']['CSSHttpUrl'])) {
								$nitro_href = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_url = substr($nitroPersistence['Nitro']['CDNStandard']['CSSHttpUrl'], strlen($nitroPersistence['Nitro']['CDNStandard']['CSSHttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNStandard']['CSSHttpUrl'] : $nitroPersistence['Nitro']['CDNStandard']['CSSHttpUrl'] . '/';
								
								$this->data['styles'][$nitro_index]['href'] = $nitro_url . $nitro_href;
							}
						} else if ($nitro_amazon_condition && $nitro_relative) {
							
							$nitro_check_amazon_value = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
							$nitro_has_amazon_persistence = in_array($nitro_check_amazon_value, $nitro_amazon_persistence);
						
							if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl']) && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
								$nitro_href = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_url = substr($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'], strlen($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'] : $nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'] . '/';
								
								$this->data['styles'][$nitro_index]['href'] = $nitro_url . $nitro_href;
							} else if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'])) {
								$nitro_href = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_url = substr($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'], strlen($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'] : $nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'] . '/';
								
								$this->data['styles'][$nitro_index]['href'] = $nitro_url . $nitro_href;
							}
						} else if ($nitro_rackspace_condition && $nitro_relative) {
							
							$nitro_check_rackspace_value = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
							$nitro_has_rackspace_persistence = in_array($nitro_check_rackspace_value, $nitro_rackspace_persistence);
						
							if ($nitro_has_rackspace_persistence && !empty($nitroPersistence['Nitro']['CDNRackspace']['CSSHttpsUrl']) && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
								$nitro_href = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_url = substr($nitroPersistence['Nitro']['CDNRackspace']['CSSHttpsUrl'], strlen($nitroPersistence['Nitro']['CDNRackspace']['CSSHttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNRackspace']['CSSHttpsUrl'] : $nitroPersistence['Nitro']['CDNRackspace']['CSSHttpsUrl'] . '/';
								
								$this->data['styles'][$nitro_index]['href'] = $nitro_url . $nitro_href;
							} else if ($nitro_has_rackspace_persistence && !empty($nitroPersistence['Nitro']['CDNRackspace']['CSSHttpUrl'])) {
								$nitro_href = substr($nitro_value['href'], 0, 1) == '/' ? substr($nitro_value['href'], 1) : $nitro_value['href'];
								$nitro_url = substr($nitroPersistence['Nitro']['CDNRackspace']['CSSHttpUrl'], strlen($nitroPersistence['Nitro']['CDNRackspace']['CSSHttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNRackspace']['CSSHttpUrl'] : $nitroPersistence['Nitro']['CDNRackspace']['CSSHttpUrl'] . '/';
								
								$this->data['styles'][$nitro_index]['href'] = $nitro_url . $nitro_href;
							}
						}
					
					}
				
				}
				
			
		$this->data['scripts'] = $this->document->getScripts();

			
				$nitro_has_js_minification = (!empty($nitroPersistence['Nitro']['Mini']['Enabled']) && $nitroPersistence['Nitro']['Mini']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['Mini']['JS']) && $nitroPersistence['Nitro']['Mini']['JS'] == 'yes');
				
				$nitro_js_minify_condition = ($nitro_has_js_minification && $nitro_has_ftp_to_cdn) || !$nitro_has_js_minification;
				$nitro_rackspace_condition = !empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNRackspace']['Enabled']) && $nitroPersistence['Nitro']['CDNRackspace']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNRackspace']['ServeJavaScript']) && $nitroPersistence['Nitro']['CDNRackspace']['ServeJavaScript'] == 'yes';
				
				if (($nitro_js_minify_condition || $nitro_amazon_condition || $nitro_rackspace_condition) && !(function_exists('areWeInIgnoredUrl') && areWeInIgnoredUrl())) {
					foreach ($this->data['scripts'] as $nitro_index => $nitro_value) {
					
						$nitro_relative = stripos($nitro_value, 'http://') === 0 ? false : (stripos($nitro_value, 'https://') === 0 ? false : true);
						
						if ($nitro_js_minify_condition && $nitro_relative && !empty($nitroPersistence['Nitro']['Enabled']) && $nitroPersistence['Nitro']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNStandard']['Enabled']) && $nitroPersistence['Nitro']['CDNStandard']['Enabled'] == 'yes' && !empty($nitroPersistence['Nitro']['CDNStandard']['ServeJavaScript']) && $nitroPersistence['Nitro']['CDNStandard']['ServeJavaScript'] == 'yes') {
						
							$nitro_has_ftp_persistence = false;
							if ($nitro_has_ftp_to_cdn) {
								$nitro_check_ftp_value = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_has_ftp_persistence = in_array($nitro_check_ftp_value, $nitro_ftp_persistence);
							} else {
								$nitro_has_ftp_persistence = true;
							}
						
							if ($nitro_has_ftp_persistence && !empty($nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpsUrl']) && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
								$nitro_href = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_url = substr($nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpsUrl'], strlen($nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpsUrl'] : $nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpsUrl'] . '/';
								
								$this->data['scripts'][$nitro_index] = $nitro_url . $nitro_href;
							} else if ($nitro_has_ftp_persistence && !empty($nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpUrl'])) {
								$nitro_href = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_url = substr($nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpUrl'], strlen($nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpUrl'] : $nitroPersistence['Nitro']['CDNStandard']['JavaScriptHttpUrl'] . '/';
								
								$this->data['scripts'][$nitro_index] = $nitro_url . $nitro_href;
							}
						} else if ($nitro_amazon_condition && $nitro_relative) {
							
							$nitro_check_amazon_value = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
							$nitro_has_amazon_persistence = in_array($nitro_check_amazon_value, $nitro_amazon_persistence);
						
							if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl']) && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
								$nitro_href = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_url = substr($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'], strlen($nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'] : $nitroPersistence['Nitro']['CDNAmazon']['HttpsUrl'] . '/';
								
								$this->data['scripts'][$nitro_index] = $nitro_url . $nitro_href;
							} else if ($nitro_has_amazon_persistence && !empty($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'])) {
								$nitro_href = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_url = substr($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'], strlen($nitroPersistence['Nitro']['CDNAmazon']['HttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'] : $nitroPersistence['Nitro']['CDNAmazon']['HttpUrl'] . '/';
								
								$this->data['scripts'][$nitro_index] = $nitro_url . $nitro_href;
							}
						} else if ($nitro_rackspace_condition && $nitro_relative) {
							
							$nitro_check_rackspace_value = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
							$nitro_has_rackspace_persistence = in_array($nitro_check_rackspace_value, $nitro_rackspace_persistence);
						
							if ($nitro_has_rackspace_persistence && !empty($nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpsUrl']) && isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
								$nitro_href = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_url = substr($nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'], strlen($nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpsUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'] : $nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'] . '/';
								
								$this->data['scripts'][$nitro_index] = $nitro_url . $nitro_href;
							} else if ($nitro_has_rackspace_persistence && !empty($nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpUrl'])) {
								$nitro_href = substr($nitro_value, 0, 1) == '/' ? substr($nitro_value, 1) : $nitro_value;
								$nitro_url = substr($nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpUrl'], strlen($nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpUrl']) - 1, 1) == '/' ? $nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpUrl'] : $nitroPersistence['Nitro']['CDNRackspace']['JavaScriptHttpUrl'] . '/';
								
								$this->data['scripts'][$nitro_index] = $nitro_url . $nitro_href;
							}
						}
					
					}
				}
				
			
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');
		
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';

		}		
		
		$this->language->load('common/header');
		$this->data['og_url'] = (isset($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER) . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));
		$this->data['og_image'] = $this->document->getOgImage();
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_page'] = $this->language->get('text_page');
				
		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		// Daniel's robot detector
		$status = true;
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}
		
		// A dirty hack to try to set a cookie for the multi-store feature
		$this->load->model('setting/store');
		
		$this->data['stores'] = array();
		
		if ($this->config->get('config_shared') && $status) {
			$this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			
			$stores = $this->model_setting_store->getStores();
					
			foreach ($stores as $store) {
				$this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			}
		}
				
		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}
		
		// Menu
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();
				
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				
				foreach ($children as $child) {
					//Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
					if ($this->config->get('config_product_count')) {
						$data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);
						//Optmization
						//$product_total = $this->model_catalog_product->getTotalProducts($data);
						$product_total = 0;
					}
									
					$children_data[] = array(
						//Optmization
						//'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
					);						
				}
				
				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'active'   => in_array($category['category_id'], $parts),
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		
		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart'
		);
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	} 	
}
?>
