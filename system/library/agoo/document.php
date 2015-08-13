<?php
class agooDocument extends Controller
{
	protected $Document;
	private $og_image;
	private $og_description;
	private $og_title;
	private $og_url;

	public function __call($name, array $params)
	{

      	$modules = false;
        //echo $name."<br>";

        if ($name=='setOgImage') {
        } else {
	        $this_document = $this->registry->get('document');
			$this->Document = $this->registry->get('document_old');

			$modules   = call_user_func_array(array(
				$this->Document,
				$name
			), $params);
			unset($this->Document);
	        $this->registry->set('document', $this_document);
        }

		return $modules;
	}

	public function setOgImage($image) {
		$this->og_image = $image;
	}

	public function getOgImage() {
		return $this->og_image;
	}

	public function setOgTitle($title) {
		$this->og_title = $title;
	}

	public function getOgTitle() {
		return $this->og_title;
	}

	public function setOgDescription($description) {
		$this->og_description = $description;
	}

	public function getOgDescription() {
		return $this->og_description;
	}

	public function setOgUrl($url) {
		$this->og_url = $url;
	}

	public function getOgUrl() {
		return $this->og_url;
	}



	public function cont($cont)
	{
		$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load controller ' . $cont . '!');
			exit();
		}
	}
}
?>