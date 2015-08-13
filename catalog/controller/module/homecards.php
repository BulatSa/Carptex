<?php  
class ControllerModuleHomecards extends Controller {
	protected function index($setting) {
		$this->language->load('module/homecards');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	$card_title_template = $this->language->get('card_title');
		
		$this->load->model('module/homecards');
		$this->load->model('tool/image');
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/homecards.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/homecards.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/homecards.css');
		}
		
		$setting['main_image_w'] = (int)$setting['main_image_w'] ? (int)$setting['main_image_w'] : 105;
		$setting['main_image_h'] = (int)$setting['main_image_h'] ? (int)$setting['main_image_h'] : 215;
		$setting['child_image_w'] = (int)$setting['child_image_w'] ? (int)$setting['child_image_w'] : 105;
		$setting['child_image_h'] = (int)$setting['child_image_h'] ? (int)$setting['child_image_h'] : 80;
		
		$this->data['categories'] = array();
		$categories = $this->model_module_homecards->getCategories(0);
		
		foreach ($categories as $category) {
			$children = $this->model_module_homecards->getCategories($category['category_id']);
			$children_data = array();
			
			foreach ($children as $child) {
				$children_data[] = array(
					'name'  => $child['name'],
					'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
					'image' => $this->model_tool_image->resize($child['image'], $setting['child_image_w'], $setting['child_image_h']),
				);
			}
			
			$count_specials = count($children_data) ? 1 : 3;
			
			$specials = $this->model_module_homecards->getCategorySpecial($category['category_id'], $count_specials);
			
			if ( !count($specials) ) {
				$specials = $this->model_module_homecards->getNewestProducts($category['category_id'], $count_specials);
			}
			
			$specials_data = array();
			
			foreach ($specials as $special) {
				$specials_data[] = array(
					'name' => $special['name'],
					'href' => $this->url->link('product/product', 'product_id=' . $special['product_id']),
					'price' => $this->currency->format($special['price']),
					'special' => $special['special'] ? $this->currency->format($special['special']) : 0,
					'image' => isset($special['image']) ? $this->model_tool_image->resize($special['image'], 35, 35) : ''
				);
			}
			
			if ($category['image'] && $setting['main_image_w'] > 0 && $setting['main_image_h'] > 0) {
				$image = $this->model_tool_image->resize($category['image'], $setting['main_image_w'], $setting['main_image_h']);
			} else {
				$image = '';
			}
			
			$price = $this->currency->format( $this->model_module_homecards->getCategoryPrice($category['category_id']) );
			$href = $this->url->link('product/category', 'path=' . $category['category_id']);
			$description = strip_tags(htmlspecialchars_decode($category['description']));
			
			if (mb_strlen($description, 'UTF-8') > 300) {
				$description = mb_substr($description, 0, 300, 'UTF-8') . '...';
			}
			
			// Level 1
			$this->data['categories'][] = array(
				'name'        => sprintf($card_title_template, $href, $category['name'], $price),
				'children'    => $children_data,
				'href'        => $href,
				'image'       => $image,
				'price'       => $price,
				'specials'    => $specials_data,
				'description' => $description,
			);
		}
		
		$this->data['settings'] = array(
			'main_image_w' => $setting['main_image_w'],
			'main_image_h' => $setting['main_image_h'],
		);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/homecards.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/homecards.tpl';
		} else {
			$this->template = 'default/template/module/homecards.tpl';
		}
		
		$this->render();
  	}

}
?>