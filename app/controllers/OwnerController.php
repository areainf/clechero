<?php
require_once MODELS_PATH.'Owner.php';
require_once DATATABLE_PATH.'OwnerDatatable.php';

class OwnerController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Dueños de Tambos";
  }

  public function index() {
    $this->render('index'); 
  }
  public function index_json() {
    $q = $this->getParameters('q');
    if($q == null){
        $dt = new OwnerDatatable($this->getParameters());
        echo $dt->getJsonData();
    }
    else{
      $q = "%$q%";
      $result = Owner::where(array('conditions' => array("type='owner' and (first_name like ? or last_name like ?)", $q, $q)));
      $obj_data = array();
      foreach ($result as $key => $value) {
        $obj_data[]=$this->forTokenInput($value);
      }
      echo json_encode($obj_data);
    }
  }
  public function add($owner = null){
    if(empty($owner))
      $this->registry->owner = new Owner();
    else
      $this->registry->owner = $owner;

    $this->render('_form');
  }


  public function create(){
      $params = $this->getData()['owner'];
      $owner = new Owner($params);
      if ($owner->is_valid() && $owner->save()){
        $this->flash->addMessage("Se agrego correctamente la persona");
        $this->renameAction('index');
        return $this->index();
      }
      else{
        $this->flash->addErrors($owner->validation->getErrors()); 
        return $this->add($owner);
      }
  }

  public function edit(){

      $id = $this->getParameters('id');
      $owner = Owner::find($id);
      if ($owner){
        $this->registry->owner = $owner;
        $this->render('_form');
      }
      else{
        $this->flash->addError("Persona No encontrada");
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function update(){
      $params = $this->getData()['owner'];
      $owner = Owner::find($params['id']);
      if($owner){
          if ($owner->is_valid($params) && $owner->update_attributes($params)){
            $this->flash->addMessage("Se modifico correctamente la persona");
            $this->renameAction('index');
            return $this->index();
          }
          else{
            $this->flash->addErrors($owner->validation->getErrors()); 
            $this->registry->owner = $owner;
            $this->render('_form');
          }
      }
      else{
        $this->flash->addError("Persona No encontrada"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function delete(){
      $id = $this->getParameters('id');
      $owner = Owner::find($id);
      if ($owner){
          $owner->delete();
          $this->flash->addErrors($owner->validation->getErrors()); 
          $this->registry->owner = $owner;
      }
      else{
        $this->flash->addError("Persona No encontrada"); 
      }
      $this->renameAction('index');
      return $this->index();
  }
  public function canExecute($action, $user){
    if( $user != NULL){
      if(Security::is_veterinary($user)){
        if (in_array($action, ['index', 'new', 'create']))
          return true;
        elseif(in_array($action, ['edit', 'update', 'delete'])){
          $owner = $this->getOwner();
          return $user->it_create_people($owner);
        }
      }
      return true;
    }
    return false;
  }
  private function forTokenInput($owner){
    return array('id' => $owner->id, 'fullname' => $owner->fullname());
  }

  private function getOwner(){
    return Owner::find($this->getParameters('id'));
  }
}
?>