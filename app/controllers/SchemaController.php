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

  public function compare() {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $schemas_ids = $this->getData('schemas_ids');
      if($schemas_ids){
        return$this->compare_schemas($schemas_ids, $this->getData('umbral'));
      }
      else{
        $this->flash->addError("Debe seleccionar los Esquemas de Control");
        $this->registry->umbral = $this->getData('umbral');
      }
    }
    else
      $this->registry->umbral = 200;  
    $this->registry->dairies = Security::current_user()->dairies();
    $this->render('compare'); 
  }

  public function create(){
      $params = $this->getData()['schema'];
      $schema = new Schema($params);
      if($this->existAndValidFile()){
        if ($schema->is_valid()){
          global $_SQL;
          $_SQL->query("START TRANSACTION");
          if ($schema->save() && $this->loadFile($schema) && $schema->createAnalisis()){
            $_SQL->query("COMMIT");

            //////$this->calculatePerdDML($schema);
            $this->flash->addMessage("Se agrego correctamente el Esquema de Control");
            $this->renameAction('index');
            return $this->index();
          }
          else{
            $_SQL->query("ROLLBACK");
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
      $schema_edit = new Schema($params);
      if($schema){
        if($this->existAndValidFile()){
          if ($schema->is_valid($params)){
            global $_SQL;
            $_SQL->query("START TRANSACTION");
            if ( $schema->update_attributes($params) && $this->loadFile($schema) && $schema->createAnalisis()){
              $_SQL->query("COMMIT");
              $this->flash->addMessage("Se modifico correctamente el Esquema de Control");
              $this->renameAction('index');
              return $this->index();
            }
            else{
              //$schema->delete();?????????
              $_SQL->query("ROLLBACK");
              $this->flash->addError("Ocurrio un error al subir el archivo"); 
              // $this->registry->schema = $schema;
               $this->registry->schema = $schema_edit;
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
      return false;
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
            //si tiene la leche especificada realiza calculos y lo guarda en la bd
            // sino calcula primero cuanto se le va a asignar a cada vaca que no
            // tiene leche y lo guarda despues
            if(!Valid::blank($dc->liters_milk)){
                $total_milk += $dc->liters_milk;
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
            else
              $without_milk[] = $dc;
            
          }
          if($total_milk == 0 && Valid::blank($schema->liters_milk)){
            $this->flash->addError("No se especifico la producción de leche"); 
            return false;
          }
          $prom_sin_mc = ($vacas_sin_mc > 0 && $litros_sin_mc > 0)? $litros_sin_mc / ($vacas_sin_mc * 1.0) : $default_cow_liters;
          $prom_con_mc = ($vacas_con_mc > 0 && $litros_con_mc > 0)? $litros_con_mc / ($vacas_con_mc * 1.0) : $default_cow_liters;
          foreach ($without_milk as $dc) {
            $dc->calculateDL($schema->date);
            if($dc->mc)
              $dc->liters_milk = $prom_con_mc;
            else{
              $dc->liters_milk = $prom_sin_mc;
              $total_milk += $dc->liters_milk;
              $dc->calculatePerdDML();
            }
            if (!$dc->save()){
                $this->flash->addErrors($dc->validation->getErrors()); 
                $this->flash->addError(DairyControl::$_last_query); 
                return false;
            }
          }
           if($total_milk > 0 && Valid::blank($schema->liters_milk))
               $schema->update_attribute("liters_milk",$total_milk);
          // elseif(Valid::blank($schema->liters_milk)){
          //   $this->flash->addError("No se especifico la producción de leche"); 
          //   $commit = false;
          // }
         
          return true;
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

  private function compare_schemas($str_ids,$umbral){
    $ids = explode(',' , $str_ids);    
    $schema1 = Schema::find($ids[0]);
    $schema2 = Schema::find($ids[1]);
    $this->registry->schema1 = $schema1;
    $this->registry->schema2 = $schema2;
    $this->registry->umbral = $umbral;
    //realiza la comparacion
    $dcs1 =  $schema1->dairy_controls();
    $dcs2 =  $schema2->dairy_controls();
    $result = array('sanas' => 0, 'cronicas' => 0, 'nuevas_inf' => 0, 'curadas' => 0, 
                    'noanalizadas1' => 0 , 'noanalizadas2' => 0);
    $map = array();
    $noanalizadas1 = array();
    foreach ($dcs1 as $dc1) {
      $dc2 = null;
      foreach ($dcs2 as $k => $v ) { 
        if($dc1->cow_id == $v->cow_id){
          //encontro la vaca en el segundo control
          $map[] = [$dc1, $v];
          unset($dcs2[$k]);
          $dc2 = $v;
          break;
        }
      }
      if($dc2 != null){
        if($dc1->rcs > $umbral){//si enferma 1 control
          if($dc2->rcs > $umbral)//si cronica
            $result['cronicas']++;
          else
            $result['curadas']++;
        }
        else{
          if($dc2->rcs > $umbral)//si nueva inf
            $result['nuevas_inf']++;
          else
            $result['sanas']++;
        }

      }
      else{
        //no esta la vaca en el segundo control
        $noanalizadas1[] = $dc1;
      }
    }
    $result['noanalizadas1'] = count($noanalizadas1);
    $result['noanalizadas2'] = count($dcs2);
    $this->registry->comparacion = $result;
    $this->registry->map = $map;
    $this->registry->noanalizadas1 = $noanalizadas1;
    $this->registry->noanalizadas2 = $dcs2;

    $this->render('result_compare');

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