<?php 

require_once('Admin.php');

class DashboardAdmin extends Admin
{
	
	function __construct()
	{
		parent::__construct();

	}

	public function execute()
	{

		return $this->design->render('@admin/'.$this->module.'.twig', array());

	}
}