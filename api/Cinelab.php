<?php

class Cinelab {
    
    private $classes = array(
        'config'   => 'Config',
        'design'   => 'Design',
        'db'       => 'Database',
        'request'  => 'Request',
        'captcha'  => 'Captcha',
        'pages'    => 'Pages',
        'projects' => 'Projects',
        'voices'   => 'Voices',
        'lang'     => 'Language',
        'managers' => 'Managers',
    );
    
    private static $objects = array();
    
    public function __construct() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }
    
    public function __get($name) {
        // Если такой объект уже существует, возвращаем его
        if(isset(self::$objects[$name])) {
            return(self::$objects[$name]);
        }

        // Если запрошенного API не существует - ошибка
        if(!array_key_exists($name, $this->classes)) {
            return null;
        }

        // Определяем имя нужного класса
        $class = $this->classes[$name];

        // Подключаем его
        include_once(dirname(__FILE__).'/'.$class.'.php');

        // Сохраняем для будущих обращений к нему
        self::$objects[$name] = new $class();

        // Возвращаем созданный объект
        return self::$objects[$name];
    }
    
}
