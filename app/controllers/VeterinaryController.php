<?php
require_once MODELS_PATH.'Veterinary.php';
require_once DATATABLE_PATH.'VeterinaryDatatable.php';

class VeterinaryController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Veterinarios";
  }

  public function index() {
    // $this->registry->veterinarians = Veterinary::where();
    $this->render('index'); 
  }
  public function index_json() {
    /*****
     q: Parametro cuando se usa tokeninput
    *****/
    $q = $this->getParameters('q');
    if($q == null){
      $params = $this->getParameters();
      if (Security::is_dairy()){
        $user = Security::current_user();
        $person = $user->person();
        $params['people_id'] = $person->id;
      }
      $dt = new VeterinaryDatatable($params);
      echo $dt->getJsonData();
    }
    else{
      $q = "%$q%";
      $result = Veterinary::where(array('conditions' => array("type='veterinary' and (first_name like ? or last_name like ?)", $q, $q)));
      $obj_data = array();
      foreach ($result as $key => $value) {
        $obj_data[]=$this->forTokenInput($value);
      }
      echo json_encode($obj_data);
    }
  }
  public function add($veterinary = null){
    if(empty($veterinary))
      $this->registry->veterinary = new Veterinary();
    else
      $this->registry->veterinary = $veterinary;

    $this->render('_form');
  }

  public function create(){
      $params = $this->getData()['veterinary'];
      $user = Security::current_user();
      $person = $user->person();
      $people_id = null;
      if($person != null )
        $people_id = $person->id;
      $params['created_by'] = $people_id;
      $veterinary = new Veterinary($params);
      if ($veterinary->is_valid() && $veterinary->save()){
        $this->flash->addMessage("Se agrego correctamente el Veterinario");
        $this->renameAction('index');
        return $this->index();
      }
      else{
        $this->flash->addErrors($veterinary->validation->getErrors()); 
        return $this->add($veterinary);
      }
  }

  public function edit(){

      $id = $this->getParameters('id');
      $veterinary = Veterinary::find($id);
      if ($veterinary){
        $this->registry->veterinary = $veterinary;
        $this->render('_form');
      }
      else{
        $this->flash->addError("Veterinario No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function update(){
      $params = $this->getData()['veterinary'];
      $veterinary = Veterinary::find($params['id']);
      if($veterinary){
          if ($veterinary->is_valid($params) && $veterinary->update_attributes($params)){
            $this->flash->addMessage("Se modifico correctamente el verinario");
            $this->renameAction('index');
            return $this->index();
          }
          else{
            $this->flash->addErrors($veterinary->validation->getErrors()); 
            $this->registry->veterinary = $veterinary;
            $this->render('_form');
          }
      }
      else{
        $this->flash->addError("Veterinario No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function delete(){
      $id = $this->getParameters('id');
      $veterinary = Veterinary::find($id);
      if ($veterinary){
          $veterinary->delete();
          $this->flash->addErrors($veterinary->validation->getErrors()); 
          $this->registry->veterinary = $veterinary;
      }
      else{
        $this->flash->addError("Veterinario No encontrado"); 
      }
      $this->renameAction('index');
      return $this->index();
  }

  private function forTokenInput($veterinary){
    return array('id' => $veterinary->id, 'fullname' => $veterinary->fullname());
  }

  public function canExecute($action, $user){
    return $user != NULL;
  }
}
?>