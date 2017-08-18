<?php

require_once('api/Cinelab.php');

class Admin extends Cinelab
{

    protected $pages_array;
    protected $module;
    

    public function __construct(){

        parent::__construct();

        $this->pages_array = $this->pages->get_pages();
        $this->module = $this->request->get('module');
        if(empty($this->module)) {
            $this->module = 'DashboardAdmin';
        }

        $this->design->twig->addGlobal('pages', $this->pages_array);
        $this->design->twig->addGlobal('module',  $this->module);
        


    }


}