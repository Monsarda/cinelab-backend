<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Request extends Cinelab
{

	public function __construct()
	{
		parent::__construct();

		$_POST = $this->stripslashes_recursive($_POST);
		$_GET  = $this->stripslashes_recursive($_GET);
	}

    /*Возвращение метода передачи данных*/
    public function method($method = null) {
        if(!empty($method)) {
            return strtolower($_SERVER['REQUEST_METHOD']) == strtolower($method);
        }
        return $_SERVER['REQUEST_METHOD'];
    }

	/**
	 * Рекурсивная чистка магических слешей
	 */
	private function stripslashes_recursive($var)
	{
		if(get_magic_quotes_gpc())
		{
			$res = null;
			if(is_array($var))
				foreach($var as $k=>$v)
					$res[stripcslashes($k)] = $this->stripslashes_recursive($v);
				else
					$res = stripcslashes($var);
		}
		else
		{
			$res = $var;
		}
		return $res;
	}

	public function get($name, $type = null)
    {
    	$request = null;
    	if(isset($_GET[$name]))
    		$request = $_GET[$name];
    		
    	if(!empty($type) && is_array($request))
    		$vrequestal = reset($request);
    	
    	if($type == 'string')
    		return strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $request));
    		
    	if($type == 'integer')
    		return intval($request);

    	if($type == 'boolean')
    		return !empty($request);
    		
    	return $request;
    }

    public function post($name = null, $type = null)
    {
    	$request = null;
    	if(!empty($name) && isset($_POST[$name]))
    		$request = $_POST[$name];
    	elseif(empty($name))
    		$request = $_POST;
    		
    	if($type == 'string')
    		return strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $request));
    		
    	if($type == 'integer')
    		return intval($request);

    	if($type == 'boolean')
    		return !empty($request);

    	return $request;
    }

    /**
    * Проверка сессии
    */
    public function check_session() {
        if(!empty($_POST)) {
            if(empty($_POST['session_id']) || $_POST['session_id'] != session_id()) {
                unset($_POST);
                return false;
            }
        }
        return true;
    }

}