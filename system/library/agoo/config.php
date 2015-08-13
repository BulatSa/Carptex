<?php
	//$lay = new agooLayout($this->registry);
	//$this->registry->set('all_layout_id', $lay->getLayoutId('all'));

class agooConfig  extends Controller  {

   protected  $Config;

   public function __call($name, array $params)
   {
	 //if (!$this->registry->get('config_work'))
	 {
        $modules = false;
		$this->registry->set('config_work', 1);
		$settings_general = $this->registry->get('config_ascp_settings');

        if (isset($settings_general['layout_url_status'])) {
        	$url_status = $settings_general['layout_url_status'];
        } else {
        	$url_status = false;
        }

        $layout_id = false;
        $flag_layout = false;
        $request_url = ltrim($this->request->server['REQUEST_URI'], '/');

		$this->Config_ = $this->registry->get('config_old');

        $modules = call_user_func_array(array($this->Config_ , $name), $params);

		unset($this->Config_);


        if (!$this->registry->get('admin_work') && ($name == 'get' && $params[0]=='blog_module') || ($name == 'get' && $params[0]=='blogurl_module') || ($name == 'get' && $params[0]=='langmark_module')  ) {

        	$all_layout_id = $this->registry->get('all_layout_id');

       	   	$layout_id =  $this->registry->get('agoo_layout_id');

			if (!$layout_id) {

						$lay = new agooLayout($this->registry);
						$layout_id = $lay->getLayoutId();
						unset($lay);
			}

			if ($modules && is_array($modules)) {

				foreach ($modules as $num => $module) {
					if (isset($module['layout_id']) && (($module['layout_id'] == $all_layout_id) || is_array($module['layout_id']))) {


	                    if (is_array($module['layout_id']) || !isset($module['layout_id'])) {

									if (isset($module['url']) && trim($module['url'])!='') {

								        if (isset($module['url_template'])) {
								        	$url_status = $module['url_template'];
								        } else {
								        	$url_status = false;
								        }

                                        if ($url_status =="1") {

											$pos = utf8_strpos($request_url, trim($module['url']));

  											if ($pos === false) {

											} else {
											  $modules[$num]['layout_id'] = $layout_id;
											}

                                        } else {

											if (trim($module['url'])== $request_url) {
	                                         $modules[$num]['layout_id'] = $layout_id;
											}
										}



									} else {

									if ($module['status'] > 0) {

										if ($layout_id) {
		                                    foreach ($module['layout_id'] as $key => $value) {
												if ($value == $layout_id) {
					                     		  	$modules[$num]['layout_id'] = $layout_id;
				                     		  	}

			                     			}
			                     		 }

			                     	}


	                     			}
	                    }
					}
				}
			}


        }



	   $this->registry->set('config_work', false);

        return $modules;
    }

   }



}


class agooLayout  extends Controller  {

   public function getLayoutId($route = '') {
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/information');

		if ($route == '') {
			if (isset($this->request->get['route'])) {
				$route = (string)$this->request->get['route'];
			} else {
				$route = 'common/home';
			}
		}

		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			 $layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if ($route == 'record/blog' && isset($this->request->get['blog_id'])) {
			$path      = explode('_', (string) $this->request->get['blog_id']);
			$layout_id = $this->model_design_bloglayout->getBlogLayoutId(end($path));
		}

		if ($route == 'record/record' && isset($this->request->get['record_id'])) {
			$layout_id = $this->model_design_bloglayout->getRecordLayoutId($this->request->get['record_id']);
		}


		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
       /*
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}
        */
        if ($route == '' && $layout_id)
        {
        	$this->registry->set('agoo_layout_id', $layout_id);
        }

		return $layout_id;

   }
}



?>