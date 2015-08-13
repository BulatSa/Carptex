<?php
class ControllerAgooReviewsReviews extends Controller
{
	private $error = array();
	protected  $data;

	public function index($data)
	{
		$this->data = $data;
		$ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
        $this->data['reviews_template'] = 'agoo/reviews/reviews.tpl';
       	$this->language->load('agoo/reviews/reviews');

		if (isset($this->data['id'])) {
           	$this->load->model('catalog/blog');
        	$this->load->model('catalog/category');
			$this->data['categories'] = $this->model_catalog_blog->getCategories(0);
			$this->data['cat'] = $this->model_catalog_category->getCategories(0);
		}


        if (!isset($this->data['id'])) {
         $this->data['id'] = false;
        }
		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['anchor'])) {
			$this->data['ascp_widgets'][$this->data['id']]['anchor'] = '';
		}



		$this->data['block_reviews_width_templates'] = array(
		$this->language->get('entry_block_reviews_width_templates_2') => '49%',
		$this->language->get('entry_block_reviews_width_templates_3') => '32%',
		$this->language->get('entry_block_reviews_width_templates_4') => '24%',
		$this->language->get('entry_block_reviews_width_templates_5') => '19%',
		$this->language->get('entry_value_templates_clear') => ""
		);


$this->data['ascp_widgets'][$this->data['id']]['anchor_templates'] = array(

$this->language->get('entry_anchor_templates_html') => "$('#cmswidget-'+cmswidget).remove();
data = $(data).html();
$('".$this->language->get('text_anchor_templates_selector')."').html(data);",

$this->language->get('entry_anchor_templates_prepend') => "$('#cmswidget-'+cmswidget).remove();
data = $(data).html();
$('".$this->language->get('text_anchor_templates_selector')."').prepend(data);",

$this->language->get('entry_anchor_templates_append') => "$('#cmswidget-'+cmswidget).remove();
data = $(data).html();
$('".$this->language->get('text_anchor_templates_selector')."').append(data);",

$this->language->get('entry_anchor_templates_before') => "$('#cmswidget-'+cmswidget).remove();
data = $(data).html();
$('".$this->language->get('text_anchor_templates_selector')."').before(data);",

$this->language->get('entry_anchor_templates_after') => "$('#cmswidget-'+cmswidget).remove();
data = $(data).html();
$('".$this->language->get('text_anchor_templates_selector')."').after(data);",

$this->language->get('entry_anchor_templates_clear') => ""
);

if (SCP_VERSION > 1) {
$this->data['ascp_widgets'][$this->data['id']]['box_begin_templates'] = array(
$this->language->get('entry_box_begin_templates_empty') => '<div>',
$this->language->get('entry_anchor_templates_clear') => ""
);
} else {
$this->data['ascp_widgets'][$this->data['id']]['box_begin_templates'] = array(
$this->language->get('entry_box_begin_templates_empty') => '<div>',
$this->language->get('entry_anchor_templates_clear') => ""
);

}

if (SCP_VERSION > 1) {
$this->data['ascp_widgets'][$this->data['id']]['box_end_templates'] = array(
$this->language->get('entry_box_end_templates_empty') => '</div>',
$this->language->get('entry_anchor_templates_clear') => ""
);
} else {
$this->data['ascp_widgets'][$this->data['id']]['box_end_templates'] = array(
$this->language->get('entry_box_end_templates_empty') => '</div>',
$this->language->get('entry_anchor_templates_clear') => ""
);

}


        return $this->data;
	}
}
?>