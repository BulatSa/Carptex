<?php
class ControllerRecordBlog extends Controller
{
	protected  $data;
	public function index()
	{
		$ver = VERSION;
		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);

		$this->config->set("blog_work", true);

		if ($this->config->get('ascp_settings') != '') {
			$this->data['settings_general'] = $this->config->get('ascp_settings');
		} else {
			$this->data['settings_general'] = Array();
			$this->config->set('ascp_settings', $this->data['settings_general']);
		}

		if (SCP_VERSION > 1) {
			$this->load->controller('common/seoblog');
		} else {
            $this->getChild('common/seoblog');
        }

		$this->language->load('record/blog');

		$this->data['text_refine']     = $this->language->get('text_refine');
		$this->data['text_empty']      = $this->language->get('text_empty');
		$this->data['text_quantity']   = $this->language->get('text_quantity');
		$this->data['text_model']      = $this->language->get('text_model');
		$this->data['text_price']      = $this->language->get('text_price');
		$this->data['text_tax']        = $this->language->get('text_tax');
		$this->data['text_points']     = $this->language->get('text_points');
		$this->data['text_display']    = $this->language->get('text_display');
		$this->data['text_list']       = $this->language->get('text_list');
		$this->data['text_grid']       = $this->language->get('text_grid');
		$this->data['text_sort']       = $this->language->get('text_sort');
		$this->data['text_limit']      = $this->language->get('text_limit');
		$this->data['text_comments']   = $this->language->get('text_comments');
		$this->data['text_viewed']     = $this->language->get('text_viewed');
		$this->data['button_cart']     = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['text_author']     = $this->language->get('text_author');

		$this->data['text_limit'] = $this->language->get('text_limit');
		$this->data['text_sort'] = $this->language->get('text_sort');


		$this->load->model('catalog/blog');
		$this->load->model('catalog/record');
		$this->load->model('tool/image');
		$this->load->model('setting/setting');

		if (!isset($this->data['settings_general']['colorbox_theme'])) {
			$this->data['settings_general']['colorbox_theme'] = 0;
		}
   		$this->data['config_template'] = $this->config->get('config_template');
        $this->cont('module/blog');
		$this->data = $this->controller_module_blog->ColorboxLoader($this->data['settings_general']['colorbox_theme'], $this->data);
  		/*
		if (!class_exists('User')) {
			require_once(DIR_SYSTEM . 'library/user.php');
			$this->registry->set('user', new User($this->registry));
		}
		*/

		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/blog.blog.js')) {
			$this->document->addScript('catalog/view/javascript/blog/blog.blog.js');
		}


		$this->load->library('user');
		$this->user = new User($this->registry);

		if ($this->user->isLogged()
		//&& $this->user->hasPermission('modify', 'record/blog')
		) {
			$this->data['userLogged'] = true;
			$this->data['token'] = $this->session->data['token'];
		} else {
			$this->data['userLogged'] = false;
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$settings_admin = $this->model_setting_setting->getSetting('ascp_admin', 'ascp_admin_https_admin_path');
		} else {
			$settings_admin = $this->model_setting_setting->getSetting('ascp_admin', 'ascp_admin_http_admin_path');
		}
		foreach ($settings_admin as $key => $value) {
			$this->data['admin_path'] = $value;
		}

		$sort_data = array(
			'rating',
			'comments',
			'popular',
			'latest',
			'sort'
		);
		$sort      = 'p.sort_order';
		if (isset($this->data['settings_general']['order']) && in_array($this->data['settings_general']['order'], $sort_data)) {
			if ($this->data['settings_general']['order'] == 'rating') {
				$sort = 'rating';
			} //$this->data['settings_general']['order'] == 'rating'
			if ($this->data['settings_general']['order'] == 'comments') {
				$sort = 'comments';
			} //$this->data['settings_general']['order'] == 'comments'
			if ($this->data['settings_general']['order'] == 'latest') {
				$sort = 'p.date_available';
			} //$this->data['settings_general']['order'] == 'latest'
			if ($this->data['settings_general']['order'] == 'sort') {
				$sort = 'p.sort_order';
			} //$this->data['settings_general']['order'] == 'sort'
			if ($this->data['settings_general']['order'] == 'popular') {
				$sort = 'p.viewed';
			} //$this->data['settings_general']['order'] == 'popular'
		} //isset($this->data['settings_general']['order']) && in_array($this->data['settings_general']['order'], $sort_data)
		$order = 'DESC';
		if (isset($this->data['settings_general']['order_ad'])) {
			if (strtoupper($this->data['settings_general']['order_ad']) == 'ASC') {
				$order = 'ASC';
			} //strtoupper($this->data['settings_general']['order_ad']) == 'ASC'
			if (strtoupper($this->data['settings_general']['order']) == 'DESC') {
				$order = 'DESC';
			} //strtoupper($this->data['settings_general']['order']) == 'DESC'
		} //isset($this->data['settings_general']['order_ad'])
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} //isset($this->request->get['sort'])
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} //isset($this->request->get['order'])
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		}  else {
			$page = 1;
		}

		if (SCP_VERSION > 1) {
			$config_catalog_limit = 'config_product_limit';
		} else {
            $config_catalog_limit = 'config_catalog_limit';
        }

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			if ($this->data['settings_general']['blog_num_records'] != '') {
				$limit = $this->data['settings_general']['blog_num_records'];
			}	else {
				$limit = $this->config->get($config_catalog_limit);
				$this->config->set('blog_num_records', $limit);
			}
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);
		if (isset($this->request->get['blog_id'])) {
			$path  = '';
			$parts = explode('_', (string) $this->request->get['blog_id']);
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} //!$path
				else {
					$path .= '_' . $path_id;
				}
				$blog_info = $this->model_catalog_blog->getBlog($path_id);
				if ($blog_info) {
					$this->data['breadcrumbs'][] = array(
						'text' => $blog_info['name'],
						'href' => $this->url->link('record/blog', 'blog_id=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				} //$blog_info
			} //$parts as $path_id
			$blog_id = array_pop($parts);
		} //isset($this->request->get['blog_id'])
		else {
			$blog_id = 0;
		}

		$blog_info = $this->model_catalog_blog->getBlog($blog_id);
		if ($blog_info) {
            // from seoblog
            $blog_page = $this->config->get('blog_page');
            if ($blog_page) {
 	            $paging = " ".$this->language->get('text_blog_page')." ".$blog_page;
            } else  {
           		$paging ='';
            }

			if (isset($blog_info['meta_title']) && $blog_info['meta_title']!='') {
             	$this->document->setTitle($blog_info['meta_title'].$paging);
			} else {
			  	$this->document->setTitle($blog_info['name'].$paging." - ".$this->config->get('config_title'));
			}

			if (isset($blog_info['meta_h1']) && $blog_info['meta_h1']!='') {
             	$this->data['heading_title']   = $blog_info['meta_h1'];
			} else {
			  	$this->data['heading_title']   = $blog_info['name'];
			}

            $this->data['name']   = $blog_info['name'];

			$this->document->setDescription($blog_info['meta_description'].$paging);
			$this->document->setKeywords($blog_info['meta_keyword']);

			$this->data['blog_href']       = $this->url->link('record/blog', 'blog_id=' . $blog_id);


			if ($blog_info['design'] != '') {
				$this->data['blog_design'] = unserialize($blog_info['design']);
			} else {
				$this->data['blog_design'] = Array();
			}
            $this->registry->set('blog_design', $this->data['blog_design']);

			if (isset($this->data['blog_design']['order']) && in_array($this->data['blog_design']['order'], $sort_data)) {
				if ($this->data['blog_design']['order'] == 'rating') {
					$sort = 'rating';
				} //$this->data['blog_design']['order'] == 'rating'
				if ($this->data['blog_design']['order'] == 'comments') {
					$sort = 'comments';
				} //$this->data['blog_design']['order'] == 'comments'
				if ($this->data['blog_design']['order'] == 'latest') {
					$sort = 'p.date_available';
				} //$this->data['blog_design']['order'] == 'latest'
				if ($this->data['blog_design']['order'] == 'sort') {
					$sort = 'p.sort_order';
				} //$this->data['blog_design']['order'] == 'sort'
				if ($this->data['blog_design']['order'] == 'popular') {
					$sort = 'p.viewed';
				} //$this->data['blog_design']['order'] == 'popular'
			} //isset($this->data['blog_design']['order']) && in_array($this->data['blog_design']['order'], $sort_data)
			if (isset($this->data['blog_design']['order_ad'])) {
				if (strtoupper($this->data['blog_design']['order_ad']) == 'ASC') {
					$order = 'ASC';
				} //strtoupper($this->data['blog_design']['order_ad']) == 'ASC'
				if (strtoupper($this->data['blog_design']['order']) == 'DESC') {
					$order = 'DESC';
				} //strtoupper($this->data['blog_design']['order']) == 'DESC'
			} //isset($this->data['blog_design']['order_ad'])

			if ($blog_info['image']) {
				if (isset($this->data['blog_design']['blog_big']) && $this->data['blog_design']['blog_big']['width'] != '' && $this->data['blog_design']['blog_big']['height'] != '') {
					$dimensions = $this->data['blog_design']['blog_big'];
				} //isset($this->data['blog_design']['blog_big']) && $this->data['blog_design']['blog_big']['width'] != '' && $this->data['blog_design']['blog_big']['height'] != ''
				else {
					$dimensions =  $this->data['settings_general']['blog_big'];
				}

				if (!isset($dimensions['width']) || $dimensions['width'] == '')
					$dimensions['width'] = 300;
				if (!isset($dimensions['height']) || $dimensions['height'] == '')
					$dimensions['height'] = 200;

				$this->data['thumb']     = $this->model_tool_image->resize($blog_info['image'], $dimensions['width'], $dimensions['height']);
				$this->data['popup']     = getHttpImage($this).$blog_info['image'];
				$this->data['thumb_dim'] = $dimensions;
			} //$blog_info['image']
			else {
				$this->data['popup']     = '';
				$this->data['thumb']     = '';
				$this->data['thumb_dim'] = false;
			}


			if ($blog_info['description']) {
				$this->data['description'] = html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8');
			} //$blog_info['description']
			else
				$this->data['description'] = false;
			if (isset($blog_info['sdescription']) && $blog_info['sdescription'] != '') {
				$this->data['sdescription'] = html_entity_decode($blog_info['sdescription'], ENT_QUOTES, 'UTF-8');
			} else
				$this->data['sdescription'] = false;


 			$this->load->library('document');
	        if (method_exists($this->document,'setOgImage') && $this->data['thumb']!='') {
	        	$this->document->setOgImage($this->data['thumb']);
	        }
	        if (method_exists($this->document,'setOgTitle')) {
	        	$this->document->setOgTitle($this->document->getTitle());
	        }
	        if (method_exists($this->document,'setOgDescription')) {
	        	$this->document->setOgDescription($this->document->getDescription());
	        }
            if (method_exists($this->document,'setOgUrl')) {
	        	$this->document->setOgUrl($this->data['blog_href']);
	        }




			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
				$sort = $this->request->get['sort'];
			} //isset($this->request->get['sort'])
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
				$order = $this->request->get['order'];
			} //isset($this->request->get['order'])
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			} //isset($this->request->get['limit'])
			$this->data['categories'] = array();

			$image                   = '';
			$this->data['image_dim'] = false;

			$results                  = $this->model_catalog_blog->getBlogies($blog_id);
			foreach ($results as $result) {
				$data         = array(
					'filter_blog_id' => $result['blog_id'],
					'filter_sub_blog' => true
				);

				$record_total = $this->model_catalog_record->getTotalRecords($data);

				if ($result['image']) {
					if (isset($this->data['blog_design']['blog_subcategory']) && $this->data['blog_design']['blog_subcategory']['width'] != '' && $this->data['blog_design']['blog_subcategory']['height'] != '') {
						$dimensions = $this->data['blog_design']['blog_subcategory'];
					} else {
						if (isset($this->data['blog_design']['blog_small']) && $this->data['blog_design']['blog_small']['width'] != '' && $this->data['blog_design']['blog_small']['height'] != '') {
							$dimensions = $this->data['blog_design']['blog_small'];
						} else {
							$dimensions = $this->data['settings_general']['blog_small'];
						}
					}

					if (!isset($dimensions['width']) || $dimensions['width'] == '') {
						if ($this->config->get('config_image_category_width') != '')
							$dimensions['width'] = $this->config->get('config_image_category_width');
						else
							$dimensions['width'] = 100;
					}

					if (!isset($dimensions['height']) || $dimensions['height'] == '') {
						if ($this->config->get('config_image_category_height') != '')
							$dimensions['height'] = $this->config->get('config_image_category_height');
						else
							$dimensions['height'] = 100;
					}

					$image                   = $this->model_tool_image->resize($result['image'], $dimensions['width'], $dimensions['height']);
					$this->data['image_dim'] = $dimensions;
				} else {
					$image                   = '';
					$this->data['image_dim'] = false;
				}
				$this->data['categories'][] = array(
					'name' => $result['name'],
					'meta_description' => $result['meta_description'],
					'total' => $record_total,
					'thumb' => $image,
					'popup'=>  getHttpImage($this).$result['image'],
					'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '_' . $result['blog_id'] . $url)
				);
			}

			if (isset($this->data['blog_design']['blog_num_records']) && $this->data['blog_design']['blog_num_records'] != '' && !isset($this->request->get['limit'])) {
				$limit = $this->data['blog_design']['blog_num_records'];
			} //isset($this->data['blog_design']['blog_num_records']) && $this->data['blog_design']['blog_num_records'] != '' && !isset($this->request->get['limit'])


			$this->data['records'] = array();


			if (isset($this->data['settings_general']['blog_search']) && (int)$this->data['settings_general']['blog_search'] == $blog_id) {
              	$filter_blog_id = false;

			} else {
				$filter_blog_id = $blog_id;
			}

            $url_search = '';
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
				$url_search .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			else {
				$filter_name = '';
			}
			if (isset($this->request->get['filter_tag'])) {
				$filter_tag = $this->request->get['filter_tag'];
				$url_search .= '&filter_tag=' . $this->request->get['filter_tag'];
			} //isset($this->request->get['filter_tag'])
			elseif (isset($this->request->get['filter_name'])) {
				$filter_tag = $this->request->get['filter_name'];
				$url_search .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			else {
				$filter_tag = '';
			}
			if (isset($this->request->get['filter_description'])) {
				$filter_description = $this->request->get['filter_description'];
				$url_search .= '&filter_description=' . $this->request->get['filter_description'];
			} //isset($this->request->get['filter_description'])
			else {
				$filter_description = '';
			}

			if (isset($this->request->get['filter_blog_id'])) {
				$filter_blog_id = $this->request->get['filter_blog_id'];
				$url_search .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			}

			if (isset($this->request->get['filter_sub_blog'])) {
				$filter_sub_blog = $this->request->get['filter_sub_blog'];
				$url_search .= '&filter_sub_blog=' . $this->request->get['filter_sub_blog'];
			} //isset($this->request->get['filter_sub_blog'])
			else {
				$filter_sub_blog = '';
			}

			if ($filter_sub_blog =='' &&  $filter_description == '' &&  $filter_tag == '' && $filter_name == '' && !isset($this->request->get['filter_blog_id'])) {
				$filter_blog_id = $blog_id;
			}


			$data = array(
				'filter_blog_id' => $filter_blog_id,
				'filter_name' => $filter_name,
				'filter_tag' => $filter_tag,
				'filter_description' => $filter_description,
				'filter_sub_blog' => $filter_sub_blog,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			);


			if (isset($this->data['blog_design'])) {
				$this->data['settings_blog'] = $this->data['blog_design'];
			}

			$record_total = $this->model_catalog_record->getTotalRecords($data);
			$results      = $this->model_catalog_record->getRecords($data);

			if (isset($this->data['settings_blog']['records_more']) && $this->data['settings_blog']['records_more']!='') {

				$more = ($record_total - ($page * $limit));
				if ($more > $limit) $more = $limit;

				if ((($page - 1) * $limit) + $limit < $record_total) {
					$this->data['entry_records_more'] = $this->language->get('entry_records_more').$more.$this->language->get('entry_records_more_end');


				} else {
					$this->data['entry_records_more'] = '';
				}
			}



			foreach ($results as $result) {
				if ($result['image']) {
					if (isset($this->data['blog_design']['blog_small']) && $this->data['blog_design']['blog_small']['width'] != '' && $this->data['blog_design']['blog_small']['height'] != '') {
						$dimensions = $this->data['blog_design']['blog_small'];
					} else {
						$dimensions =  $this->data['settings_general']['blog_small'];
					}

					if (!isset($this->data['blog_design']['images']))
					   $this->data['blog_design']['images'] =array();


				if (!isset($dimensions['width']) || $dimensions['width'] == '')
					$dimensions['width'] = 300;
				if (!isset($dimensions['height']) || $dimensions['height'] == '')
					$dimensions['height'] = 200;

					$image                   = $this->model_tool_image->resize($result['image'], $dimensions['width'], $dimensions['height']);
					$this->data['image_dim'] = $dimensions;
				} else {
					$image                   = false;
					$this->data['image_dim'] = false;
				}
				if ($this->config->get('config_comment_status')) {
					$rating = (int) $result['rating'];
				} else {
					$rating = false;
				}

			if ($result['description'] && isset($this->data['blog_design']['description_full']) && $this->data['blog_design']['description_full']) {

				$result['description_full'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
			} else {
				$result['description_full'] = false;
			}


				if (!isset($result['sdescription'])) {
					$result['sdescription'] = '';
				} //!isset($result['sdescription'])
				if ($result['description'] && $result['sdescription'] == '') {
					$flag_desc = 'pred';
					$amount    = 1;
					if (isset($this->data['blog_design']['blog_num_desc'])) {
						$this->data['blog_num_desc'] = $this->data['blog_design']['blog_num_desc'];
					} //isset($this->data['blog_design']['blog_num_desc'])
					else {
						$this->data['blog_num_desc'] = $this->data['settings_general']['blog_num_desc'];
					}
					if ($this->data['blog_num_desc'] == '') {
						$this->data['blog_num_desc'] = 50;
					} //$this->data['blog_num_desc'] == ''
					else {
						$amount    = $this->data['blog_num_desc'];
						$flag_desc = 'symbols';
					}
					if (isset($this->data['blog_design']['blog_num_desc_words'])) {
						$this->data['blog_num_desc_words'] = $this->data['blog_design']['blog_num_desc_words'];
					} //isset($this->data['blog_design']['blog_num_desc_words'])
					else {
						$this->data['blog_num_desc_words'] = $this->data['settings_general']['blog_num_desc_words'];
					}
					if ($this->data['blog_num_desc_words'] == '') {
						$this->data['blog_num_desc_words'] = 10;
					} //$this->data['blog_num_desc_words'] == ''
					else {
						$amount    = $this->data['blog_num_desc_words'];
						$flag_desc = 'words';
					}
					if (isset($this->data['blog_design']['blog_num_desc_pred'])) {
						$this->data['blog_num_desc_pred'] = $this->data['blog_design']['blog_num_desc_pred'];
					} //isset($this->data['blog_design']['blog_num_desc_pred'])
					else {
						$this->data['blog_num_desc_pred'] = $this->data['settings_general']['blog_num_desc_pred'];
					}
					if ($this->data['blog_num_desc_pred'] == '') {
						$this->data['blog_num_desc_pred'] = 3;
					} //$this->data['blog_num_desc_pred'] == ''
					else {
						$amount    = $this->data['blog_num_desc_pred'];
						$flag_desc = 'pred';
					}
					switch ($flag_desc) {
						case 'symbols':
							$pattern = ('/((.*?)\S){0,' . $amount . '}/isu');
							preg_match_all($pattern, strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), $out);
							$description = $out[0][0];
							break;
						case 'words':
							$pattern = ('/((.*?)\x20){0,' . $amount . '}/isu');
							preg_match_all($pattern, strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), $out);
							$description = $out[0][0];
							break;
						case 'pred':
							$pattern = ('/((.*?)\.){0,' . $amount . '}/isu');
							preg_match_all($pattern, strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), $out);
							$description = $out[0][0];
							break;
					} //$flag_desc
				}  else {
					$description = false;
				}
				if (isset($result['sdescription']) && $result['sdescription'] != '') {
					$description = html_entity_decode($result['sdescription'], ENT_QUOTES, 'UTF-8');
				} //isset($result['sdescription']) && $result['sdescription'] != ''

				unset($result['sdescription']);
				unset($result['description']);


					if (!isset($this->data['settings_general']['format_date'])) {
						$this->data['settings_general']['format_date'] = $this->language->get('text_date');
					}
					if (!isset($this->data['settings_general']['format_hours'])) {
						$this->data['settings_general']['format_hours'] = $this->language->get('text_hours');
					}

					if (isset($this->data['settings_general']['format_time']) && $this->data['settings_general']['format_time'] && date($this->data['settings_general']['format_date']) == date($this->data['settings_general']['format_date'], strtotime($result['date_available']))) {
						$date_str = $this->language->get('text_today');
					} else {
						$date_str = rdate($this, $this->data['settings_general']['format_date'], strtotime($result['date_available']));
					}
					$date_available = $date_str.(rdate($this,  $this->data['settings_general']['format_hours'], strtotime($result['date_available'])));


				$blog_href      = $this->model_catalog_blog->getPathByrecord($result['record_id']);
				$http_image 	= getHttpImage($this);
				$popup 			= $http_image . $result['image'];


				if (!isset($this->data['blog_design']['category_status'])) {
					$this->data['blog_design']['category_status'] = 0;
				} //!isset($this->data['blog_design']['category_status'])
				if (!isset($this->data['blog_design']['view_date'])) {
					$this->data['blog_design']['view_date'] = 1;
				} //!isset($this->data['blog_design']['view_date'])
				if (!isset($this->data['blog_design']['view_share'])) {
					$this->data['blog_design']['view_share'] = 1;
				} //!isset($this->data['blog_design']['view_share'])
				if (!isset($this->data['blog_design']['view_viewed'])) {
					$this->data['blog_design']['view_viewed'] = 1;
				} //!isset($this->data['blog_design']['view_viewed'])
				if (!isset($this->data['blog_design']['view_rating'])) {
					$this->data['blog_design']['view_rating'] = 1;
				} //!isset($this->data['blog_design']['view_rating'])
				if (!isset($this->data['blog_design']['view_comments'])) {
					$this->data['blog_design']['view_comments'] = 1;
				} //!isset($this->data['blog_design']['view_comments'])

                if (!isset($this->data['blog_design']['images'])) {
	                $this->data['blog_design']['images'] = array();
                }


				$this->data['records'][] = array(
					'record_id' => $result['record_id'],
					'thumb' => $image,
					'images' => $this->getRecordImages($result['record_id'], $this->data['blog_design']['images']),
					'popup' => $popup,
					'name' => $result['name'],
					'author' => $result['author'],
					'customer_id' => $result['customer_id'],
					'description' => $description,
					'description_full'=> $result['description_full'],
					'attribute_groups' => $this->model_catalog_record->getRecordAttributes($result['record_id']),
					'rating' => $result['rating'],
					'date_added' => $result['date_added'],
					'date_available' => $date_available,
					'datetime_available' => $result['date_available'],
					'date_end' => $result['date_end'],
					'viewed' => $result['viewed'],
					'comments' => (int) $result['comments'],
					'href' => $this->url->link('record/record', 'record_id=' . $result['record_id']),
					'blog_href' => $this->url->link('record/blog', 'blog_id=' . $blog_href['path']),
					'blog_name' => $blog_href['name'],
					'settings' => $this->data['settings_general'],
					'settings_blog' => $this->data['blog_design'],
					'settings_comment' => unserialize($result['comment'])
				);
			}
			$url = '';
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'].$url_search;
			}
			$this->data['sorts']   = array();
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.sort_order&order=ASC' . $url.$url_search)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_date_added_desc'),
				'value' => 'p.date_available-DESC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.date_available&order=DESC' . $url.$url_search)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_date_added_asc'),
				'value' => 'p.date_available-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=p.date_available&order=ASC' . $url.$url_search)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=pd.name&order=ASC' . $url.$url_search)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=pd.name&order=DESC' . $url.$url_search)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=rating&order=DESC' . $url.$url_search)
			);
			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&sort=rating&order=ASC' . $url.$url_search)
			);
			$url                   = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'].$url_search;
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'].$url_search;
			}

			$this->data['limits']   = array();
			$this->data['limits'][] = array(
				'text' => $limit,
				'value' => $limit,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url.$url_search . '&limit=' . $limit)
			);
			$this->data['limits'][] = array(
				'text' => 25,
				'value' => 25,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url .$url_search. '&limit=25')
			);
			$this->data['limits'][] = array(
				'text' => 50,
				'value' => 50,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url.$url_search . '&limit=50')
			);
			$this->data['limits'][] = array(
				'text' => 75,
				'value' => 75,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url.$url_search . '&limit=75')
			);
			$this->data['limits'][] = array(
				'text' => 100,
				'value' => 100,
				'href' => $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url.$url_search . '&limit=100')
			);

			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'].$url_search;
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'].$url_search;
			}
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'].$url_search;
			}

			$this->data['sort']       = $sort;
			$this->data['order']      = $order;
			$this->data['limit']      = $limit;
			$this->data['continue']   = $this->url->link('common/home');

			$pagination               = new Pagination();
			$pagination->total        = $record_total;
			$pagination->page         = $page;
			$pagination->limit        = $limit;
			$pagination->text         = $this->language->get('text_pagination');
			$pagination->url          = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . $url.$url_search . '&page={page}');
			$this->data['pagination'] = $pagination->render();


			if (isset($this->data['blog_design']['blog_template']) && $this->data['blog_design']['blog_template'] != '') {
				$template = $this->data['blog_design']['blog_template'];
			}  else {
				$template = 'blog.tpl';
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agootemplates/blog/' . $template)) {
				$this_template = $this->config->get('config_template') . '/template/agootemplates/blog/' . $template;
			}  else {
				if (file_exists(DIR_TEMPLATE . 'default/template/agootemplates/blog/' . $template)) {
					$this_template = 'default/template/agootemplates/blog/' . $template;
				}  else {
					$this_template = 'default/template/agootemplates/blog/blog.tpl';
				}
			}
			$this->children        = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			if (isset($this->data['blog_design']['further'][$this->config->get('config_language_id')]) && $this->data['blog_design']['further'][$this->config->get('config_language_id')]!='' ) {
             $this->data['settings_general']['further'][$this->config->get('config_language_id')] = $this->data['blog_design']['further'][$this->config->get('config_language_id')];
			}

			if (isset($this->data['settings_general']['box_share_list']) && $this->data['settings_general']['box_share_list']!='') {
			 	$this->data['box_share_list'] = html_entity_decode($this->data['settings_general']['box_share_list']);
			} else {
			 	$this->data['box_share_list'] = '';
			}
            $this->data['language'] = $this->language;

	        if (SCP_VERSION > 1) {
				$this->data['column_left'] = $this->load->controller('common/column_left');
				$this->data['column_right'] = $this->load->controller('common/column_right');
				$this->data['content_top'] = $this->load->controller('common/content_top');
				$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
				$this->data['footer'] = $this->load->controller('common/footer');
				$this->data['header'] = $this->load->controller('common/header');
			}

			$this->data['url_rss'] = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id'] . '&rss=2.0');
			if (isset($this->request->get['rss'])) {
				$this->response->addHeader("Content-type: text/xml");
				$this->data['url_self'] = $this->url->link('record/blog', 'blog_id=' . $this->request->get['blog_id']);
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agootemplates/blog/blogrss.tpl')) {
					$this_template = $this->config->get('config_template') . '/template/agootemplates/blog/blogrss.tpl';
				} else {
					if (file_exists(DIR_TEMPLATE . 'default/template/agootemplates/blog/blogrss.tpl')) {
						$this_template = 'default/template/agootemplates/blog/blogrss.tpl';
					} else {
						$this_template = '';
					}
				}
				$this->children             = array();
				$this->data['header']       = '';
				$this->data['column_left']  = '';
				$this->data['column_right'] = '';
				$this->data['content_top']  = '';
				$this->data['footer']       = '';
				$this->data['lang']         = $this->config->get('config_language');
                $this->data['config_name']	= $this->config->get('config_name');
                $this->data['config_meta_description']	= $this->config->get('config_meta_description');
			}

			$this->data['theme'] = $this->config->get('config_template');
            $this->config->set("blog_work", false);
            $this->data['config_language_id'] = $this->config->get('config_language_id');



       	$image_rss = '/image/rss24.png';
		if (file_exists(DIR_TEMPLATE . $this->data['theme'] . $image_rss)) {
			$this->data['image_rss'] = 'catalog/view/theme/'.$this->data['theme'] . $image_rss;
		} else {
			$this->data['image_rss'] = 'catalog/view/theme/default' . $image_rss;
		}

        $this->data['theme_stars'] = $this->getThemeStars('image/blogstars-1.png');

        $this->template = $this_template;

        if (SCP_VERSION < 2) {
			$html = $this->render();
		} else {
 			$html = $this->load->view($this->template , $this->data);
		}

	    	return $html;

		} else {
			$url = '';
			if (isset($this->request->get['blog_id'])) {
				$url .= '&blog_id=' . $this->request->get['blog_id'];
			} //isset($this->request->get['blog_id'])
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			} //isset($this->request->get['sort'])
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			} //isset($this->request->get['order'])
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			} //isset($this->request->get['page'])
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			} //isset($this->request->get['limit'])
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('record/blog', $url),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->setTitle($this->language->get('text_error'));
			$this->data['heading_title']   	= $this->language->get('text_error');
			$this->data['text_error']      	= $this->language->get('text_error');
			$this->data['button_continue'] 	= $this->language->get('button_continue');
			$this->data['continue']        	= $this->url->link('common/home');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this_template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			}  else {
				$this_template = 'default/template/error/not_found.tpl';
			}
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			if (!isset($this->request->get['ajax_file'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
			}
	  		 if (SCP_VERSION >1) {
				$this->data['column_left'] = $this->load->controller('common/column_left');
				$this->data['column_right'] = $this->load->controller('common/column_right');
				$this->data['content_top'] = $this->load->controller('common/content_top');
				$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
				$this->data['footer'] = $this->load->controller('common/footer');
				$this->data['header'] = $this->load->controller('common/header');
			}
            $this->config->set("blog_work", false);
            $this->template = $this_template;
			if (!isset($this->request->get['ajax_file'])) {
		        if (SCP_VERSION < 2) {
					$html_record = $this->render();
				} else {
		 			$html_record = $this->load->view($this->template , $this->data);
				}
				return $html_record;
			}
		}
	}


	private function getRecordImages($record_id, $settings) {
		$images = array();

		if (!isset($settings['width']) || $settings['width']=='' )    $settings['width']=$this->config->get('config_image_additional_width');;
		if (!isset($settings['height']) || $settings['height']=='' )  $settings['height']=$this->config->get('config_image_additional_height');

		$width = $settings['width'];
		$height = $settings['height'];

		$results              = $this->model_catalog_record->getRecordImages($record_id);
		foreach ($results as $res) {

		    $image_options = unserialize(base64_decode($res['options']));

		    if (isset($image_options['title'][$this->config->get('config_language_id')])) {
		    $image_title = html_entity_decode($image_options['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		    } else {
		     $image_title = getHttpImage($this) . $res['image'];
		    }

		    if (isset($image_options['description'][$this->config->get('config_language_id')])) {
		    	$image_description = $description = html_entity_decode($image_options['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'); ;
		    } else {
		     	$image_description ="";
		    }

		    if (isset($image_options['url'])) {
			    $image_url = $image_options['url'];
		    } else {
			    $image_url = "";
		    }

			$images[] = array(
				'popup' => getHttpImage($this) . $res['image'],
				'title' => $image_title,
				'description' => $image_description,
				'url'=> $image_url,
				'options' => $image_options,
				'thumb' => $this->model_tool_image->resize($res['image'], $width , $height)
			);
		}
		return $images;
	}


    public function getThemeStars($file) {
     	$themefile = false;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/'.$file)) {
			$themefile = $this->config->get('config_template');
		} else {
			if (file_exists(DIR_TEMPLATE . 'default/'.$file)) {
				$themefile = 'default';
			}
		}
      	return $themefile;
    }

	public function cont($cont)
	{
		$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load controller ' . $cont . '!');
			exit();
		}
	}

}
require_once(DIR_SYSTEM . 'helper/seocmsprofunc.php');