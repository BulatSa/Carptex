<?php
class ControllerRecordSeourl extends Controller
{	public function index() {
		$this->getChild('common/seoblog');
		echo $this->url->link( base64_decode($this->request->get['seoroute']) , base64_decode($this->request->get['seoquery']) );
	}
}