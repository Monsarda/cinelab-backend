<?php

require_once('Cinelab.php');

class Managers extends Cinelab {

    /*Список параметров доступа для менеджера сайта*/
    public $permissions_list = array(
        'production','rental','digital','vfx','soundmix','datadelivery',
    );

    private $all_managers = array();
    
    public function __construct() {}

    /*Инициализация менеджеров*/
    private function init_managers() {

    	$query = "SELECT * FROM c_managers ORDER BY id";
		
        $sthm = $this->db->prepare($query);
        $sthm->execute();
        $managers = $sthm->fetchAll();

        // print_r($managers);

        // exit();

        foreach ($managers as $m) {
            $this->all_managers[$m['id']] = $m;
            if (!is_null($m['permissions'])) {
                $m['permissions'] = explode(',', $m['permissions']);
                foreach ($m['permissions'] as $permission) {
                    $permission = trim($permission);
                }
            } else {
                $m['permissions'] = $this->permissions_list;
            }
        }
    }

    public function get_manager($id = null)
    {
    	if (empty($this->all_managers)) {
            $this->init_managers();
        }
        // Если не запрашивается по логину, отдаём текущего менеджера или false
        if(empty($id)) {
            if (!empty($_SESSION['admin'])) {
                $id = $_SESSION['admin'];
            }
        }
        if(is_int($id) && isset($this->all_managers[$id])) {
            return $this->all_managers[$id];
        } elseif(is_string($id)) {
            foreach ($this->all_managers as $m) {
                if ($m['login'] == $id) {
                    return $m;
                }
            }
        }
        return false;
    }

    /*Проверка доступа к определнному модулю сайта*/
    public function access($module) {
        $manager = $this->get_manager();
        if(is_array($manager->permissions)) {
            return in_array($module, $manager->permissions);
        } else {
            return false;
        }
    }

    /*Проверка пароля*/
    public function check_password($password, $crypt_pass) {
        
        $crypt_pass = password_verify($password, $crypt_pass);

        return ($password == $crypt_pass);
    }
}
