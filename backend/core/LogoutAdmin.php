<?php

require_once('Admin.php');

class LogoutAdmin extends Admin
{

	public function __construct(){
		parent::__construct();

		session_destroy();
		header('location: /backend');
        exit();

	}

	public function execute()
	{
		return false;
	}

}