<?php

Class AppController Extends BaseController {
  
  function __construct($ctrl) {
     parent::__construct($ctrl);
     $this->use_base_directory=false;
  }
  public function index() {
    $user = Security::current_user();
    if (empty($user))
      $this->render('login');
    else
      $this->render('index');
  }
  public function unauthorized() {
    $this->flash->add('No tiene los permisos suficientes para realizar esta acción');
    $this->render('login');
  }

  public function canExecute($action, $user){
    //return $user != NULL;
    return true;
  }
}

?>
