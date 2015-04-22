<?php

Class error404Controller Extends baseController {
  
  function __construct($ctrl) {
     parent::__construct($ctrl);
     $this->use_base_directory=false;
  }
  public function index() {
  	/*** set a template variable ***/
    $this->flash->addError('Pagina No Encontrada');
  	/*** load the index template ***/
    $this->render('error404');
  }

}
?>
