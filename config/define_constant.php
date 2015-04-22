<?php

 // /*** include the controller class ***/
 // // include __SITE_PATH . '/application/' . 'controller_base.class.php';

 // /*** include the registry class ***/
 // // include __SITE_PATH . '/application/' . 'registry.class.php';

 // /*** include the router class ***/
 // include __SITE_PATH . '/config/' . 'router.class.php';

 // /*** include the template class ***/
 // // include __SITE_PATH . '/application/' . 'template.class.php';

 // /*** auto load model classes ***/
 //  function __autoload($class_name) {
 //    $filename = strtolower($class_name) . '.class.php';
 //    $file = __SITE_PATH . '/app/models/' . $filename;

 //    if (file_exists($file) == false)
 //        return false;
 //    include ($file);
 //  }
 //  /*** Obtiene el url base del sitio ***/
 //  function _get_url_base(){
 //    return sprintf(
 //      "%s://%s%s",
 //      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
 //      $_SERVER['SERVER_NAME'],
 //      $_SERVER['REQUEST_URI']
 //    );
 //  }
 //  /*** a new registry object ***/
 //  $registry = new Registry();

 //  $registry->_URL_BASE = _get_url_base();

 //  /*** create the database registry object ***/
 //  // $registry->db = db::getInstance();

?>
