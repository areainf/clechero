<?php
require_once MODELS_PATH.'Cow.php';
require_once MODELS_PATH.'Dairy.php';
require_once DATATABLE_PATH.'CowDatatable.php';

class CowController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Tambos";
    $this->loadDairy();
  }

  public function index() {
    $navbar_dairy = Security::current_dairy();
    if($this->ctrl->getValue('dairy_id')){
      $this->registry->dairy = Dairy::find($this->ctrl->getValue('dairy_id'));
      Security::set_dairy($this->registry->dairy);
    }
    elseif ($navbar_dairy != null)
      $this->registry->dairy = $navbar_dairy;
    $this->render('index'); 
  }

  public function index_json() {
    $dt = new CowDatatable($this->getParameters());
    echo $dt->getJsonData();
  }

  // public function add($cow = null){
  //   if(empty($cow))
  //     $this->registry->cow = new Cow(['dairy_id' => $this->registry->dairy->id]);
  //   else
  //     $this->registry->cow = $cow;
  //   $this->render('_form');
  // }

  // public function create(){
  //     $params = $this->getData()['cow'];
      
  //     $cow = new Cow($params);
  //     if ($cow->is_valid() && $cow->save()){
  //       $this->flash->addMessage("Se agrego correctamente el animal al Tambo");
  //       $this->renameAction('index');
  //       return $this->index();
  //     }
  //     else{
  //       $this->flash->addErrors($cow->validation->getErrors()); 
  //       return $this->add($cow);
  //     }
  // }

  public function edit(){
    $id = $this->getParameters('id');
    $cow = Cow::find($id);
    if ($cow){
      $this->registry->cow = $cow;
      $this->registry->dairy = $cow->dairy();
      $this->render('_form');
    }
    else{
      $this->flash->addError("Control No encontrado"); 
      $this->renameAction('index');
      return $this->index();
    }
  }

  // public function update(){
  //     $params = $this->getData()['cow'];
  //     $cow = Cow::find($params['id']);
  //     if($cow){
  //         if ($cow->is_valid($params) && $cow->update_attributes($params)){
  //           $this->flash->addMessage("Se modifico correctamente el control");
  //           $this->renameAction('index');
  //           return $this->index();
  //         }
  //         else{
  //           $this->flash->addErrors($cow->validation->getErrors()); 
  //           $this->registry->cow = $cow;
  //           $this->registry->dairy = $cow->dairy();
  //           $this->render('_form');
  //         }
  //     }
  //     else{
  //       $this->flash->addError("Animal No encontrado"); 
  //       $this->renameAction('index');
  //       return $this->index();
  //     }
  // }

  // public function delete(){
  //     $id = $this->getParameters('id');
  //     $cow = Cow::find($id);
  //     if ($cow){
  //         $this->registry->dairy = $cow->dairy();
  //         $cow->delete();
  //         $this->flash->addErrors($cow->validation->getErrors()); 
  //         $this->registry->cow = $cow;
  //     }
  //     else{
  //       $this->flash->addError("Animal No encontrado"); 
  //     }
  //     $this->renameAction('index');
  //     return $this->index();
  // }
  
//  // public function add($cow = null){
//  //   if(empty($cow))
//  //     $this->registry->cow = new Cow();
//  //   else
//  //     $this->registry->cow = $cow;
//
//  //   $this->render('_form');
//  // }
//
//  // public function create(){
//  //     $params = $this->getData()['cow'];
//  //     $cow = new Cow($params);
//  //     if ($cow->is_valid() && $cow->save()){
//  //       $this->flash->addMessage("Se agrego correctamente el Vacuno");
//  //       $this->renameAction('index');
//  //       return $this->index();
//  //     }
//  //     else{
//  //       $this->flash->addErrors($cow->validation->getErrors()); 
//  //       return $this->add($cow);
//  //     }
//  // }
//
//  // public function edit(){
//
//  //     $id = $this->getParameters('id');
//  //     $cow = Cow::find($id);
//  //     if ($cow){
//  //       $this->registry->cow = $cow;
//  //       $this->render('_form');
//  //     }
//  //     else{
//  //       $this->flash->addError("Vacuno No encontrado");
//  //       $this->renameAction('index');
//  //       return $this->index();
//  //     }
//  // }
//  // public function update(){
//  //     $params = $this->getData()['cow'];
//  //     $cow = Cow::find($params['id']);
//  //     if($cow){
//  //         if ($cow->is_valid($params) && $cow->update_attributes($params)){
//  //           $this->flash->addMessage("Se modifico correctamente el Vacuno");
//  //           $this->renameAction('index');
//  //           return $this->index();
//  //         }
//  //         else{
//  //           $this->flash->addErrors($cow->validation->getErrors()); 
//  //           $this->registry->cow = $cow;
//  //           $this->render('_form');
//  //         }
//  //     }
//  //     else{
//  //       $this->flash->addError("Vacuno No encontrado"); 
//  //       $this->renameAction('index');
//  //       return $this->index();
//  //     }
//  // }
//  // public function delete(){
//  //     $id = $this->getParameters('id');
//  //     $cow = Cow::find($id);
//  //     if ($cow){
//  //         $cow->delete();
//  //         $this->flash->addErrors($cow->validation->getErrors()); 
//  //         $this->registry->cow = $cow;
//  //     }
//  //     else{
//  //       $this->flash->addError("Vacuno No encontrado"); 
//  //     }
//  //     $this->renameAction('index');
//  //     return $this->index();
//  // }

  public function _not_in_control_json(){
    $caravana = $this->getParameters('q');
    $schema = Schema::find($this->getParameters('schema_id'));
    $dairy_id = $schema->dairy_id;
    $result = Cow::where(["conditions" => ["dairy_id = ? and caravana like '%". $caravana."%' and 
      id not in (SELECT cow_id from ".Dairycontrol::$_table_name." WHERE schema_id = ?)", $dairy_id, $schema->id]]);
    $obj_data = array();
    foreach ($result as $key => $value) {
      $obj_data[]=$this->forTokenInput($value);
    }
    echo json_encode($obj_data);
  }

  private function forTokenInput($cow){
    return array('id' => $cow->id, 'caravana' => $cow->caravana);
  }

  private function loadDairy(){
    //primero busca en el post, si no esta en el get
    $dairy_id = $this->getData('cow')['dairy_id'];
    if ($dairy_id == null)
      $dairy_id = $this->getParameters('dairy_id');

    $dairy = Dairy::find($dairy_id);
    $this->registry->dairy = $dairy;
  }

  public function canExecute($action, $user){
    return $user != null && (Security::is_dairy($user) || $user->is_veterinary());
  }
}
?>