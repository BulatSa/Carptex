<?php
 	$ver = VERSION;
	if (!defined('SCP_VERSION')) define('SCP_VERSION', $ver[0]);
 	$this->file= DIR_APPLICATION.'controller/common/front.php';
    $this->class='ControllerCommonfront';
    $this->method ='install';
    require_once($this->file);
	if (!isset($registry)) {
			$registry = $this->registry;
	}

   if (SCP_VERSION < 2)
    {
		$SeoCMSPROFront = new ControllerCommonFront($registry);
		$SeoCMSPROFront->install();
	}