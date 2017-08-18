<?php 

require_once('Admin.php');

class ProjectsModuleAdmin extends Admin
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function execute()
	{

        if (($this->request->get('page') !== null && !empty($this->request->get('page')))) {
            $page = $this->request->get('page') * 8 - 8;
        }else
        {
            $page = 0;
        }

        $projects = $this->projects->get_projects(array(),  $page);

		return $this->design->render('@admin/'.$this->module.'.twig', array(

            'projects' => $projects,
        ));
	}
}