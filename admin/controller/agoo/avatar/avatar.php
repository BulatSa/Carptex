<?php
class ControllerAgooAvatarAvatar extends Controller
{
	private $error = array();
	protected  $data;

	public function index($data)
	{
		$this->data = $data;
		$ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
        $this->data['avatar_template'] = 'agoo/avatar/avatar.tpl';
       	$this->language->load('agoo/avatar/avatar');

        if (!isset($this->data['id'])) {
         $this->data['id'] = false;
        }
		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['anchor'])) {
			$this->data['ascp_widgets'][$this->data['id']]['anchor'] = "content_selector = '#content';";
		}


$this->data['ascp_widgets'][$this->data['id']]['anchor_templates'] = array(

$this->language->get('entry_anchor_templates_avatar') => "content_selector = '#content';",

$this->language->get('entry_anchor_templates_avatar_reviews') => "$('#cmswidget-'+cmswidget).remove();
data = $(data).html();
$('.seocmspro_load_avatar').append('<div>'+data+'</div>');",

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

        return $this->data;
	}
}
?>