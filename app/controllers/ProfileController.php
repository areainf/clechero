<?php
require_once MODELS_PATH.'User.php';

class ProfileController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Perfil de Usuario";
  }

  public function index() {
    $this->render('index'); 
  }
  
  public function edit(){
    $user = Security::current_user();
    $this->render('_form');
  }
  public function update(){
      $params = $this->getData()['person'];
      $user = Security::current_user();
      $person = $user->person();

      if ($person->is_valid($params) && $person->update_attributes($params)&& $user->update_attributes($params)){
        $this->flash->addMessage("Se modifico correctamente el Perfil");
        $this->renameAction('index');
        return $this->index();
      }
      else{
        $this->flash->addErrors($person->validation->getErrors()); 
        $this->render('index');
      }
  }

  public function canExecute($action, $user){
    return $user != NULL;
  }

}
?>