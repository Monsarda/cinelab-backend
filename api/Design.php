<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';
require_once dirname(__DIR__).'/Twig/Autoloader.php';


class Design extends Cinelab
{
	
	public $twig;

	function __construct()
	{
		

		$templateDir1 = dirname(__DIR__).'/templates/html';
		$templateDir2 = dirname(__DIR__).'/backend/templates/html';

		Twig_Autoloader::register();


		$loader = new Twig_Loader_Filesystem(array($templateDir1, $templateDir2));



		$loader->addPath($templateDir1, 'main');
		$loader->addPath($templateDir2, 'admin');

		$this->twig = new Twig_Environment($loader, array(
		    // 'cache' => 'cache/cinelab'
		));

	}

	public function render($name,$vars = array())
	{
		return $this->twig->render($name, $vars);
	}
}