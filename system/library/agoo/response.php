<?php
class agooResponse extends Controller
{
	protected $Response;
	protected  $data;
	public function __call($name, array $params)
	{
		if ($this->config->get('ascp_settings') != '') {
			$data['settings_general'] = $this->config->get('ascp_settings');
		}  else {
			$data['settings_general'] = Array();
		}

         if (isset($data['settings_general']['cache_pages']) && $data['settings_general']['cache_pages']) {

            $session = $this->session->data;
            if (isset($session['token'])) {
             unset($session['token']);
            }
            if (isset($session['captcha'])) {
             unset($session['captcha']);
            }

		  	$data_cache['url'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		  	$hash = md5(serialize($data_cache).serialize($this->request->post).serialize($session).serialize($this->config->get('config_language_id')) . serialize($this->config->get('config_store_id')));
	        $cache_name        	= 'blog.output.'.$hash;

			$module_view_cache = $this->cache->get($cache_name);


	        if (isset($module_view_cache) && $module_view_cache!='' && !$this->registry->get('blog_cache_set')) {

	         $Response_old = $this->registry->get('response_old');
			 $this->registry->set('response', $Response_old);
             //echo "Bingo cache";
             $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
	         $this->response->setOutput($module_view_cache);
			 $this->response->output();
			 exit();
	        }
         }


      	$modules = false;
      	$code_array  = $this->config->get('general_set');
        if (isset($code_array['pagination_prefix'])) {
       	 $pagination_prefix = $code_array['pagination_prefix'];
        } else {
       	 $pagination_prefix = 'page';
        }

        // link to active sitemap
        if (strtolower($name)=='setoutput') {
           if ($this->config->get('google_sitemap_blog_status')) {

				if ($this->config->get('ascp_settings_sitemap') != '') {
					$data['ascp_settings_sitemap'] = $this->config->get('ascp_settings_sitemap');
				}  else {
					$data['ascp_settings_sitemap'] = Array();
				}

				if (isset($data['ascp_settings_sitemap']['google_sitemap_blog_inter_status']) && $data['ascp_settings_sitemap']['google_sitemap_blog_inter_status'] ) {

					if (isset($this->request->get['route'])) {
						$data['route'] = $this->request->get['route'];
					} else {
						$data['route'] = false;
					}

	               if (isset($data['ascp_settings_sitemap']['google_sitemap_blog_inter_route']) && $data['ascp_settings_sitemap']['google_sitemap_blog_inter_route']!='' && $data['ascp_settings_sitemap']['google_sitemap_blog_inter_route']==$data['route'] && $data['route']!='record/google_sitemap_blog') {

	                 if (isset($data['ascp_settings_sitemap']['google_sitemap_blog_inter_tag']) && $data['ascp_settings_sitemap']['google_sitemap_blog_inter_tag']!='' ) {

		                 $google_sitemap_blog_inter_tag = html_entity_decode($data['ascp_settings_sitemap']['google_sitemap_blog_inter_tag'], ENT_QUOTES, 'UTF-8');

		                 if (strpos($params[0] , $google_sitemap_blog_inter_tag)===false) {

		                 } else {

					         if ($this->cont('record/google_sitemap_blog')) {

						         $sitemap_html    = $this->controller_record_google_sitemap_blog->getascp();
						         $find = array($google_sitemap_blog_inter_tag);
								 $replace = array($sitemap_html.$google_sitemap_blog_inter_tag);
								 $params[0] = str_replace($find, $replace, $params[0]);
							 }
		                 }
    	             }
                   }
		        }

	        }

        }

        if (isset($params[0])) {
           if (isset($data['settings_general']['og']) && $data['settings_general']['og'] ) {
            if (strpos($params[0] , "og:image")===false && method_exists($this->document,'getOgImage')) {
              $og_image = $this->document->getOgImage();
              if ($og_image && $og_image!='')
              $params[0] = str_replace("</head>", '
<meta property="og:image" content="'.$og_image.'" />
</head>', $params[0]);
	        }

            if (strpos($params[0] , "og:title")===false && method_exists($this->document,'getOgTitle')) {
              $og_title = $this->document->getOgTitle();
              if ($og_title && $og_title!='')
              $params[0] = str_replace("</head>", '
<meta property="og:title" content="'.$og_title.'" />
</head>', $params[0]);
	        }

            if (strpos($params[0] , "og:description")===false && method_exists($this->document,'getOgDescription')) {
              $og_description = $this->document->getOgDescription();
              if ($og_description && $og_description!='')
              $params[0] = str_replace("</head>", '
<meta property="og:description" content="'.$og_description.'" />
</head>', $params[0]);
	        }

            if (strpos($params[0] , "og:url")===false && method_exists($this->document,'getOgUrl')) {
              $og_url = $this->document->getOgUrl();
              if ($og_url && $og_url!='')
              $params[0] = str_replace("</head>", '
<meta property="og:url" content="'.$og_url.'" />
</head>', $params[0]);
	        }

		}

	       if (isset($data['settings_general']['get_pagination']) && $data['settings_general']['get_pagination'] ) {
	              	$get_pagination = $data['settings_general']['get_pagination'];
	       } else {
	             	$get_pagination = 'tracking';
	       }


	      	if (strpos($params[0] , "/".$pagination_prefix."-{page}")===false) {

	        } else {

	          if ($this->config->get('config_seo_url_type')=='seo_url') {
	          	$params[0] = str_replace("/".$pagination_prefix."-{page}", "", $params[0]);
	          } else {
	            $params[0] = str_replace("/".$pagination_prefix."-{page}", "/", $params[0]);
	          }

	        }

			if (strpos($params[0] , $get_pagination."=cmswidget")===false) {
			} else {

				  $tok = "/(((\&|(\&amp\;))|(\?))".$get_pagination."=cmswidget-\d+.*?(_wpage-1|_wpage-\{page\}))(?=((\')|(\")|(\?)|(\&)))/";
                  //$tok = "/(((\&amp\;)|(\&)|(\?))".$get_pagination."=cmswidget-\d+(.*?)_((wpage-\{page\})|(wpage-1))(.*?))([\"]|[\'])/";
	              $params[0] = preg_replace($tok , "", $params[0] );
			}

	      	if (strpos($params[0] , "/".$pagination_prefix."-1")===false) {

	        } else {

			if ($this->registry->get('blog_design')) {
                $blog_design = $this->registry->get('blog_design');

			if (isset($blog_design['blog_devider']) && $blog_design['blog_devider'] == '1') {
				 $devider = "/";
				} else {
				 $devider = "";
				}
				if (isset($blog_design['end_url_category']) && strpos($blog_design['end_url_category'], '.') !== false) {
					$devider = "";
				}
	            $params[0] = str_replace("/".$pagination_prefix."-1", $devider, $params[0]);
               }

			}

            $params[0] = str_replace("//?", "/?", $params[0]);
        }


        if ($this->registry->get('admin_work')) {
	         if ($this->cont('catalog/seocms')) {
		         $admin_html    = $this->controller_catalog_seocms->index();

		         $find = array("</body>", "</html>");
				 $replace = array($admin_html."</body>","</html>");
				 $params[0] = str_replace($find, $replace, $params[0]);
			 }
        }


		$this->registry->set('response_work', true);
		$this->Response = $this->registry->get('response_old');

		if (strtolower($name)=='setoutput' && $this->registry->get("blog_output") && isset($data['settings_general']['cache_pages']) && $data['settings_general']['cache_pages'])  {

		        $cache_output = $params[0];
				$this->cache->set($cache_name, $cache_output);
				$this->registry->set('blog_cache_set', true);
		}


		$modules   = call_user_func_array(array(
			$this->Response,
			$name
		), $params);
		unset($this->Response);

        $this->registry->set('response_work', false);
		return $modules;



	}

	public function cont($cont)
	{
		$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
			return true;
		} else {
			//trigger_error('Error: Could not load controller ' . $cont . '!');
			//exit();
			return false;
		}
	}


}
?>