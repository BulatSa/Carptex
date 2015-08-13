<?php
class ControllerCatalogAdapter extends Controller
{
	private $error = array();
	protected $data;
	private $module_files = Array(	'agootemplates/blog/adaptive.tpl',
									'agootemplates/blog/blog.tpl',
									'agootemplates/blog/blog_gallery.tpl',
									'agootemplates/blog/blog_null.tpl',
									'agootemplates/blog/isotope.tpl',
									'agootemplates/blog/search.tpl',
									'agootemplates/record/empty.tpl',
									'agootemplates/record/isotope.tpl',
									'agootemplates/record/product.tpl',
									'agootemplates/record/record.tpl');
	public function index()
	{
		$ver = VERSION;
		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
		$this->language->load('module/blog');
		$this->data['oc_version'] = str_pad(str_replace(".", "", VERSION), 7, "0");
		$this->load->model('setting/setting');
		$this->data['blog_version']       = '*';
		$this->data['blog_version_model'] = 'PRO';
		$settings_admin                   = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['blog_version'] = $value;
		}
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) {
			$this->data['blog_version_model'] = $value;
		}
		$this->data['blog_version'] = $this->data['blog_version'] . ' ' . $this->data['blog_version_model'];
		$this->language->load('catalog/adapter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['tab_general']      = $this->language->get('tab_general');
		$this->data['tab_list']         = $this->language->get('tab_list');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_modules']      = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_options']      = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_schemes']      = $this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_widgets']      = $this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back']         = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back_text']    = $this->language->get('url_back_text');
		$this->data['url_record']       = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record_text']  = $this->language->get('url_record_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog']         = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment']      = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog_text']    = $this->language->get('url_blog_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text']  = $this->language->get('url_create_text');
		$this->load->model('catalog/adapter');

        $this->cont('agooa/adminmenu');
        $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();

		$this->getList();
	}
	private function getList()
	{
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		$this->data['url_back']      = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back_text'] = $this->language->get('url_back_text');
		$url                         = '';
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		$this->data['insert']        = $this->url->link('catalog/adapter/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy']          = $this->url->link('catalog/adapter/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete']        = $this->url->link('catalog/adapter/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['adapter_list']  = $this->getThemes();
		$this->load->model('tool/image');
		$this->data['adapter'] = array();
		$this->load->model('setting/setting');
		$store_info                 = $this->model_setting_setting->getSetting('config', 0);
		$this->data['config_theme'] = $store_info['config_template'];
		foreach ($this->data['adapter_list'] as $id => $theme) {
			$action                 = array();
			$action[]               = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/adapter/update', 'token=' . $this->session->data['token'] . '&adapter_theme=' . $theme, 'SSL')
			);
			$action[]               = array(
				'text' => '.',
				'href' => $this->url->link('catalog/adapter/update', 'token=' . $this->session->data['token'] . '&adapter_theme=' . $theme . '&source=compare', 'SSL')
			);
			$result['adapter_id']   = $id;
			$result['adapter_name'] = $theme;
			if (file_exists(DIR_IMAGE . 'templates/' . $theme . '.png')) {
				$result['adapter_image'] = 'templates/' . $theme . '.png';
			} else {
				$result['adapter_image'] = 'no_image.jpg';
			}
			if (isset($result['adapter_image']) && $result['adapter_image'] && file_exists(DIR_IMAGE . $result['adapter_image'])) {
				$image = $this->model_tool_image->resize($result['adapter_image'], 70, 70);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 70, 70);
			}
			$this->data['adapter'][] = array(
				'adapter_id' => (isset($result['adapter_id']) ? $result['adapter_id'] : ''),
				'adapter_name' => (isset($result['adapter_name']) ? $result['adapter_name'] : ''),
				'adapter_image' => $image,
				'selected' => isset($this->request->post['selected']) && in_array($result['adapter_id'], $this->request->post['selected']),
				'action' => $action
			);
		}
		$this->data['heading_title']      = $this->language->get('heading_title');
		$this->data['text_enabled']       = $this->language->get('text_enabled');
		$this->data['text_disabled']      = $this->language->get('text_disabled');
		$this->data['text_no_results']    = $this->language->get('text_no_results');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['column_image']       = $this->language->get('column_image');
		$this->data['column_name']        = $this->language->get('column_name');
		$this->data['column_status']      = $this->language->get('column_status');
		$this->data['column_action']      = $this->language->get('column_action');
		$this->data['button_copy']        = $this->language->get('button_copy');
		$this->data['button_insert']      = $this->language->get('button_insert');
		$this->data['button_delete']      = $this->language->get('button_delete');
		$this->data['token']              = $this->session->data['token'];
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['this']     = $this;
		$this->template         = 'catalog/adapter_list.tpl';
		$this->children         = array(
			'common/header',
			'common/footer'
		);
		$this->data['registry'] = $this->registry;
		$this->data['language'] = $this->language;
		$this->data['config']   = $this->config;
		if (SCP_VERSION < 2) {
			$this->data['column_left'] = '';
			$html                      = $this->render();
		} else {
			$this->data['header']      = $this->load->controller('common/header');
			$this->data['menu']        = $this->load->controller('common/menu');
			$this->data['footer']      = $this->load->controller('common/footer');
			$this->data['column_left'] = $this->load->controller('common/column_left');
			$html                      = $this->load->view($this->template, $this->data);
		}
		$this->response->setOutput($html);
	}
	public function update()
	{
		$this->language->load('catalog/adapter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/adapter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if ($this->editTheme($this->request->get['adapter_theme'], $this->request->post)) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_warning');
			}
		}
		$this->getForm();
	}
	public function editTheme($adapter_theme, $post)
	{
		if (isset($post['success_file']) && $post['success_file'] != '')
			$success_data = html_entity_decode($post['success_file'], ENT_QUOTES, 'UTF-8');
		else
			$success_data = '';
		if (isset($post['replace_breadcrumb']) && $post['replace_breadcrumb'] != '')
			$replace_breadcrumb = $post['replace_breadcrumb'];
		else
			$replace_breadcrumb = false;
		if (isset($post['replace_breadcrumb_name']) && $post['replace_breadcrumb_name'] != '')
			$replace_breadcrumb_name = html_entity_decode($post['replace_breadcrumb_name'], ENT_QUOTES, 'UTF-8');
		else
			$replace_breadcrumb_name = '';
		if (isset($post['replace_main_name']) && $post['replace_main_name'] != '')
			$replace_main_name = html_entity_decode($post['replace_main_name'], ENT_QUOTES, 'UTF-8');
		else
			$replace_main_name = '';
		if (isset($post['selected_tag']) && is_array($post['selected_tag']))
			$selected_tag = $post['selected_tag'];
		else
			$selected_tag = array();
		if (isset($post['selected_id']) && is_array($post['selected_id']))
			$selected_id = $post['selected_id'];
		else
			$selected_id = array();
		if (isset($post['selected_class']) && is_array($post['selected_class']))
			$selected_class = $post['selected_class'];
		else
			$selected_class = array();
		$this->load->library('parser/simple_html_dom');
		$html = str_get_html($success_data, true, true, DEFAULT_TARGET_CHARSET, false);
		$html = str_get_html($html->outertext, true, true, DEFAULT_TARGET_CHARSET, false);
		foreach ($selected_tag as $tag) {
			$html = str_get_html($html->innertext, true, true, DEFAULT_TARGET_CHARSET, false);
			$b    = $html->find($tag);
			if ($b) {
				foreach ($b as $a) {
					$a->outertext = '';
				}
			}
		}
		foreach ($selected_id as $id) {
			$html = str_get_html($html->innertext, true, true, DEFAULT_TARGET_CHARSET, false);
			$b    = $html->find('#' . $id);
			if ($b) {
				foreach ($b as $a) {
					$a->outertext = '';
				}
			}
		}
		foreach ($selected_class as $class) {
			$k = 0;
			foreach ($class as $num => $classic) {
				$html = str_get_html($html->innertext, true, true, DEFAULT_TARGET_CHARSET, false);
				$b    = $html->find('.' . $classic, $num - $k);
				if ($b) {
					$b->outertext = '';
					$k++;
				}
			}
		}
		if ($replace_breadcrumb && $replace_breadcrumb_name != '') {
			$html = str_get_html($html->innertext, true, true, DEFAULT_TARGET_CHARSET, false);
			$b    = $html->find($replace_breadcrumb_name, 0);
			if ($b)
				$b->outertext = html_entity_decode('{BREADCRUMB}', ENT_QUOTES, 'UTF-8');
		}
		$html_new = $html->outertext;
		if ($replace_main_name && $replace_main_name != '') {
			$healthy  = array(
				$replace_main_name
			);
			$yummy    = array(
				"{CONTENT}"
			);
			$html_new = str_replace($healthy, $yummy, $html_new);
		}
		$this->data['html']  = $html_new;
		$this->data['theme'] = $adapter_theme;
		return $this->saveTheme($this->data);
	}
	private function saveTheme($data)
	{
		$this->data = $data;
		$this->load->library('parser/simple_html_dom');
		$save_flag = false;
		foreach ($this->module_files as $module_file) {
			$module_file_content = DIR_CATALOG . 'view/theme/default/template/' . $module_file;
			if (file_exists($module_file_content)) {
				$content                = file_get_contents($module_file_content);
				$html                   = str_get_html($content, true, true, DEFAULT_TARGET_CHARSET, false);
				$html                   = str_get_html($html->outertext, true, true, DEFAULT_TARGET_CHARSET, false);
				$breadcrumb             = $html->find('.breadcrumb', 0);
				$seocmspro_content      = $html->find('.seocmspro_content', 0);
				$this->data['html_new'] = $this->data['html'];
				if ($breadcrumb && $breadcrumb != '') {
					$healthy                = array(
						'{BREADCRUMB}'
					);
					$yummy                  = array(
						$breadcrumb
					);
					$this->data['html_new'] = str_replace($healthy, $yummy, $this->data['html_new']);
					$save_flag              = true;
				}
				if ($seocmspro_content && $seocmspro_content != '') {
					$healthy                = array(
						'{CONTENT}'
					);
					$yummy                  = array(
						$seocmspro_content
					);
					$this->data['html_new'] = str_replace($healthy, $yummy, $this->data['html_new']);
					$save_flag              = true;
				}
				if ($save_flag) {
					$path = DIR_CATALOG . 'view/theme/' . $this->data['theme'] . '/template/' . $module_file;
					if ($this->mkdirs($path)) {
						file_put_contents($path, $this->data['html_new']);
					} else {
						$save_flag = false;
					}
				}
				unset($html);
			}
		}
		return $save_flag;
	}
	private function formatHtml($html_new)
	{
		$healthy  = array(
			"<div",
			"/div>",
			"\t"
		);
		$yummy    = array(
			"\r\n<div",
			"/div>\r\n",
			""
		);
		$html_new = str_replace($healthy, $yummy, $html_new);
		$healthy  = array(
			"<div",
			"/div>",
			"\t"
		);
		$yummy    = array(
			"\r\n<div",
			"/div>\r\n",
			""
		);
		$html_new = str_replace($healthy, $yummy, $html_new);
		$html_new = preg_replace('/ {2,}/', ' ', $html_new);
		$html_new = preg_replace("/(\r\n){3,}/", "\r\n", $html_new);
		return $html_new;
	}
	private function getForm()
	{
		$ver = VERSION;
		if (!defined('SCP_VERSION'))
			define('SCP_VERSION', $ver[0]);
		$this->language->load('module/blog');
		$this->data['oc_version'] = str_pad(str_replace(".", "", VERSION), 7, "0");
		$this->load->model('setting/setting');
		$this->data['blog_version']       = '*';
		$this->data['blog_version_model'] = 'PRO';
		$settings_admin                   = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['blog_version'] = $value;
		}
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) {
			$this->data['blog_version_model'] = $value;
		}

        $this->cont('agooa/adminmenu');
        $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();

		$this->data['blog_version']     = $this->data['blog_version'] . ' ' . $this->data['blog_version_model'];
		$this->data['tab_general']      = $this->language->get('tab_general');
		$this->data['tab_list']         = $this->language->get('tab_list');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_modules']      = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_options']      = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_schemes']      = $this->url->link('module/blog/schemes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_widgets']      = $this->url->link('module/blog/widgets', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back']         = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back_text']    = $this->language->get('url_back_text');
		$this->data['url_record']       = $this->url->link('catalog/record', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_record_text']  = $this->language->get('url_record_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog']         = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment']      = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_blog_text']    = $this->language->get('url_blog_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text']  = $this->language->get('url_create_text');
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
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
		if (!file_exists(DIR_APPLICATION . 'view/javascript/ckeditor/ckeditor.js')) {
			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/ckeditor/ckeditor.js')) {
				$this->document->addScript('view/javascript/blog/ckeditor/ckeditor.js');
			}
		} else {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		}
		if (isset($this->request->get['adapter_theme'])) {
			$theme = $this->data['theme'] = $this->request->get['adapter_theme'];
		} else {
			$theme = $this->data['theme'] = '';
		}
		$this->data['config_language'] = $this->config->get('config_language');
		$this->data['no_image']        = '';
		$this->language->load('catalog/adapter');
		$this->data['heading_title']     = $this->language->get('heading_title');
		$this->data['text_enabled']      = $this->language->get('text_enabled');
		$this->data['text_disabled']     = $this->language->get('text_disabled');
		$this->data['text_none']         = $this->language->get('text_none');
		$this->data['text_yes']          = $this->language->get('text_yes');
		$this->data['text_no']           = $this->language->get('text_no');
		$this->data['text_select_all']   = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['url_back']          = $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_back_text']     = $this->language->get('url_back_text');
		$this->data['url_blog']          = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_adapter']       = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_adapter']       = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_comment']       = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_adapter_text']  = $this->language->get('url_adapter_text');
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$url                = '';
		$this->module_files = Array(
			'agootemplates/blog/adaptive.tpl',
			'agootemplates/blog/blog.tpl',
			'agootemplates/blog/blog_gallery.tpl',
			'agootemplates/blog/blog_null.tpl',
			'agootemplates/blog/isotope.tpl',
			'agootemplates/blog/search.tpl',
			'agootemplates/record/empty.tpl',
			'agootemplates/record/isotope.tpl',
			'agootemplates/record/product.tpl',
			'agootemplates/record/record.tpl'
		);
		if (isset($this->request->post['replace_breadcrumb'])) {
			$this->data['replace_breadcrumb'][$theme] = $this->request->post['replace_breadcrumb'];
		} else {
			$this->data['replace_breadcrumb'][$theme] = false;
		}
		if (isset($this->request->post['replace_breadcrumb_name'])) {
			$this->data['replace_breadcrumb_name'][$theme] = $this->request->post['replace_breadcrumb_name'];
		} else {
			$this->data['replace_breadcrumb_name'][$theme] = '.breadcrumb';
		}
		if (isset($this->request->post['replace_main_name'])) {
			$this->data['replace_main_name'][$theme] = $this->request->post['replace_main_name'];
		} else {
			$this->data['replace_main_name'][$theme] = '<?php echo $text_message; ?>';
		}
		$this->data['replace_main_name']['shoppica']        = '';
		$this->data['replace_main_name']['shoppica2']       = '';
		$this->data['replace_breadcrumb']['METROPOLITEN']   = true;
		$this->data['replace_breadcrumb']['default2']       = true;
		$this->data['replace_breadcrumb']['journal']        = true;
		$this->data['replace_breadcrumb']['journal2']       = true;
		$this->data['replace_breadcrumb']['juicyblue']      = true;
		$this->data['replace_breadcrumb']                   = $this->data['replace_breadcrumb'][$theme];
		$this->data['replace_breadcrumb_name']['oxy']       = '.breadcrumbs';
		$this->data['replace_breadcrumb_name']['vista']     = '.breadcrumbs';
		$this->data['replace_breadcrumb_name']['shoppica']  = '#breadcrumbs';
		$this->data['replace_breadcrumb_name']['shoppica2'] = '#breadcrumbs';
		$this->data['replace_breadcrumb_name']['kubera']    = '';
		$this->data['replace_breadcrumb_name']              = $this->data['replace_breadcrumb_name'][$theme];
		$this->data['file_theme'][$theme]                   = 'common/success.tpl';
		if (isset($this->request->get['source']) && $this->request->get['source'] != '')
			$this->data['file_theme'][$theme] = 'product/compare.tpl';
		$this->data['file_theme']['shoppica2']         = 'product/compare.tpl';
		$this->data['file_theme']['shoppica']          = 'product/compare.tpl';
		$this->data['file_theme']                      = $this->data['file_theme'][$theme];
		$this->data['replace_main_name']['moneymaker'] = '<p><?php echo $text_message; ?></p>';
		$this->data['replace_main_name']               = $this->data['replace_main_name'][$theme];
		if (file_exists(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $this->data['file_theme'])) {
			$success_file = DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $this->data['file_theme'];
			$success      = $this->data['success_data'] = file_get_contents($success_file);
		} else {
			$this->data['file_theme'] = 'product/compare.tpl';
			if (file_exists(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $this->data['file_theme'])) {
				$success_file = DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $this->data['file_theme'];
				$success      = $this->data['success_data'] = file_get_contents($success_file);
			} else {
				$success_file               = '';
				$this->data['success_data'] = '';
			}
		}
		if ($this->data['success_data'] != '') {
			$time_start = microtime(true);
			$this->load->library('parser/simple_html_dom');
			$html                                = str_get_html($success, true, true, DEFAULT_TARGET_CHARSET, false);
			$html                                = str_get_html($html->outertext, true, true, DEFAULT_TARGET_CHARSET, false);
			$this->data['success_id'][$theme]    = array();
			$this->data['success_class'][$theme] = array();
			$this->data['success_tag'][$theme]   = array(
				'script'
			);
			$this->data['success_id']            = $this->data['success_id'][$theme];
			$this->data['success_class']         = $this->data['success_class'][$theme];
			$this->data['success_tag']           = $this->data['success_tag'][$theme];
			foreach ($html->find(0) as $element) {
				if ($element->id != '')
					$this->data['success_id'][] = $element->id;
				if ($element->class != '') {
					$element->class = preg_replace('/<\?(.*)\?>/', ' ', $element->class);
					$element->class = preg_replace('/\s{2,}/', ' ', trim($element->class));
					$pos            = strpos($element->class, ' ');
					if ($pos === false) {
						$class_array = Array(
							$element->class
						);
					} else {
						$class_array = explode(" ", $element->class);
					}
					foreach ($class_array as $classic) {
						if ($classic != '')
							$this->data['success_class'][$classic][] = $classic;
					}
				}
				if ($element->tag != '' && $element->tag != 'root')
					$this->data['success_tag'][$element->tag] = $element->tag;
			}
			$this->data['remove_tag'][$theme]         = Array(
				'script'
			);
			$this->data['remove_class'][$theme]       = Array(
				'buttons'
			);
			$this->data['remove_id'][$theme]          = Array();
			$this->data['remove_class']['moneymaker'] = Array(
				'pagination_buttons'
			);
			$this->data['remove_class']['shoppica']   = Array(
				's_submit',
				'buttons'
			);
			$this->data['remove_class']['shoppica2']  = Array(
				's_submit',
				'buttons'
			);
			$new                                      = $html->outertext;
			$new                                      = preg_replace('/ {2,}/', ' ', $new);
			$new                                      = preg_replace('~(?:\r?\n){2,}~', "\n\r", $new);
			$healthy                                  = array(
				"<div",
				"/div>",
				"<?php echo \$text_message; ?>",
				"{BREADCRUMB}",
				"\t"
			);
			$yummy                                    = array(
				"\r\n<div",
				"/div>\r\n",
				"\r\n{CONTENT}\r\n",
				"\r\n{BREADCRUMB}\r\n",
				""
			);
			$newcontent                               = str_replace($healthy, $yummy, $new);
			$time_end                                 = microtime(true);
			$time                                     = $time_end - $time_start;
			$this->data['time']                       = sprintf("%05.5f sec.", $time);
		}
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		if (isset($this->request->get['adapter_theme'])) {
			$this->data['action'] = $this->url->link('catalog/adapter/update', 'token=' . $this->session->data['token'] . '&adapter_theme=' . $this->request->get['adapter_theme'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/adapter/update', 'token=' . $this->session->data['token'] . '&adapter_id=' . $this->request->get['adapter_id'] . $url, 'SSL');
		}
		$this->data['cancel'] = $this->url->link('catalog/adapter', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token']  = $this->session->data['token'];
		if (isset($this->request->get['adapter_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['this']      = $this;
		$this->template          = 'catalog/adapter_form.tpl';
		$this->children          = array(
			'common/header',
			'common/footer'
		);
		$this->data['registry']  = $this->registry;
		$this->data['language']  = $this->language;
		$this->data['config']    = $this->config;
		if (SCP_VERSION < 2) {
			$this->data['column_left'] = '';
			$html                      = $this->render();
		} else {
			$this->data['header']      = $this->load->controller('common/header');
			$this->data['menu']        = $this->load->controller('common/menu');
			$this->data['footer']      = $this->load->controller('common/footer');
			$this->data['column_left'] = $this->load->controller('common/column_left');
			$html                      = $this->load->view($this->template, $this->data);
		}
		$this->response->setOutput($html);
	}
	public function backup($data)
	{
		$this->data = $data;
		foreach ($this->module_files as $module_file) {
			$module_file_content = DIR_CATALOG . 'view/theme/' . $this->data['theme'] . '/template/' . $module_file;
			if (file_exists($module_file_content)) {
				$path    = DIR_CACHE . 'view/theme/' . $this->data['theme'] . '/template/' . $module_file;
				$content = file_get_contents($module_file_content);
				$this->makefullpathfile($path);
				file_put_contents($path, $content);
			}
		}
	}
	private function mkdirs($pathname, $mode = 0777, $index = FALSE)
	{
		$flag_save = false;
		$path_file = dirname($pathname);
		$name_file = basename($pathname);
		if (is_dir(dirname($path_file))) {
		} else {
			$this->mkdirs(dirname($pathname), $mode, $index);
		}
		if (is_dir($path_file)) {
			if (file_exists($path_file)) {
				$flag_save = true;
			}
		} else {
			umask(0);
			@mkdir($path_file, $mode);
			if (file_exists($path_file)) {
				$flag_save = true;
			}
			if ($index) {
				$accessFile = $path_file . "/" . $name_file;
				touch($accessFile);
				$accessWrite = fopen($accessFile, "wb");
				fwrite($accessWrite, 'access denied');
				fclose($accessWrite);
				if (file_exists($accessFile)) {
					$flag_save = true;
				} else {
					$flag_save = false;
				}
			}
		}
		return $flag_save;
	}
	public function makefullpathfile($path)
	{
		$path_file = dirname($path);
		$name_file = basename($path);
		$flag_save = false;
		$arr       = explode('/', $path_file);
		$curr      = array();
		foreach ($arr as $key => $val) {
			if (!empty($val)) {
				$curr[]  = $val;
				$mkdirka = implode('/', $curr) . "/";
				if (!file_exists($mkdirka)) {
					mkdir($mkdirka, 0777);
				}
			}
		}
		if (file_exists($path_file)) {
			file_put_contents($path, '');
			$flag_save = true;
		}
		return $flag_save;
	}
	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'catalog/adapter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$this->request->get['adapter_theme'] = preg_replace("/[^a-zA-Z0-9_\s\-]/", "", $this->request->get['adapter_theme']);
		if ($this->request->get['adapter_theme'] == '') {
			$this->error['warning'] = $this->language->get('error_name');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function insert()
	{
		$this->language->load('catalog/adapter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/adapter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (!$this->model_catalog_adapter->addField($this->request->post)) {
				$this->session->data['success'] = $this->language->get('text_success');
				$url                            = '';
				$this->redirect($this->url->link('catalog/adapter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			} else {
				$this->session->data['error_warning_form'] = $this->language->get('error_warning_form');
			}
		}
		$this->getForm();
	}
	private function getThemes()
	{
		$this->data['themes'] = array();
		$directories          = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['themes'][] = basename($directory);
		}
		return $this->data['themes'];
	}
	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'catalog/adapter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateCopy()
	{
		if (!$this->user->hasPermission('modify', 'catalog/adapter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function cont($cont)
	{
		$file  = DIR_CATALOG . 'controller/' . $cont . '.php';
		if (file_exists($file)) {
           $this->cont_loading($cont, $file);
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
}
?>