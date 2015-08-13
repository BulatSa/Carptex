<?php

class agooLoader  extends Controller{

   protected  $Loader;

   public function __call($name, array $params)
   {
	 //if (!$this->registry->get('loader_work'))
	 {

		$this->registry->set('loader_work', 1);
        $flag = false;
        $modules = NULL;

        if ($name == 'library') {
			$file = DIR_SYSTEM . 'library/agoo/' . $params[0] . '.php';
         	if (file_exists($file)) {
				$params[0]='agoo/' . $params[0];
			}

        }
        if ($name == 'helper') {
         	$file = DIR_SYSTEM . 'helper/agoo/' . $params[0] . '.php';
         	if (file_exists($file)) {
				$params[0]='agoo/' . $params[0];
			}
        }


        if ($name == 'model') {
			$file  = DIR_APPLICATION . 'model/agoo/' . $params[0] . '.php';
         	if (file_exists($file) || isset($params[1])) {
				$flag = true;

				if (isset($params[1])) {
					$this->agoomodel($params[0], $params[1] );
				} else {
				 	$this->agoomodel($params[0]);
				}

			}
        }
		if (!$flag) {
			$this->Loader = new Loader($this->registry);

	        $modules = call_user_func_array(array($this->Loader , $name), $params);

			unset($this->Loader);
		}

	   $this->registry->set('loader_work', false);

       return $modules;
    }

   }

	public function agoomodel($model, $dir_application = DIR_APPLICATION )
	{
		$file  = $dir_application . 'model/agoo/' . $model . '.php';
		$class = 'agooModel' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		if (!file_exists($file)) {
			$file  = $dir_application . 'model/' . $model . '.php';
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		}
		if (file_exists($file)) {
			//if (!class_exists($class)) {
				include_once($file);

				$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
			//}
		}
		else {
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();
		}
	}

}


?>