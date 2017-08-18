<?php

require_once('Admin.php');

class AuthAdmin extends Admin
{

	public function __construct(){
		parent::__construct();
	}

	public function execute()
	{

		$message = null;

		if ($this->request->method('post')) {
            /*Авторизация в админ.панель*/
            $login = $this->request->post('username');
            $pass = $this->request->post('password');
            $remember = $this->request->post('remember');
            $manager = $this->managers->get_manager((string)$login);

            if ($manager) {

	        	if ($this->managers->check_password($pass, $manager['password'])) 
	        	{
                    /*Входим в админку*/
                    $_SESSION['admin'] = $manager['login'];
                    header('location: /backend/index.php');
                    exit();
                }else
                {
                	$message = 'Password Wrong';
                }
	        }else
	        {
	        	$message = 'User Not Found';
	        }

        }

		return $this->design->render('@admin/'.$this->module.'.twig', array(
			'message' => $message,
		));
	}

}