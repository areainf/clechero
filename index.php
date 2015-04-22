<?php
session_start();
/* Establecer la codificación de caracteres interna a UTF-8 */
mb_internal_encoding("UTF-8");
// define the site path
define ('__SITE_PATH', realpath(dirname(__FILE__)));
include "base/constant.php";
include "base/Config.class.php";
include "base/db_connect.php";
require_once BASE_PATH.'BaseController.class.php';
require_once BASE_PATH.'Ctrl.class.php';
require_once LIB_DIR.'Encriptar.php';
require_once HELPERS_PATH.'Security.php';
require_once HELPERS_PATH.'Logger.php';



global $config;
$config = new Config();

  function __autoload($class_name) {

    $filename = $class_name . '.php';
    $file = MODELS_PATH . $filename; 
    if (file_exists($file) == false){
      $file = BASE_PATH . $filename;
      if (file_exists($file) == false){
          $file = CONTROLLERS_PATH . $filename;
        if (file_exists($file) == false)
            return false;
      }
    }
    include ($file);
  }



// set the timezone
date_default_timezone_set($config->getValue('application')['timezone']);

 /*** error reporting on ***/
error_reporting($config->getValue('application')['error_reporting']);
ini_set("display_errors", $config->getValue('application')['display_errors']);

// require_once MODELS_PATH.'User.php';
// $user = new User(array('username' => 'mmarozzi', 'password' => Encriptar::mycrypt('lospibes')));
// $user->save();

$ctrl = new Ctrl($_SERVER, CONTROLLERS_PATH);
$ctrl->setConfig($config);
$ctrl->loader(); 

$controller = $ctrl->controller;
$action = $ctrl->action;

if (!isset($_SESSION)){
    ini_set('session.gc_maxlifetime', 36000);
}
$user = Security::current_user();
$action_callable = $ctrl->action_callable;
if($action_callable){
	if($controller->canExecute($action, $user)){
		$controller->$action();
	}
	else{
		$controller = new AppController($ctrl);
		$controller->unauthorized();
	}
}
else
	die("NO HAY CONTROLADOR PARA ESTA ACCION $action 404");



?>
