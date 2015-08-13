<?php
class ControllerAgooHookHook extends Controller
{
	private $error = array();
	protected  $data;

	public function index($data)
	{
		$this->data = $data;
		$ver = VERSION;
 		if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
        $this->data['hook_template'] = 'agoo/hook/hook.tpl';
       	$this->language->load('agoo/hook/hook');

        return $this->data;
	}
}
?>