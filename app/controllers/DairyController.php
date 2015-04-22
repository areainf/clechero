<?php
require_once MODELS_PATH.'Dairy.php';
require_once DATATABLE_PATH.'DairyDatatable.php';
require_once DATATABLE_PATH.'DairiesDatatable.php';
class DairyController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Tambos";
  }

  public function index() {
    if (Security::is_dairy())
      $this->render('index_own'); 
    else
      $this->render('index_admin'); 

  }

  public function index_json() {
    $params = $this->getParameters();
    if (Security::is_dairy()){
      $user = Security::current_user();
      $person = $user->person();
      $params['people_id'] = $person->id;
      $dt = new DairyDatatable($params);
    }
    else
      $dt = new DairiesDatatable($params);
    echo $dt->getJsonData();
  }

  public function add($dairy = null){
    if(empty($dairy))
      $this->registry->dairy = new Dairy();
    else
      $this->registry->dairy = $dairy;
    if (Security::is_dairy()){
      $user = Security::current_user();      
      $this->registry->veterinarians = $user->veterinarians();
      $this->render('_form_own');
    }
    else
     die("ERROR");// $this->render('_form_admin');
  }


  public function create(){
      $params = $this->getData()['dairy'];
      if (Security::is_dairy()){
        $user = Security::current_user();
        $owner = $user->person();
        $params['owner_id'] = $owner->id;
      }
      $dairy = new Dairy($params);
      if ($dairy->is_valid() && $dairy->save()){
        $this->flash->addMessage("Se agrego correctamente el Tambo");
        $this->renameAction('index');
        return $this->index();
      }
      else{
        $this->flash->addErrors($dairy->validation->getErrors()); 
        return $this->add($dairy);
      }
  }

  public function edit(){

      $id = $this->getParameters('id');
      $dairy = Dairy::find($id);
      if ($dairy){
        $this->registry->dairy = $dairy;
        $user = Security::current_user();
        if($user->isOwn($dairy)){
          $this->registry->veterinarians = $user->veterinarians();
          $this->render('_form_own');
        }
        elseif(Security::is_admin())
          $this->render('_form_admin');
        else{
          $this->flash->addError("Error !!!");
          $this->renameAction('index');
          return $this->index();  
        }
      }
      else{
        $this->flash->addError("Tambo No encontrado");
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function update(){
      $params = $this->getData()['dairy'];
      $dairy = Dairy::find($params['id']);
      if($dairy){
          if ($dairy->is_valid($params) && $dairy->update_attributes($params)){
            $this->flash->addMessage("Se modifico correctamente el Tambo");
            $this->renameAction('index');
            return $this->index();
          }
          else{
            $this->flash->addErrors($dairy->validation->getErrors()); 
            $this->registry->dairy = $dairy;
            $this->render('_form');
          }
      }
      else{
        $this->flash->addError("Tambo No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function delete(){
      $id = $this->getParameters('id');
      $dairy = Dairy::find($id);
      if ($dairy){
          $dairy->delete();
          $this->flash->addErrors($dairy->validation->getErrors()); 
          $this->registry->dairy = $dairy;
      }
      else{
        $this->flash->addError("Tambo No encontrado"); 
      }
      $this->renameAction('index');
      return $this->index();
  }
  public function canExecute($action, $user){
    return $user != NULL;
  }
}
?>