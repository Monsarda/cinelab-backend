<?php

require_once('Admin.php');

class IndexAdmin extends Admin
{
	
    private $modules_permissions = array(
        'DashboardAdmin' => 'dashboard',
        'ContentPageAdmin' => 'content',
        'ProjectsModuleAdmin' => 'projects',
        'VoicesModuleAdmin' => 'voicelibrary',
        'RentalModuleAdmin' => 'rental',
    );

    private $permissions;

	function __construct()
	{
		parent::__construct();

        // Администратор
        $this->manager = $this->managers->get_manager();
        if (!$this->manager && $this->module!='AuthAdmin') {
            header('location: /backend/index.php?module=AuthAdmin');
            exit();
        } elseif ($this->manager && $this->module == 'AuthAdmin') {
            header('location: /backend/index.php');
            exit();
        }

        

	}

    public function fetch()
    {

        $this->permissions = explode(',', $this->manager['permissions']);

        if (empty($this->permissions[0])) {
            $this->permissions = array_values($this->modules_permissions);
        }

        // print_r($permissions);

        // exit();

        $this->design->twig->addGlobal('permissions', $this->permissions);
        $this->design->twig->addGlobal('modules_permissions', $this->modules_permissions);

        if (@in_array($this->modules_permissions[$this->module], $this->permissions) || $this->module == 'LogoutAdmin' || $this->module == 'AuthAdmin') {
            require_once($this->module.'.php');
            $class = new $this->module;
            return $class->execute();
        }else 
        {
            return $this->design->render('@admin/AccessDenied.twig', array(
                'menu_id' => $this->request->get('menu_id'),
                'subject' => $this->request->get('subject'),
                'page' => $this->page = strtolower(substr($this->request->get('subject'), 0,-4))
            ));
        }        
        
    }
}