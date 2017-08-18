<?php 

chdir('..');
// Засекаем время
if(!empty($_SERVER['HTTP_USER_AGENT'])){
    session_name(md5($_SERVER['HTTP_USER_AGENT']));
}
session_start();
$_SESSION['id'] = session_id();

@ini_set('session.gc_maxlifetime', 86400); // 86400 = 24 часа
@ini_set('session.cookie_lifetime', 0); // 0 - пока браузер не закрыт

require_once('backend/core/IndexAdmin.php');

// Кеширование в админке нам не нужно
header("Cache-Control: no-cache, must-revalidate");
header("Expires: -1");
header("Pragma: no-cache");

$backend = new IndexAdmin();

// Проверка сессии для защиты от xss
// if(!$backend->request->check_session()) {
//     unset($_POST);
//     trigger_error('Session expired', E_USER_WARNING);
// }

print $backend->fetch();