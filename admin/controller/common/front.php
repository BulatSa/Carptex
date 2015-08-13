<?php
class ControllerCommonFront extends Controller {

	public function install() {

			$this->load->library('user');
			$this->user = new User($this->registry);

			if ($this->user->isLogged() || $this->registry->get('admin_work')) {
	          $this->config->set('config_maintenance', false);
    	    }

        if (!$this->config->get('config_maintenance')){
  	        $this->load->library('agoo/response');
	        $this->load->model('tool/image');
	        $this->load->model('design/layout');
	        $this->load->library('agoo/document');

			$loader_old = $this->registry->get('load');
			$this->registry->set('load_old', $loader_old);
			$this->load->library('agoo/loader');
			$agooloader = new agooLoader($this->registry);
			$this->registry->set('load', $agooloader);

			$this->load->library('agoo/document');
			$Document = $this->registry->get('document');
			$this->registry->set('document_old', $Document);
			$agooDocument = new agooDocument($this->registry);
			$this->registry->set('document', $agooDocument);


	  	 $this->registry->set('config_ascp_settings', $this->config->get('ascp_settings'));



		if (!$this->registry->get('loader_loading'))
		{
			$this->load->library('agoo/config');
			$Config = $this->registry->get('config');
			$this->registry->set('config_old', $Config);
			$agooConfig = new agooConfig($this->registry);
			$this->registry->set('config', $agooConfig);

			$this->load->library('agoo/response');
			$Response = $this->registry->get('response');
			$this->registry->set('response_old', $Response);
			$agooResponse = new agooResponse($this->registry);
			$this->registry->set('response', $agooResponse);

		}

		$this->registry->set('loader_loading', true);
        $this->config->set('config_maintenance', false);

       } else {       	if (SCP_VERSION > 1) {       		return $this->load->controller('common/maintenance');
       	}
       }
	}
}