<?php
class ControllerRecordRecord extends Controller
{
	private $error = array();
	private $code;
	protected  $data;
	public function index()
	{
		$ver = VERSION;
		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);

		$this->config->set("blog_work", true);

		$this->load->model('setting/setting');
		$this->data['blog_version'] = '';
		$this->data['blog_version_model'] = '';
		$settings_admin = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) { $this->data['blog_version'] = $value; }
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) { $this->data['blog_version_model'] = $value; }
        $this->data['blog_version'] = $this->data['blog_version']. ' '. $this->data['blog_version_model'];

		$this->data['config_template'] = $this->config->get('config_template');

		if (file_exists(DIR_APPLICATION . 'view/javascript/jquery/tabs.js')) {
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		} else {

			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/tabs/tabs.js')) {
				$this->document->addScript('catalog/view/javascript/blog/tabs/tabs.js');
			}
		}
		$this->document->addScript('catalog/view/javascript/blog/blog.comment.js');
        require_once(DIR_SYSTEM . 'helper/utf8blog.php');



		if ((isset($this->request->get['ajax']) && $this->request->get['ajax']==2) || (isset($this->request->post['ajax']) && $this->request->post['ajax']==1)) {
  			$this->data['ajax'] = true;
		} else {
			$this->data['ajax'] = false;
		}



		$this->language->load('record/record');
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);
		if ($this->config->get('ascp_settings') != '') {
			$this->data['settings_general'] = $this->config->get('ascp_settings');
		}  else {
			$this->data['settings_general'] = Array();
		}


		if (!isset($this->data['settings_general']['colorbox_theme'])) {
			$this->data['settings_general']['colorbox_theme'] = 0;
		}
        $this->cont('module/blog');
		$this->data = $this->controller_module_blog->ColorboxLoader($this->data['settings_general']['colorbox_theme'], $this->data);
        $http_image = getHttpImage($this);

		$this->load->model('catalog/blog');
		if (isset($this->request->get['record_id'])) {
			$record_id                     = $this->request->get['record_id'];
			$blog_info                     = $this->model_catalog_blog->getPathByrecord($record_id);
			$this->request->get['blog_id'] = $blog_info['path'];
		}  else {
			$record_id = false;
		}
		if (isset($blog_info['path'])) {
			$path = '';
			foreach (explode('_', $blog_info['path']) as $path_id) {
				$blog_id = $path_id;
			}
		} else {
			$blog_id = 0;
		}

		if (isset($this->request->get['blog_id'])) {
			$path = '';
			foreach (explode('_', $this->request->get['blog_id']) as $path_id) {
				if (!$path) {
					$path = $path_id;
				} //!$path
				else {
					$path .= '_' . $path_id;
				}
				$path_id . "->" . $this->request->get['blog_id'];
				$blog_info = $this->model_catalog_blog->getBlog($path_id);
				if ($blog_info) {
					$this->data['breadcrumbs'][] = array(
						'text' => $blog_info['name'],
						'href' => $this->url->link('record/blog', 'blog_id=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				} //$blog_info
			} //explode('_', $this->request->get['blog_id']) as $path_id
		} else {
			$path = '';
		}
		$sort_data = array(
			'rating',
			'comments',
			'popular',
			'latest',
			'sort'
		);
		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$this->data['blog_design'] = unserialize($blog_info['design']);
		}  else {
			$this->data['blog_design'] = Array();
		}
		$sort = 'p.sort_order';
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
		$order = 'DESC';
		if (isset($this->data['blog_design']['order_ad'])) {
			if (strtoupper($this->data['blog_design']['order_ad']) == 'ASC') {
				$order = 'ASC';
			} //strtoupper($this->data['blog_design']['order_ad']) == 'ASC'
			if (strtoupper($this->data['blog_design']['order']) == 'DESC') {
				$order = 'DESC';
			} //strtoupper($this->data['blog_design']['order']) == 'DESC'
		} //isset($this->data['blog_design']['order_ad'])
		$this->load->model('catalog/record');
		$data                = array(
			'filter_blog_id' => $blog_id,
			'sort' => $sort,
			'order' => $order
		);
		$result_blog_records = $this->model_catalog_record->getRecords($data);
		if ($result_blog_records) {
			$previousKey                           = false;
			$nextKey                               = false;
			$next_flag                             = false;
			$this->data['record_previous']['url']  = '';
			$this->data['record_previous']['name'] = '';
			$this->data['record_next']['url']      = '';
			$this->data['record_next']['name']     = '';
			foreach ($result_blog_records as $num => $rec) {
				if ($next_flag) {
					$this->data['record_next']['url']  = $this->url->link('record/record', '&record_id=' . $result_blog_records[$num]['record_id']);
					$this->data['record_next']['name'] = $result_blog_records[$num]['name'];
					$next_flag                         = false;
				} //$next_flag
				if ($rec['record_id'] == $record_id) {
					$next_flag = true;
					if ($previousKey) {
						if (isset($result_blog_records[$previousKey])) {
							$this->data['record_previous']['url']  = $this->url->link('record/record', '&record_id=' . $result_blog_records[$previousKey]['record_id']);
							$this->data['record_previous']['name'] = $result_blog_records[$previousKey]['name'];
						} //isset($result_blog_records[$previousKey])
					} //$previousKey
				} //$rec['record_id'] == $record_id
				$previousKey = $num;
			} //$result_blog_records as $num => $rec
		} //$result_blog_records
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			} //isset($this->request->get['filter_tag'])
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			} //isset($this->request->get['filter_description'])
			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			} //isset($this->request->get['filter_blog_id'])
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('record/blog', $url),
				'separator' => $this->language->get('text_separator')
			);
		} //isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])
		if (isset($this->request->get['record_id'])) {
			$record_id = $this->request->get['record_id'];
		}  else {
			$record_id = 0;
		}
		$this->load->model('catalog/record');
		$record_info = $this->model_catalog_record->getRecord($record_id);
		if ($record_info) {
			$url = '';
			if (isset($this->request->get['blog_id'])) {
				$url .= '&blog_id=' . $this->request->get['blog_id'];
			} //isset($this->request->get['blog_id'])
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			} //isset($this->request->get['filter_name'])
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			} //isset($this->request->get['filter_tag'])
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			} //isset($this->request->get['filter_description'])
			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			} //isset($this->request->get['filter_blog_id'])
			$this->data['breadcrumbs'][] = array(
				'text' => $record_info['name'],
				'href' => $this->url->link('record/record', '&record_id=' . $this->request->get['record_id']),
				'separator' => $this->language->get('text_separator')
			);

			if (isset($record_info['meta_title']) && $record_info['meta_title']!='') {
             $this->document->setTitle($record_info['meta_title']);
			} else {
			  $this->document->setTitle($record_info['name']." - ".$this->config->get('config_title'));
			}

			if (isset($record_info['meta_h1']) && $record_info['meta_h1']!='') {
             $this->data['heading_title']   = $record_info['meta_h1'];
			} else {
			  $this->data['heading_title']   = $record_info['name'];
			}


            $this->data['name']   = $record_info['name'];

			$this->document->setDescription($record_info['meta_description']);
			$this->document->setKeywords($record_info['meta_keyword']);



			$this->load->model('catalog/comment');
			$this->data['text_welcome']         = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$this->data['text_select']          = $this->language->get('text_select');
			$this->data['text_or']              = $this->language->get('text_or');
			$this->data['text_write']           = $this->language->get('text_write');
			$this->data['text_note']            = $this->language->get('text_note');
			$this->data['text_share']           = $this->language->get('text_share');
			$this->data['text_wait']            = $this->language->get('text_wait');
			$this->data['text_tags']            = $this->language->get('text_tags');
			$this->data['text_viewed']          = $this->language->get('text_viewed');
			$this->data['entry_name']           = $this->language->get('entry_name');
			$this->data['entry_comment']        = $this->language->get('entry_comment');
			$this->data['entry_rating']         = $this->language->get('entry_rating');
			$this->data['entry_good']           = $this->language->get('entry_good');
			$this->data['entry_bad']            = $this->language->get('entry_bad');
			$this->data['entry_captcha']        = $this->language->get('entry_captcha');
			$this->data['entry_captcha_title']  = $this->language->get('entry_captcha_title');
			$this->data['entry_captcha_update'] = $this->language->get('entry_captcha_update');
			$this->data['button_cart']          = $this->language->get('button_cart');
			$this->data['button_wishlist']      = $this->language->get('button_wishlist');
			$this->data['button_compare']       = $this->language->get('button_compare');
			$this->data['button_upload']        = $this->language->get('button_upload');
			$this->data['button_write']         = $this->language->get('button_write');
			$this->data['tab_description']      = $this->language->get('tab_description');
			$this->data['tab_attribute']        = $this->language->get('tab_attribute');
			$this->data['tab_advertising']      = $this->language->get('tab_advertising');
			$this->data['tab_comment']          = $this->language->get('tab_comment');
			$this->data['tab_images']           = $this->language->get('tab_images');
			$this->data['tab_related']          = $this->language->get('tab_related');
			$this->data['tab_product_related']  = $this->language->get('tab_product_related');
			$this->data['text_author']          = $this->language->get('text_author');

			$this->data['record_id']            = $this->request->get['record_id'];

            /*
			$this->data['model']                = $record_info['model'];
			$this->data['reward']               = $record_info['reward'];
			$this->data['points']               = $record_info['points'];
			$this->data['text_model']           = $this->language->get('text_model');
			$this->data['text_reward']          = $this->language->get('text_reward');
			$this->data['text_points']          = $this->language->get('text_points');
			$this->data['text_stock']           = $this->language->get('text_stock');
			$this->data['text_price']           = $this->language->get('text_price');
			$this->data['text_tax']             = $this->language->get('text_tax');
			$this->data['text_option']          = $this->language->get('text_option');
			$this->data['text_qty']             = $this->language->get('text_qty');
            */
			$this->data['comment_count']        = $this->model_catalog_comment->getTotalCommentsByRecordId($this->request->get['record_id']);


			$this->load->model('tool/image');

			if ($record_info['image']) {
				$this->data['popup'] = $http_image . $record_info['image'];
			}  else {
				$this->data['popup'] = '';
			}


			if ($record_info['image']) {

				if (isset($this->data['blog_design']['thumb_image']['width']) && $this->data['blog_design']['thumb_image']['width']!='') {
				 $width = $this->data['blog_design']['thumb_image']['width'];
				} else {
				 $width = $this->config->get('config_image_thumb_width');
				}

				if (isset($this->data['blog_design']['thumb_image']['height']) && $this->data['blog_design']['thumb_image']['height']!='') {
				 $height = $this->data['blog_design']['thumb_image']['height'];
				} else {
				 $height = $this->config->get('config_image_thumb_height');
				}

				$this->data['thumb'] = $this->model_tool_image->resize($record_info['image'], $width, $height);
			}
			else {
				$this->data['thumb'] = '';
			}

			$this->data['href']           = $this->url->link('record/record', 'record_id=' . $this->request->get['record_id']);


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
	        	$this->document->setOgUrl($this->data['href']);
	        }




            $this->document->addLink($this->data['thumb'], 'image_src');

			$this->data['images'] = array();

			if (!isset($this->data['blog_design']['images'])) $this->data['blog_design']['images']=array();

			if (!isset($this->data['blog_design']['product_image'])) $this->data['blog_design']['product_image'] = $this->data['blog_design']['images'];
            if (!isset( $this->data['blog_design']['gallery_image']))  $this->data['blog_design']['gallery_image'] = $this->data['blog_design']['product_image'];
            $this->data['images'] = $this->getRecordImages($this->request->get['record_id'], $this->data['blog_design']['gallery_image']);

			 if (isset($this->data['settings_general']['box_share']) && $this->data['settings_general']['box_share']!='') {
			 	$this->data['box_share'] = html_entity_decode($this->data['settings_general']['box_share']);
			 } else {
			 	$this->data['box_share'] = '';
			 }



			$this->data['options'] = array();


			$this->data['text_comments']  = sprintf($this->language->get('text_comments'), (int) $record_info['comments']);
			$this->data['comments']       = (int) $record_info['comments'];
			$record_comment               = unserialize($record_info['comment']);
			$this->data['record_comment'] = $record_comment;
			$this->data['comment_status'] = $record_comment['status'];
			if ($this->customer->isLogged()) {
				$this->data['text_login']     = $this->customer->getFirstName() . " " . $this->customer->getLastName();
				$this->data['captcha_status'] = false;
				$this->data['customer_id']    = $this->customer->getId();
			} else {
				$this->data['text_login']     = $this->language->get('text_anonymus');
				$this->data['captcha_status'] = true;
				$this->data['customer_id']    = false;
				$this->data['signer_code']    = 'customer_id';
				$this->language->load('account/login');
				$this->data['text_new_customer']            = $this->language->get('text_new_customer');
				$this->data['text_register']                = $this->language->get('text_register');
				$this->data['text_register_account']        = $this->language->get('text_register_account');
				$this->data['text_returning_customer']      = $this->language->get('text_returning_customer');
				$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
				$this->data['text_forgotten']               = $this->language->get('text_forgotten');
				$this->data['entry_email']                  = $this->language->get('entry_email');
				$this->data['entry_password']               = $this->language->get('entry_password');
				$this->data['button_continue']              = $this->language->get('button_continue');
				$this->data['button_login']                 = $this->language->get('button_login');
				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
				}  else {
					$this->data['error_warning'] = '';
				}
				if (isset($this->session->data['success'])) {
					$this->data['success'] = $this->session->data['success'];
					unset($this->session->data['success']);
				} else {
					$this->data['success'] = '';
				}
				if (isset($this->request->post['email'])) {
					$this->data['email'] = $this->request->post['email'];
				} else {
					$this->data['email'] = '';
				}
				if (isset($this->request->post['password'])) {
					$this->data['password'] = $this->request->post['password'];
				}  else {
					$this->data['password'] = '';
				}
				$this->data['action']    = $this->url->link('account/login', '', 'SSL');
				$this->data['register']  = $this->url->link('account/register', '', 'SSL');
				$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
				if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
					$this->data['redirect'] = $this->request->post['redirect'];
				} elseif (isset($this->session->data['redirect'])) {
					$this->data['redirect'] = $this->session->data['redirect'];
					unset($this->session->data['redirect']);
				}  else {
					$this->data['redirect'] = $this->data['href'];
				}
			}

				$this->language->load('record/signer');

                $this->data['pointer'] = 'record_id';
				$this->load->model('agoo/signer/signer');


                if (isset($_COOKIE['email_subscribe_record_id']) && isset($this->data['pointer'])) {
                 $email_subscribe = unserialize(base64_decode($_COOKIE['email_subscribe_'.$this->data['pointer']]));

                 if (isset($email_subscribe[$this->data['record_id']])) {
                   $email_subscribe =  $email_subscribe[$this->data['record_id']];
                 } else {
                   $email_subscribe = '';
                 }

				} else {
                 $email_subscribe = false;
                }

				$this->data['signer_status'] = $this->model_agoo_signer_signer->getStatus($this->request->get['record_id'], $this->data['customer_id'], 'record_id', $email_subscribe);


			$this->data['viewed'] = $record_info['viewed'];
			if (!isset($record_info['date_available'])) {
			$this->data['date'] =	$record_info['date_available'] = $record_info['date_added'];
			} else {
			$this->data['date'] =  $record_info['date_available'];
			}


           $this->data['author'] = $record_info['author'];
           $this->data['author_customer_id'] = $record_info['customer_id'];


/*

			if (rdate($this,$this->language->get('text_date')) == rdate($this,$this->language->get('text_date'), strtotime($record_info['date_available']))) {
				$date_str = $this->language->get('text_today');
			} //rdate($this,$this->language->get('text_date')) == rdate($this,$this->language->get('text_date'), strtotime($record_info['date_available']))
			else {
				$date_str = rdate($this,$this->language->get('text_date'), strtotime($record_info['date_available']));
			}
			$date_available = $date_str.(rdate($this,  $this->language->get('text_hours'), strtotime($record_info['date_available'])));
*/


				if (isset($this->data['settings_general']['format_date'])) {

					} else {
						$this->data['settings_general']['format_date'] = $this->language->get('text_date');
					}

					if (isset($this->data['settings_general']['format_hours'])) {

					} else {
						$this->data['settings_general']['format_hours'] = $this->language->get('text_hours');
					}

					if (isset($this->data['settings_general']['format_time']) && $this->data['settings_general']['format_time'] && date($this->data['settings_general']['format_date']) == date($this->data['settings_general']['format_date'], strtotime($record_info['date_available']))) {
						$date_str = $this->language->get('text_today');
					} else {
						$date_str = rdate($this, $this->data['settings_general']['format_date'], strtotime($record_info['date_available']));
					}
					$date_available = $date_str.(rdate($this,  $this->data['settings_general']['format_hours'], strtotime($record_info['date_available'])));





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
			if (isset($this->data['blog_design']['view_captcha']) && !$this->data['blog_design']['view_captcha']) {
				$this->data['captcha_status'] = false;
			} //isset($this->data['blog_design']['view_captcha']) && !$this->data['blog_design']['view_captcha']

			$date_added                     = $date_available;
			$this->data['date_added']       = $date_added;
			$this->data['rating']           = (int) $record_info['rating'];
			$this->data['description']      = html_entity_decode($record_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_record->getRecordAttributes($this->request->get['record_id']);
			$this->data['products']         = array();
			$results                        = $this->model_catalog_record->getProductRelated($this->request->get['record_id'], 'product_id');



			foreach ($results as $result) {
				if ($result['image']) {

				if (isset($this->data['blog_design']['product_image']['width']) && $this->data['blog_design']['product_image']['width']!='') {
				 $width = $this->data['blog_design']['product_image']['width'];
				} else {
				 $this->data['blog_design']['product_image']['width'] = $width = $this->config->get('config_image_related_width');
				}

				if (isset($this->data['blog_design']['product_image']['height']) && $this->data['blog_design']['product_image']['height']!='') {
				 $height = $this->data['blog_design']['product_image']['height'];
				} else {
				 $this->data['blog_design']['product_image']['height'] = $height = $this->config->get('config_image_related_height');
				}
					$image = $this->model_tool_image->resize($result['image'], $width, $height);
				}  else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				if ((float) $result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				if ($this->config->get('config_review_status')) {
					$rating = (int) $result['rating'];
				}  else {
					$rating = false;
				}

                //for future
				if (isset($result['description_blog']) && $result['description_blog']) {
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

				if ($this->config->get('config_product_description_length')) {
				 $how = $this->config->get('config_product_description_length');
				} else {
				 $how = 100;
				}

				$description =  utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $how);
				unset($result['description']);


				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'description' => $description.'..',
					'price' => $price,
					'special' => $special,
					'rating' => $rating,
					// 'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
			$this->data['records'] = array();
			$results               = $this->model_catalog_record->getRecordRelated($this->request->get['record_id']);
			foreach ($results as $result) {
				if ($result['image']) {

				if (isset($this->data['blog_design']['product_image']['width']) && $this->data['blog_design']['product_image']['width']!='') {
				 $width = $this->data['blog_design']['product_image']['width'];
				} else {
				 $this->data['blog_design']['product_image']['width'] = $width = $this->config->get('config_image_related_width');
				}

				if (isset($this->data['blog_design']['product_image']['height']) && $this->data['blog_design']['product_image']['height']!='') {
				 $height = $this->data['blog_design']['product_image']['height'];
				} else {
				 $this->data['blog_design']['product_image']['height'] = $height = $this->config->get('config_image_related_height');
				}
					$image = $this->model_tool_image->resize($result['image'], $width, $height);
				}  else {
					$image = false;
				}

				if ($result['comment']) {
					$rating = (int) $result['rating'];
				}  else {
					$rating = false;
				}
				$record_comment_info     = unserialize($result['comment']);

				if ($this->config->get('config_product_description_length')) {
				 $how = $this->config->get('config_product_description_length');
				} else {
				 $how = 100;
				}

				$rdescription =  utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $how);
				unset($result['description']);

				$this->data['records'][] = array(
					'record_id' => $result['record_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'description' => $rdescription.'..',
					'author' => $result['author'],
					'customer_id' => $result['customer_id'],
					'viewed' => $result['viewed'],
					'rating' => $rating,
					'comment_status' => $record_comment_info['status'],
					'comments' => sprintf($this->language->get('text_comments'), (int) $result['comments']),
					'href' => $this->url->link('record/record', 'record_id=' . $result['record_id'])
				);
			}
			$this->data['tags'] = array();

	   		if (isset($this->data['settings_general']['blog_search']) && $this->data['settings_general']['blog_search']) {
             	$this->data['blog_search']['href'] = $this->data['settings_general']['blog_search'];
			} else {
				$this->data['blog_search']['href'] = false;
			}

			$results            = $this->model_catalog_record->getRecordTags($this->request->get['record_id']);
			foreach ($results as $result) {
				$this->data['tags'][] = array(
					'tag' => trim($result['tag']),
					'href' => $this->url->link('record/blog', 'blog_id='.$this->data['blog_search']['href'].'&filter_tag=' . $result['tag'])
				);
			}
			$this->model_catalog_record->updateViewed($this->request->get['record_id']);




			if (isset($this->data['blog_design']['blog_template_record']) && $this->data['blog_design']['blog_template_record'] != '') {
				$template = $this->data['blog_design']['blog_template_record'];
			}  else {
				$template = 'record.tpl';
			}


			if (isset($this->request->get['template_modal']) && $this->request->get['template_modal'] != '') {
				$template =	$this->request->get['template_modal'];
			}


			if (isset($this->request->get['cmswidget']) && $this->request->get['cmswidget'] != '') {
				$this->data['cmswidget'] = (int)$this->request->get['cmswidget'];

                $this->data['ascp_widgets']  = $this->config->get('ascp_widgets');
				$this->data['settings_widget'] = $this->data['ascp_widgets'][$this->data['cmswidget']];
			}


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agootemplates/record/' . $template)) {
				$this_template = $this->config->get('config_template') . '/template/agootemplates/record/' . $template;
			}  else {
				if (file_exists(DIR_TEMPLATE . 'default/template/agootemplates/record/' . $template)) {
					$this_template = 'default/template/agootemplates/record/' . $template;
				}  else {
					$this_template = 'default/template/agootemplates/record/record.tpl';
				}
			}

            $this->data['settings_blog']    = $this->data['blog_design'];

            $this->children      = array();


		if ($this->data['ajax'])
		{
  			$this->data['header'] = '';
			$this->data['column_left'] = '';
			$this->data['column_right'] = '';
			$this->data['content_top'] = '';
			$this->data['content_bottom'] = '';
			$this->data['footer'] = '';

  			if (isset($this->data['settings_widget']['positions']) && $this->data['settings_widget']['positions']!='') {
	  			foreach ($this->data['settings_widget']['positions'] as $num => $position) {
	              if (SCP_VERSION > 1) {
	              $this->data[$position] = $this->load->controller('common/'.$position);
	              } else {
	               array_push($this->children, 'common/'.$position );
	              }
	  			}
  			}



		} else {
		    if (SCP_VERSION < 2) {
				$this->children      = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			} else {
				$this->data['column_left'] = $this->load->controller('common/column_left');
				$this->data['column_right'] = $this->load->controller('common/column_right');
				$this->data['content_top'] = $this->load->controller('common/content_top');
				$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
				$this->data['footer'] = $this->load->controller('common/footer');
				$this->data['header'] = $this->load->controller('common/header');
			}

		}


			$this->data['language'] =  $this->language;
			$this->data['theme'] = $this->config->get('config_template');
			$this->config->set("blog_work", false);
        	$this->data['theme_stars'] = $this->getThemeStars('image/blogstars-1.png');
        	$this->template = $this_template;

	        if (SCP_VERSION < 2) {
				$html = $this->render();
			} else {
	 			$html = $this->load->view($this->template , $this->data);
			}

		return $html;
/*******************************************************************************************************************************************/
		}  else {
			$url = '';
			if (isset($this->request->get['blog_id'])) {
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			if (isset($this->request->get['filter_blog_id'])) {
				$url .= '&filter_blog_id=' . $this->request->get['filter_blog_id'];
			}
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('record/record', $url . '&record_id=' . $record_id),
				'separator' => $this->language->get('text_separator')
			);
			$this->document->setTitle($this->language->get('text_error'));
			$this->data['heading_title'] 	= $this->language->get('text_error');
			$this->data['text_error']    	= $this->language->get('text_error');
			$this->data['button_continue'] 	= $this->language->get('button_continue');
			$this->data['continue']      	= $this->url->link('common/home');
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
            $this->data['language'] =  $this->language;
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

					    if (isset($image_options['url'][$this->config->get('config_language_id')])) {
						    $image_url = $image_options['url'][$this->config->get('config_language_id')];
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


	private function getBlogSettingsbyRecordID($record_id)
	{
		$this->load->model('catalog/blog');
		if (isset($record_id)) {
			$blog_path = $this->model_catalog_blog->getPathByrecord($record_id);
			$blog_id   = $blog_path['path'];
		} //isset($record_id)
		if (isset($blog_id)) {
			foreach (explode('_', $blog_id) as $path_id) {
				$path_id = $path_id;
			} //explode('_', $blog_id) as $path_id
		} //isset($blog_id)
		$blog_info = $this->model_catalog_blog->getBlog($path_id);
		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$this->data['blog_design'] = unserialize($blog_info['design']);
		} else {
			$this->data['blog_design'] = Array();
		}
		return $this->data['blog_design'];
	}



	public function upload()
	{
		$this->language->load('record/record');
		$json = array();
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			} //(strlen($filename) < 3) || (strlen($filename) > 128)
			$allowed   = array();
			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			} //$filetypes as $filetype
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			} //!in_array(substr(strrchr($filename, '.'), 1), $allowed)
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			} //$this->request->files['file']['error'] != UPLOAD_ERR_OK
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(rand());
				$this->load->library('encryption');
				$encryption   = new Encryption($this->config->get('config_encryption'));
				$json['file'] = $encryption->encrypt($file);
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			} //is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])
			$json['success'] = $this->language->get('text_upload');
		} //($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])
		$this->response->setOutput(json_encode($json));
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
?>