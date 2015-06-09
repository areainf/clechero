<?php
require_once MODELS_PATH.'Schema.php';
require_once MODELS_PATH.'DairyControl.php';
require_once DATATABLE_PATH.'SchemaDairiesDatatable.php';
require_once REPORT_PATH.'AnalisisDairyControl.php';

class DairycontrolController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "!!!!! Animales en el Esquema de Control !!!!";
    $this->loadSchema();
  }

  public function index() {
    $this->render('index'); 
  }
  
  public function index_json() {
      $params = $this->getParameters();
      $dt = new SchemaDairiesDatatable($params);
      echo $dt->getJsonData();
  }

  public function analisis() {
    if($this->registry->schema == null){
      if($this->ctrl->getValue('dairy_id')){
        $dairy = Dairy::find($this->ctrl->getValue('dairy_id'));
        $this->registry->schema = $dairy->last_schema();
        $valor = $this->registry->schema;
      }
      else{
        if (Security::current_dairy())
          $this->registry->schema = Security::current_dairy()->last_schema();   
      }
    }
    if($this->registry->schema != null){
      //set current dairy
      Security::set_dairy($this->registry->schema->dairy());
      if (!$this->registry->schema->analisis())
        $this->registry->schema->createAnalisis();
      $this->render('analisis'); 
    }
    else{
      $controller = new AppController($this->ctrl);
      $controller->flash->addError("No hay control lechero para el tambo seleccionado"); 
      $controller->index();
    }
  }
  
  public function analisis_report() {
    $schema = $this->registry->schema;
    $an = new AnalisisDairyControl($schema);
    $name = $schema->id.".xlsx";
    $folderpath = $schema->folder_path().$name;
    $an->save($folderpath);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . $name);
    header('Pragma: no-cache');
    readfile($folderpath);    
  }


  // public function add($dairycontrol = null){
  //   if(empty($dairycontrol))
  //     $this->registry->dairycontrol = new Dairycontrol(['schema_id' => $this->registry->schema->id]);
  //   else
  //     $this->registry->dairycontrol = $dairycontrol;
  //   $this->render('_form');
  // }

  // public function create(){
  //     $params = $this->getData()['dairycontrol'];
      
  //     $dairycontrol = new Dairycontrol($params);
  //     if ($dairycontrol->is_valid() && $dairycontrol->save()){
  //       $this->flash->addMessage("Se agrego correctamente el animal al Control");
  //       $this->renameAction('index');
  //       return $this->index();
  //     }
  //     else{
  //       $this->flash->addErrors($dairycontrol->validation->getErrors()); 
  //       return $this->add($dairycontrol);
  //     }
  // }

  // public function edit(){
  //   $id = $this->getParameters('id');
  //   $dairycontrol = Dairycontrol::find($id);
  //   if ($dairycontrol){
  //     $this->registry->dairycontrol = $dairycontrol;
  //     $this->registry->schema = $dairycontrol->schema();
  //     $this->render('_form');
  //   }
  //   else{
  //     $this->flash->addError("Control No encontrado"); 
  //     $this->renameAction('index');
  //     return $this->index();
  //   }
  // }

  // public function update(){
  //     $params = $this->getData()['dairycontrol'];
  //     $dairycontrol = Dairycontrol::find($params['id']);
  //     if($dairycontrol){
  //         if ($dairycontrol->is_valid($params) && $dairycontrol->update_attributes($params)){
  //           $this->flash->addMessage("Se modifico correctamente el control");
  //           $this->renameAction('index');
  //           return $this->index();
  //         }
  //         else{
  //           $this->flash->addErrors($dairycontrol->validation->getErrors()); 
  //           $this->registry->dairycontrol = $dairycontrol;
  //           $this->registry->schema = $dairycontrol->schema();
  //           $this->render('_form');
  //         }
  //     }
  //     else{
  //       $this->flash->addError("Control No encontrado"); 
  //       $this->renameAction('index');
  //       return $this->index();
  //     }
  // }

  // public function delete(){
  //     $id = $this->getParameters('id');
  //     $dairycontrol = Dairycontrol::find($id);
  //     if ($dairycontrol){
  //         $this->registry->schema = $dairycontrol->schema();
  //         $dairycontrol->delete();
  //         $this->flash->addErrors($dairycontrol->validation->getErrors()); 
  //         $this->registry->dairycontrol = $dairycontrol;
  //     }
  //     else{
  //       $this->flash->addError("Control No encontrado"); 
  //     }
  //     $this->renameAction('index');
  //     return $this->index();
  // }
  private function loadSchema(){
    //primero busca en el post, si no esta en el get
    $schema_id = $this->getData('dairycontrol')['schema_id'];
    if ($schema_id == null)
      $schema_id = $this->getParameters('schema_id');
    if ($schema_id != null){
      $schema = Schema::find($schema_id);
      $this->registry->schema = $schema;
    }
    else
      $this->registry->schema = NULL;
  }

  public function canExecute($action, $user){
    //validar usuario = sechema own
    return $user != null && (Security::is_dairy($user) || $user->is_veterinary());
  }
}
?>