<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Language extends Cinelab
{
	
	private $default_locale = 'en';

	function __construct()
	{
		parent::__construct();

		if (!isset($_SESSION['lang'])) {
			$_SESSION['lang'] = $this->default_locale;
		}

	}

	public function set_locale($lang)
	{
		$_SESSION['lang'] = $lang;
	}

	public function get_locale()
	{
		return $_SESSION['lang'];
	}

	
}