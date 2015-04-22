<?php
require_once MODELS_PATH.'Schema.php';
require_once HELPERS_PATH.'CsvDairyControl.php';
require_once DATATABLE_PATH.'SchemaDatatable.php';
class SchemaController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Esquema de control";
  }

  public function index() {
    $this->render('index'); 
  }
  public function index_json() {
      $params = $this->getParameters();
      $dt = new SchemaDatatable($params);
      echo $dt->getJsonData();
  }
  public function add($schema = null){
    if(empty($schema))
      $this->registry->schema = new Schema();
    else
      $this->registry->schema = $schema;
    $this->render('_form');
  }

  public function create(){
      $params = $this->getData()['schema'];
      $schema = new Schema($params);
      if($this->existAndValidFile()){
        if ($schema->is_valid() && $schema->save()){
          if ($this->loadFile($schema)){
            //////$this->calculatePerdDML($schema);
            $this->flash->addMessage("Se agrego correctamente el Esquema de Control");
            $this->renameAction('index');
            return $this->index();
          }
          else{
            return $this->add($schema);
          }
        }
        else{
          $this->flash->addErrors($schema->validation->getErrors()); 
          return $this->add($schema);
        }
      }
      else{
        $this->flash->addError("Debe seleccionar el archivo del Control Lechero"); 
        $this->registry->schema = $schema;
        $this->render('_form');
      }
  }

  public function edit(){

      $id = $this->getParameters('id');
      $schema = Schema::find($id);
      if ($schema){
        $this->registry->schema = $schema;
        $this->render('_form');
      }
      else{
        $this->flash->addError("Esquema de Control No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function update(){
      $params = $this->getData()['schema'];
      $schema = Schema::find($params['id']);
      if($schema){
        if($this->existAndValidFile()){
          if ($schema->is_valid($params) && $schema->update_attributes($params)){
            if ($this->loadFile($schema)){
              $this->flash->addMessage("Se modifico correctamente el Esquema de Control");
              $this->renameAction('index');
              return $this->index();
            }
            else{
              $schema->delete();
              $this->flash->addError("Ocurrio un error al subir el archivo"); 
              $this->registry->schema = $schema;
              $this->render('_form');  
            }
          }
          else{
            $this->flash->addErrors($schema->validation->getErrors()); 
            $this->registry->schema = $schema;
            $this->render('_form');
          }
        }
        else{
          $this->flash->addError("Debe seleccionar el archivo del Control Lechero"); 
          $this->registry->schema = $schema;
          $this->render('_form');
        }
      }
      else{
        $this->flash->addError("Esquema de Control No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function delete(){
      $id = $this->getParameters('id');
      $schema = Schema::find($id);
      if ($schema){
          $schema->delete();
          $this->flash->addErrors($schema->validation->getErrors()); 
          $this->registry->schema = $schema;
      }
      else{
        $this->flash->addError("Esquema de Control No encontrado"); 
      }
      $this->renameAction('index');
      return $this->index();
  }

  public function downloadFile(){
    $schema = Schema::find($this->getParameters('id'));
    $name = $schema->file_name();
    $filepath = $schema->path_file();

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . $name);
    header('Pragma: no-cache');
    readfile($filepath);
  }

  public function last_json(){
    $dairy_id = $this->getParameters('dairy_id');
    $schema = Schema::last($dairy_id);
    if ($schema){
      echo json_encode($schema->attr_to_json());
    }
    else{
      echo json_encode("");
    }
  }
  
  private function loadFile($schema){
    $file = $_FILES['dairy_control'];
    if ($file['error']['file_data'] == UPLOAD_ERR_NO_FILE)
      return true;
    elseif($file['error']['file_data'] == UPLOAD_ERR_OK){
      $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
      if(in_array($file['type']['file_data'],$mimes)){
        $csv = new CsvDairyControl($schema, $file['tmp_name']['file_data']);
        if($csv->parseToArray()){
          $csv->parseToDairyControl();
          if ($csv->count_records_errors > 0){
            $this->flash->addError("Ocurrio " . $csv->count_records_errors." error/es"); 
            $nro_record = 1;
            foreach ($csv->dairy_controls as $dc) {
              if(!$dc->validation->is_valid){
                $this->flash->addErrors(array_merge(["Registro N°: " . $nro_record], $dc->validation->getErrors())); 
              }
              $nro_record++;
            }
            return false;
          }
          $commit = true;
          global $_SQL;
          $_SQL->query("START TRANSACTION");
          $schema->remove_controls();
          $total_milk=0;
          // $default_cow_liters = ($csv->count_cattles) ? ($schema->liters_milk / ($csv->count_cattles * 1.0)) : 0;
          $default_cow_liters = ($schema->in_ordenio) ? ($schema->liters_milk / ($schema->in_ordenio * 1.0 - $csv->count_mc)) : 0;
          
          $litros_sin_mc = 0;
          $vacas_sin_mc = 0;
          $litros_con_mc = 0;
          $vacas_con_mc = 0;
          $without_milk = [];
          foreach ($csv->dairy_controls as $dc) {
            if(!Valid::blank($dc->liters_milk)){
              $total_milk += $dc->liters_milk;
              //esta linea estaba en el else
              $dc->calculateDL($schema->date);
              if ($dc->mc){
                $litros_con_mc += $dc->liters_milk;
                $vacas_con_mc++;
              }
              else{
                $litros_sin_mc += $dc->liters_milk;
                $vacas_sin_mc++;
                $dc->calculatePerdDML();
              }
              if (!$dc->save()){
                  $this->flash->addErrors($dc->validation->getErrors()); 
                  $commit = false;
                  break;
              }
            }
            else{
              //$dc->liters_milk = $default_cow_liters;
              $without_milk[] = $dc;
            }
          }
          if($total_milk == 0 && Valid::blank($schema->liters_milk)){
            $this->flash->addError("No se especifico la producción de leche"); 
            $commit = false;
          }
          else{
            $prom_sin_mc = ($vacas_sin_mc > 0 && $litros_sin_mc > 0)? $litros_sin_mc / ($vacas_sin_mc * 1.0) : $default_cow_liters;
            $prom_con_mc = ($vacas_con_mc > 0 && $litros_con_mc > 0)? $litros_con_mc / ($vacas_con_mc * 1.0) : $default_cow_liters;
            foreach ($without_milk as $dc) {
              if($dc->mc)
                $dc->liters_milk = $prom_con_mc;
              else{
                $dc->liters_milk = $prom_sin_mc;
                $total_milk += $dc->liters_milk;
                $dc->calculateDL($schema->date);
                $dc->calculatePerdDML();
              }
              if (!$dc->save()){
                  $this->flash->addErrors($dc->validation->getErrors()); 
                  $this->flash->addError(DairyControl::$_last_query); 
                  $commit = false;
                  break;
              }
            }
          }
           if($total_milk > 0 && Valid::blank($schema->liters_milk))
               $schema->update_attribute("liters_milk",$total_milk);
          // elseif(Valid::blank($schema->liters_milk)){
          //   $this->flash->addError("No se especifico la producción de leche"); 
          //   $commit = false;
          // }
          if ($commit)
            $_SQL->query("COMMIT");
          else
            $_SQL->query("ROLLBACK");
          return $commit;
        }
        else{
          $this->flash->addErrors($csv->errors);
          return false;
        }
      }
      else{
        $this->flash->addError("El archivo no es del tipo correcto, debe ser un archivo CSV, separado por ','");
        return false;
      }
    }
    else{
      $this->addError("Ocurrio un error al cargar el archivo");
      return false;
    }
  }

  // private function calculatePerdDML($schema){
  //   $dcs = $schema->dairy_controls();
  //   foreach ($dcs as $dc) {
  //     $dc->calculatePerdDML();
  //   }
  // }

  private function existAndValidFile(){
    $file = $_FILES['dairy_control'];
    if ($file['error']['file_data'] == UPLOAD_ERR_NO_FILE)
      return false;
    return ($file['error']['file_data'] == UPLOAD_ERR_OK);
  }

  public function canExecute($action, $user){
    return Security::is_dairy($user);
  }
}
?>