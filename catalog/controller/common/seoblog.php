<?php
if (!class_exists('ControllerCommonSeoBlog')) {
class ControllerCommonSeoBlog extends Controller
{
	protected $data;
	private $blog_design = Array();
	private $cache_data = null;
	private $langcode_all;
	private $languages_all;
	private $flag_language = false;
	private $comp_url = false;

	public function __construct($registry)
	{
		parent::__construct($registry);

		$ver = VERSION;
		if (!defined('SCP_VERSION'))
			define('SCP_VERSION', $ver[0]);

		$this->comp_url = $this->config->get('ascp_comp_url');
		if (!isset($this->session->data['language_old'])) {
			$this->session->data['language_old'] = $this->session->data['language'];
		}
		$query_lang = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ");
		foreach ($query_lang->rows as $result_lang) {
			$this->languages_all[$result_lang['language_id']] = $result_lang;
			$this->langcode_all[$result_lang['code']]         = $result_lang;
		}
		if (isset($this->session->data['language_old']) && $this->session->data['language_old'] != $this->session->data['language']) {
			// User change language
			$this->flag_language                 = true;
			$this->session->data['language_old'] = $this->session->data['language'];
			$this->registry->set('flag_language', true);
		}
		if ($this->registry->get('flag_language')) {
			// Change language
			if (isset($this->request->get['route']) && ($this->request->get['route'] == 'record/blog' || $this->request->get['route'] == 'record/record')) {
				$language_switch      = $this->langcode_all[$this->session->data['language']]['language_id'];
				$language_code_switch = $this->session->data['language'];
				$this->switchLanguage($language_switch, $language_code_switch);
				$this->validate();
			}
		}
		$this->cache_data = $this->cache->get('blog.seoblog');
		if (!$this->cache_data) {
			$query            = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog");
			$this->cache_data = array();
			$this->cache_data = $query->rows;
			foreach ($query->rows as $row) {
				$this->cache_data[$row['query']]                              = $row;
				$this->cache_data['keyword'][$row['keyword']]                 = $row;
				$this->cache_data['lang'][$row['query']][$row['language_id']] = $row;
			}
			$this->cache->set('blog.seoblog', $this->cache_data);
		}

	}
	public function index()
	{
		// if (file_exists(DIR_CONFIG .'ssb_library/ssb_data.php')) {
		//Disable Palladin bugs (if this link is not category (empty category), do not insert before keyword slash! Fix this bug developer)
		//if ($this->config->get('urls'))
		//{
		//$this->load->model('catalog/blog');
		//$this->model_catalog_blog->editSetting('superseobox', Array('urls' => 0));
		//}
		/*
		require_once DIR_CONFIG .'ssb_library/ssb_data.php';
		$ssb_data = ssb_data::getInstance();
		if($ssb_data->getEntityStatus('urls')){
		}
		*/
		// }

		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		if ($this->registry->get('response_work')) {
			return;
		}
		if (isset($_GET['_route_'])) {
			$this->request->get['_route_'] = $_GET['_route_'];
		}
		if (isset($_GET['route'])) {
			$this->request->get['route'] = $_GET['route'];
		}
		$this->flag = 'none';
        /*
		$q_lang     = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ");
		foreach ($q_lang->rows as $result_lang) {
			$languages_all[$result_lang['language_id']] = $result_lang;
		}
		*/
		$lang_all = $this->languages_all;
		if (isset($this->request->get['record_id']) && $this->request->get['route'] == 'record/record' && !isset($_route_)) {
			$this->request->get['route']   = 'record/record';
			$this->request->get['blog_id'] = $this->getPathByRecord($this->request->get['record_id']);
			if (isset($this->request->get['_route_'])) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
			}
			/*
			$sql   = "SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = 'record_id=" . (int) $this->request->get['record_id'] . "' ";
			$query = $this->db->query($sql);
			$rows = $query->rows;
			*/
			$blog_id   = explode('_', (string) $this->request->get['blog_id']);
			$query_tag = 'blog_id=' . (int) end($blog_id);
			if (isset($this->cache_data[$query_tag])) {
				$rows = $this->cache_data['lang'][$query_tag];
			} else {
				$rows = '';
			}
			if ($rows && !isset($_route_)) {
				foreach ($rows as $num => $val) {
					if (in_array($val['language_id'], $lang_all)) {
						unset($lang_all[$val['language_id']]);
					}
				}
				$l_a                  = end($lang_all);
				$language_switch      = $l_a['language_id'];
				$language_code_switch = $l_a['code'];
				if (!$this->registry->get('flag_language')) {
					$this->switchLanguage($language_switch, $language_code_switch);
				}
			} else {
				if (isset($_route_)) {
					$parts_route = explode('/', $_route_);
					foreach ($parts_route as $p_num => $p_val) {
						foreach ($this->cache_data as $c_num => $c_val) {
							if (isset($c_val['keyword']) && $p_val == $c_val['keyword']) {
								$pere = $c_val;
								break;
							}
						}
					}
					if (isset($pere) && $pere['language_id'] != $this->config->get('config_language_id')) {
						$language_switch      = $pere['language_id'];
						$language_code_switch = $this->languages_all[$pere['language_id']]['code'];
						if (!$this->registry->get('flag_language')) {
							$this->switchLanguage($language_switch, $language_code_switch);
						}
					}
				}
			}
			if ($this->config->get('config_seo_url')) {
				$this->validate();
			}
			if (isset($this->request->get['_route_'])) {
				$this->request->get['_route_'] = $_route_;
			}
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
			return $this->flag = 'record';
		}
		if (isset($this->request->get['blog_id']) && $this->request->get['route'] == 'record/blog' && !isset($_route_)) {
			//	if (isset($this->request->get['blog_id']) ) {
			$this->request->get['route']   = 'record/blog';
			$this->request->get['blog_id'] = $this->getPathByBlog($this->request->get['blog_id']);
			if (isset($this->request->get['_route_'])) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
			}
			/*
			$sql   = "SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = 'blog_id=" . (int) $this->request->get['blog_id'] . "' ";
			$query = $this->db->query($sql);
			$rows =  $query->rows;
			*/
			$blog_id   = explode('_', (string) $this->request->get['blog_id']);
			$query_tag = 'blog_id=' . (int) end($blog_id);
			if (isset($this->cache_data[$query_tag])) {
				$rows = $this->cache_data['lang'][$query_tag];
			} else {
				$rows = '';
			}
			if ($rows && !isset($_route_)) {
				foreach ($rows as $num => $val) {
					if (in_array($val['language_id'], $lang_all)) {
						unset($lang_all[$val['language_id']]);
					}
				}
				$l_a                  = end($lang_all);
				$language_switch      = $l_a['language_id'];
				$language_code_switch = $l_a['code'];
				if (!$this->registry->get('flag_language')) {
					$this->switchLanguage($language_switch, $language_code_switch);
				}
			} else {
				if (isset($_route_)) {
					$parts_route = explode('/', $_route_);
					foreach ($parts_route as $p_num => $p_val) {
						foreach ($this->cache_data as $c_num => $c_val) {
							if (isset($c_val['keyword']) && $p_val == $c_val['keyword']) {
								$pere = $c_val;
								break;
							}
						}
					}
				}
				if (isset($pere) && $pere['language_id'] != $this->config->get('config_language_id')) {
					$language_switch      = $pere['language_id'];
					$language_code_switch = $this->languages_all[$pere['language_id']]['code'];
					if (!$this->registry->get('flag_language')) {
						$this->switchLanguage($language_switch, $language_code_switch);
					}
				}
			}
			if ($this->config->get('config_seo_url')) {
				$this->validate();
			}
			if (isset($this->request->get['_route_'])) {
				$this->request->get['_route_'] = $_route_;
			}
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
			return $this->flag = 'blog';
		}
		if ($this->config->get('ascp_settings') != '') {
			$this->data['settings_general'] = $this->config->get('ascp_settings');
		} else {
			$this->data['settings_general'] = Array();
		}
		if (isset($this->request->get['_route_'])) {

			$this->load->model('design/bloglayout');
			$this->data['layouts'] = $this->model_design_bloglayout->getLayouts();
			$route                 = $this->request->get['_route_'];
			if (isset($this->data['settings_general']['end_url_record']) && $this->data['settings_general']['end_url_record'] != '') {
				$devider = $this->data['settings_general']['end_url_record'];
				if (strrpos($route, $devider) !== false) {
					if ($devider == '.' || $devider == ' ') {
						$route = substr_replace($route, '', strrpos($route, ''), strlen($route));
					} else {
						$route = substr_replace($route, '', strrpos($route, $devider), strlen($route));
					}
				}
			}
			$route     = trim($route, '/');
			$parts     = explode('/', $route);
			$parts_end = end($parts);

			if (strpos($parts_end, 'page-') !== false) {

				list($key, $value) = explode("-", $parts_end);

                $value = (int) $value;

				if ($value > 1) {
					$this->request->get[$key] = $value;
				}
				$title   = $this->document->getTitle();
				$options = $this->config->get('general_set');
				if (!$options['switch']) {
					$this->document->setTitle($title . " " . $key . " " . $value);
				}
				$this->config->set('blog_page', $value);
				unset($parts[count($parts) - 1]);
			}

			reset($parts);
			if (isset($this->request->get['record_id']) && $this->request->get['record_id'] != '') {
				array_push($parts, 'record_id=' . $this->request->get['record_id']);
			}

			foreach ($parts as $part) {
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE keyword = '" . $this->db->escape($part) . "'  ");
                if (isset($this->cache_data['keyword'][$this->db->escape($part)])) {
                	$query_data[0] = $this->cache_data['keyword'][$this->db->escape($part)];
                	$num_rows = 1;
                } else {
                	$query_data = array();
                	$num_rows = 0;
                }

				if ($num_rows == 0 && isset($this->request->get['record_id']) && $this->request->get['record_id'] != '') {
					$num_rows  = 1;
					$query_data[0]['query'] = 'record_id=' . $this->request->get['record_id'];
					//$query_data[0]['language_id'] = $this->config->get('config_language_id');
				}

				if ($num_rows > 0) {
					foreach ($query_data as $row) {
  						if (isset($row['keyword']) && $row['keyword'] == $this->db->escape($part)) {
							if (($row['language_id'] != $this->config->get('config_language_id')) && $num_rows < 2) {
								$languages  = array();
								$detect     = '';

								//$query_lang = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' AND language_id='" . $row['language_id'] . "'");
								$query_lang_data[0] = $this->languages_all[$row['language_id']];

								foreach ($query_lang_data as $result_lang) {
									$languages[$result_lang['code']] = $result_lang;
									$code                            = $result_lang['code'];
								}

								if (!$this->registry->get('flag_language')) {
									$this->switchLanguage($languages[$code]['language_id'], $code);
								}
								break;
							}
						}
					}

					$url = explode('=', $query_data[0]['query']);


					if (isset($url[0]) && $url[0] == 'record_id') {
						$this->request->get['record_id'] = $url[1];
						$path                            = $this->getPathByRecord($this->request->get['record_id']);
						$this->flag                      = 'record';
						$layout                          = 0;
						foreach ($this->data['layouts'] as $num => $lay) {
							if ($lay['name'] == 'Record')
								$layout = $lay['layout_id'];
						}
						$this->config->set("config_layout_id", $layout);
					} else {
						if (isset($url[0]) && $url[0] == 'blog_id') {
							$this->flag = 'blog';
							$layout     = 0;
							foreach ($this->data['layouts'] as $num => $lay) {
								if ($lay['name'] == 'Blog')
									$layout = $lay['layout_id'];
							}
							$this->config->set("config_layout_id", $layout);
							if (!isset($this->request->get['blog_id'])) {
								$this->request->get['blog_id'] = $url[1];
							} else {
								$this->request->get['blog_id'] .= '_' . $url[1];
							}
						}
					}
					if (isset($url[0]) && $url[0] == 'route') {
						$this->request->get['route'] = $url[1];
					}
				} else {
				}
			}
			$flg = false;
			if (isset($this->request->get['record_id'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = 'record/record';
				$flg                         = true;
			} elseif (isset($this->request->get['blog_id'])) {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
				$this->request->get['route'] = 'record/blog';
				$flg                         = true;
			}
			if ($flg) {
				$_route_ = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
				if ($this->config->get('config_seo_url')) {
					$this->validate();
				}
				$this->request->get['_route_'] = $_route_;
				if (isset($this->request->get['route'])) {
					$this->request->get['_route_'] = $this->request->get['route'];
				}
				return $this->flag;
			}
		} else {
		}
	}
	public function switchLanguage($language_id, $code)
	{
		if (isset($this->request->get['_route_'])) {
			$this->session->data['language'] = $code;
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
			$this->config->set('config_language_id', $language_id);
			$this->config->set('config_language', $code);
			$language = new Language($this->langcode_all[$code]['directory']);
			if (SCP_VERSION > 1) {
				$language->load('default');
			} else {
				$language->load($this->langcode_all[$code]['filename']);
			}
			$this->registry->set('language', $language);
			$this->session->data['language_old'] = $code;
		}
	}
	public function rewrite($link)
	{
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));
			$url      = '';
			$data     = array();
			if (isset($url_data['query'])) {
				parse_str($url_data['query'], $data);
			}
			if (!isset($url_data['scheme'])) {
				$url_data['scheme'] = 'http';
			}
			foreach ($data as $num => $name) {
				if ($name != 'record_id' && $name != '' && $name != 'route' && $name != 'blog_id') {
					if (is_array($name)) {
						unset($data[$num]);
					} else {
						if (is_array($data) && !empty($data) && isset($data[$name]))
							unset($data[$name]);
					}
				}
			}
			reset($data);
			if (isset($data['record_id'])) {
				$record_id = $data['record_id'];
				if ($this->config->get('config_seo_url')) {
					$path = $this->getPathByRecord($record_id);
				}
				$data['path'] = $path;
			}
			$flag_record   = false;
			$flag_category = false;
			$flag_unset    = false;
			foreach ($data as $key => $value) {
				if (isset($data['route'])) {
					if ($key == 'blog_id') {
						$path = $this->getPathByBlog($value);
					}
					if ($key == 'path') {
						$categories = explode('_', $value);
						$new        = array_reverse($categories);
						foreach ($new as $category) {
							/*
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = 'blog_id=" . (int) $category . "' AND `language_id`='".$this->config->get('config_language_id')."' ");
							if ($query->num_rows) {
							$url = '/' . $query->row['keyword'] . $url;
							}
							*/
							$query_tag = 'blog_id=' . (int) $category;
							if (isset($this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')])) {
								$row = $this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')];
							} else {
								$row = '';
							}
							if ($row) {
								$url = '/' . $row['keyword'] . $url;
							}
						}
						unset($data[$key]);
					}
					if (($data['route'] == 'record/record' && $key == 'record_id')) {
						$flag_record = true;
						/*
						$query       = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "' AND `language_id`='".$this->config->get('config_language_id')."' ");
						if ($query->num_rows) {
						$url = '/' . $query->row['keyword'];
						unset($data[$key]);
						}
						*/
						$query_tag   = $this->db->escape($key . '=' . (int) $value);
						if (isset($this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')])) {
							$row = $this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')];
						} else {
							$row = '';
						}
						if ($row) {
							$url = '/' . $row['keyword'];
							unset($data[$key]);
						}
					} elseif ($key == 'blog_id' && !$flag_record) {
						$flag_category = true;
						$categories    = explode('_', $value);
						if (count($categories) == 1) {
							$path       = $this->getPathByBlog($categories[0]);
							$categories = explode('_', $path);
						}
						foreach ($categories as $category) {
							/*
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = 'blog_id=" . (int) $category . "' AND `language_id`='".$this->config->get('config_language_id')."' ");
							if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
							}
							*/
							$query_tag = 'blog_id=' . (int) $category;
							if (isset($this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')])) {
								$row = $this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')];
							} else {
								$row = '';
							}
							if ($row) {
								$url .= '/' . $row['keyword'];
								$flag_unset = true;
							} else {
								$flag_unset = false;
								break;
							}
						}
					}
					if ($flag_category && $flag_unset && $key == 'blog_id') {
						unset($data[$key]);
					}
					if ($flag_record && $key == 'blog_id') {
						unset($data[$key]);
					} else {
						if ($url == '') {
							/*
							$sql   = "SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE `query` = '" . $this->db->escape($key . '=' . $value) . "' AND `language_id`='".$this->config->get('config_language_id')."' ";
							$query = $this->db->query($sql);
							if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
							}
							*/
							$query_tag = $this->db->escape($key . '=' . (int) $value);
							if (isset($this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')])) {
								$row = $this->cache_data['lang'][$query_tag][$this->config->get('config_language_id')];
							} else {
								$row = '';
							}
							if ($row) {
								$url .= '/' . $row['keyword'];
							}
						}
					}
				}
			}
			if ($url) {
				unset($data['route']);
				if ($this->config->get('ascp_settings') != '') {
					$this->data['settings_general'] = $this->config->get('ascp_settings');
				} else {
					$this->data['settings_general'] = Array();
				}

			if (isset($this->blog_design['blog_devider']) && $this->blog_design['blog_devider'] == '1') {
					$devider = "/";
				} else {
					$devider = "";
				}
				if (strpos($url, '.') !== false) {
					$devider = "";
				}	 else {
					if (!$flag_record) {
						if (isset($this->blog_design['end_url_category']) && $this->blog_design['end_url_category'] != '') {
							$devider         = $this->blog_design['end_url_category'];

							if (isset($this->data['settings_general']['end_url_record']) && $this->data['settings_general']['end_url_record'] != '') {
								$devider_setting = $this->data['settings_general']['end_url_record'];
							} else {
								$devider_setting = '';
							}

							if ($devider_setting == '.' || $devider_setting == ' ') {
								$devider = '';
							}
						}
					} else {
						if (isset($this->data['settings_general']['end_url_record']) && $this->data['settings_general']['end_url_record'] != '') {
							$devider = $this->data['settings_general']['end_url_record'];
							if ($devider == '.' || $devider == ' ') {
								$devider = '';
							}
						}
					}
				}
				$query  = '';
				$paging = '';
				if ($data) {
					foreach ($data as $key => $value) {
						//$value = (int)$value;
						if ($key != 'page') {
							$query .= '&' . $key . '=' . $value;
						} else {

								if ($devider != '/')
									$paging = "/" . $key . "-" . $value;
								else
									$paging = $key . "-" . $value;

						}
					}
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}
				$link = $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $devider . $paging . $query;
				return $link;
			} else {
				return $link;
			}
		} else {
			return $link;
		}
	}
	private function getPathByRecord($record_id)
	{

		if (utf8_strpos($record_id, '_') !== false) {
			$abid      = explode('_', $record_id);
			$record_id = $abid[count($abid) - 1];
		}
		$record_id = (int) $record_id;
		if ($record_id < 1)
			return false;
		static $path = null;
		if (!is_array($path)) {
			$path        = $this->cache->get('record.seopath');
			$blog_design = $this->cache->get('record.blog_design');
			if (!is_array($path))
				$path = array();
		}
		if (!isset($path[$record_id]) || !isset($blog_design[$record_id])) {
			$sql   = "SELECT r2b.blog_id as blog_id,
			IF(r.blog_main=r2b.blog_id, 1, 0) as blog_main
			FROM " . DB_PREFIX . "record_to_blog r2b
			LEFT JOIN " . DB_PREFIX . "record r  ON (r.record_id = r2b.record_id)
			WHERE r2b.record_id = '" . (int) $record_id . "' ORDER BY blog_main DESC LIMIT 1";

			$query            = $this->db->query($sql);
			$path[$record_id] = $this->getPathByBlog($query->num_rows ? (int) $query->row['blog_id'] : 0);
			if (utf8_strpos($path[$record_id], '_') !== false) {
				$abid    = explode('_', $path[$record_id]);
				$blog_id = $abid[count($abid) - 1];
			} else {
				$blog_id = (int) $path[$record_id];
			}
			$blog_id = (int) $blog_id;
			$this->load->model('catalog/blog');
			$blog_info = $this->model_catalog_blog->getBlog($blog_id);
			if (isset($blog_info['design']) && $blog_info['design'] != '') {
				$this->blog_design = unserialize($blog_info['design']);
			} else {
				$this->blog_design = Array();
			}
			if (isset($blog_info['design'])) {
				$blog_design[$record_id] = $blog_info['design'];
			} else {
				$blog_design[$record_id] = array();
			}
			$this->cache->set('record.blog_design', $blog_design);
			$this->cache->set('record.seopath', $path);
		} else {
			if (isset($blog_design[$record_id]) && is_string($blog_design[$record_id])) {
				$this->blog_design = unserialize($blog_design[$record_id]);
			} else {
				$this->blog_design = Array();
			}
		}
		if (isset($this->blog_design['blog_short_path']) && $this->blog_design['blog_short_path'] == 1)
			$path[$record_id] = '';
		return $path[$record_id];
	}
	private function getPathByBlog($blog_id)
	{
		if (utf8_strpos($blog_id, '_') !== false) {
			$abid    = explode('_', $blog_id);
			$blog_id = $abid[count($abid) - 1];
		}
		$blog_id = (int) $blog_id;
		if ($blog_id < 1)
			return false;
		static $path = null;
		$this->load->model('catalog/blog');
		$blog_info = $this->model_catalog_blog->getBlog($blog_id);
		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$this->blog_design = unserialize($blog_info['design']);
		} else {
			$this->blog_design = Array();
		}
		if (!is_array($path)) {
			$path = $this->cache->get('blog.seopath');
			if (!is_array($path))
				$path = array();
		}
		if (!isset($path[$blog_id])) {
			$max_level = 10;
			$sql       = "SELECT CONCAT_WS('_'";
			for ($i = $max_level - 1; $i >= 0; --$i) {
				$sql .= ",t$i.blog_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "blog t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog t$i ON (t$i.blog_id = t" . ($i - 1) . ".parent_id)";
			}
			$sql .= " WHERE t0.blog_id = '" . (int) $blog_id . "'";
			$query          = $this->db->query($sql);
			$path[$blog_id] = $query->num_rows ? $query->row['path'] : false;
			$this->cache->set('blog.seopath', $path);
		}
		$category = $path[$blog_id];
		return $category;
	}
	private function validate()
	{
		if (isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
			return;
		}
		if (empty($this->request->get['route'])) {
			$this->request->get['route'] = 'common/home';
		}
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$config_url = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);
			$url        = str_replace('&amp;', '&', ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo        = str_replace('&amp;', '&', str_replace($config_url, '', $this->url->link($this->request->get['route'], $this->getQueryString(array(
				'route',
				'_route_',
				'site_language'
			)), 'SSL')));
		} else {
			$config_url = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);
			$url        = str_replace('&amp;', '&', ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo        = str_replace('&amp;', '&', str_replace($config_url, '', $this->url->link($this->request->get['route'], $this->getQueryString(array(
				'route',
				'_route_',
				'site_language'
			)), 'NONSSL')));
		}
		$this->registry->set('url_site', $seo);
		if (rawurldecode($url) != rawurldecode($seo)) {
			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');

			if (!$this->comp_url) {
				$this->response->redirect($config_url . $seo);
			}
		}
	}
	private function strpos_offset($needle, $haystack, $occurrence)
	{
		$arr = explode($needle, $haystack);
		switch ($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}
	private function getQueryString($exclude = array())
	{
		if (!is_array($exclude)) {
			$exclude = array();
		}
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}
}
}