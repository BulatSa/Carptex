<?php
class agooCache  extends Controller
{
	private $expire = 36000;
    protected  $Cache;

   public function __call($name, array $params)
   {
		//error_reporting(0);
		//set_error_handler('agoo_error_handler');
      	$modules = false;
		$this->registry->set('cache_work', true);

        $object = 'Cache';
	    $Cache_ =  new $object();

        //$Cache_ ->_set('expire', 36000 );

        $modules = call_user_func_array(array($Cache_ , $name), $params);

		unset($Cache_);

		$this->registry->set('cache_work', false);
		restore_error_handler();
		error_reporting(E_ALL);
        return $modules;
   }

	public function set($key, $value)
	{
		$this->delete($key);
		$file   = DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);
		$handle = fopen($file, 'w');
		fwrite($handle, serialize($value));
		fclose($handle);
	}




	public function set_expire($value)
	{
		$this->expire = $value;
	}

	public function get_expire()
	{
		return $this->expire;
	}

}

	class agooNewCache  extends Cache
	{
		protected $expire;
		public function _set($name, $value) {
			//$this->expire =$value;
		}
	}


if (!function_exists('agoo_error_handler')) {
	function agoo_error_handler($errno, $errstr)
	{
	}
}


?>