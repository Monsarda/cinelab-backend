<?php

require_once dirname(__DIR__).'/api/Cinelab.php';

class Config extends Cinelab {

    /*Файл для хранения настроек*/
    public $config_file = 'config/config.ini';
    
    private $vars = array();

    public function __construct() {
        /*Читаем настройки из дефолтного файла*/
        $ini = parse_ini_file(dirname(dirname(__FILE__)).'/'.$this->config_file);
        /*Записываем настройку как переменную класса*/
        foreach($ini as $var=>$value) {
            $this->vars[$var] = $value;
        }
    }

    /*Выборка настройки*/
    public function __get($name) {
        if(isset($this->vars[$name])) {
            return $this->vars[$name];
        } else {
            return null;
        }
    }

    
}
