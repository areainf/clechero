<?php
require_once MODELS_PATH.'Schema.php';
require_once HELPERS_PATH.'CsvDairyControl.php';
require_once HELPERS_PATH.'ExcelDairyControl.php';
require_once DATATABLE_PATH.'SchemaDatatable.php';
require_once REPORT_PATH.'ReportCronicas.php';
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
        return $this->compare_schemas($schemas_ids, $this->getData('umbral'));
      }
      else{
        $this->flash->addError("Debe seleccionar los Esquemas de Control");
        $this->registry->umbral = $this->getData('umbral');
      }
    }
    else
      $this->registry->umbral = 200;
    if($this->ctrl->getValue('dairy_id')){
      $this->registry->dairy = Dairy::find($this->ctrl->getValue('dairy_id'));
      Security::set_dairy($this->registry->dairy);
    }
    else
      $this->registry->dairies = Security::current_user()->dairies();
    $this->render('compare'); 
  }

  public function create(){
      $params = $this->getData()['schema'];
      if($this->existAndValidFile()){
        $params['filename'] = $_FILES['file_data']['name'];
        $params['filetype'] = $_FILES['file_data']['type'];
        $schema = new Schema($params);
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
        $this->registry->schema = new Schema($params);
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
    if ($this->isGet())return $this->index();

      $params = $this->getData()['schema'];
      $schema = Schema::find($params['id']);
      $schema_edit = new Schema($params);
      if($schema){
        if($this->existAndValidFile()){
          $params['filename'] = $_FILES['file_data']['name'];
          $params['filetype'] = $_FILES['file_data']['type'];
          // $schema->filename = $_FILES['file_data']['name'];
          // $schema->filetype = $_FILES['file_data']['type'];
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
            $this->registry->schema = $schema_edit;
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
    header('Content-Type: '. $schema->filetype);
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
    $file = $_FILES['file_data'];
    // $ext = end((explode(".", $file['name'])));
    // $temp = TMP_PATH . $schema->id. '.'.$ext;
    // if (!move_uploaded_file($file['tmp_name'], $temp)){
    //   $this->flash->addError("Ocurrio un error al Intentar subir el Archivo"); 
    //   return false;
    // }
    if($file['type'] == 'text/csv'){
      $fileDC = new CsvDairyControl($schema, $file['tmp_name']);
      if(!$fileDC->parseToArray()){
         $this->flash->addErrors($fileDC->errors);
        return false;
      }
    }
    else{
      $fileDC = new ExcelDairyControl($schema, $file['tmp_name']);
      if(!$fileDC->parseToArray()){
         $this->flash->addErrors($fileDC->errors);
        return false;
      }
    }
    $fileDC->parseToDairyControl();
    if ($fileDC->count_records_errors > 0){
      $this->flash->addError("Ocurrio " . $fileDC->count_records_errors." error/es"); 
      $nro_record = 1;
      foreach ($fileDC->dairy_controls as $dc) {
        if(!$dc->validation->is_valid){
          $this->flash->addErrors(array_merge(["Registro N°: " . $nro_record], $dc->validation->getErrors())); 
        }
        $nro_record++;
      }
      return false;
    }
    $schema->remove_controls();
    $total_milk=0;
    $default_cow_liters = ($schema->in_ordenio) ? ($schema->liters_milk / ($schema->in_ordenio * 1.0 - $fileDC->count_mc)) : 0;
    $litros_nop1 = 0;
    $vacas_nop1 = 0;
    $litros_nop2 = 0;
    $vacas_nop2 = 0;
    $without_milk = [];
    foreach ($fileDC->dairy_controls as $dc) {
      //si tiene la leche especificada realiza calculos y lo guarda en la bd
      // sino calcula primero cuanto se le va a asignar a cada vaca que no
      // tiene leche y lo guarda despues
      if(!Valid::blank($dc->liters_milk)){
        $total_milk += $dc->liters_milk;
        $dc->calculateDL($schema->date);
        if (!$dc->hasMC()){
          $dc->calculatePerdDML();
        }
        if ($dc->nop == 1){
          $litros_nop1 += $dc->liters_milk;
          $vacas_nop1++;
        }
        else{
          $litros_nop2 += $dc->liters_milk;
          $vacas_nop2++; 
        }
        if (!$dc->save()){
            $this->flash->addErrors($dc->validation->getErrors()); 
            return false;
        }
      }
      else
        $without_milk[] = $dc;
    }
    if($total_milk == 0 && Valid::blank($schema->liters_milk)){
      $this->flash->addError("No se especifico la producción de leche"); 
      return false;
    }
    $prom_nop1 = ($vacas_nop1 > 0 && $litros_nop1 > 0)? $litros_nop1 / ($vacas_nop1 * 1.0) : $default_cow_liters;
    $prom_nop2 = ($vacas_nop2 > 0 && $litros_nop2 > 0)? $litros_nop2 / ($vacas_nop2 * 1.0) : $default_cow_liters;
    foreach ($without_milk as $dc) {
      $dc->calculateDL($schema->date);
      $dc->liters_milk = $dc->nop == 1 ? $prom_nop1 : $prom_nop2;            
      if(!$dc->hasMC()){
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
    return true;
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
    $count_analizadas = 0;
    /*Por cada vaca controlada en esquema1*/
    foreach ($dcs1 as $dc1) {
      $dc2 = null;
      /*Busca si esta la vaca en el esquema2*/
      foreach ($dcs2 as $k => $v ) { 
        if($dc1->cow_id == $v->cow_id){
          //encontro la vaca en el segundo control
          // si no tienen mc incluirlas
          if(!$dc1->hasMC() && !$v->hasMC())
            $map[] = [$dc1, $v];
          unset($dcs2[$k]);
          $dc2 = $v;
          break;
        }
      }
      /*Si encontro la vaca en los dos esquemas */
      if($dc2 != null){
        /*Si  no tiene en ninguno de los controles mc*/
        if(!$dc1->hasMC() && !$dc2->hasMC()){
          $count_analizadas++;
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
          /*aca habria que ver si se indica que esta vaca tenia mc*/ 
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
    $this->registry->count_analizadas = $count_analizadas;

    $this->render('result_compare');

  }

  public function downloadCronicas(){
    $schema1 = Schema::find($this->getData('schema_1_id'));
    $schema2 = Schema::find($this->getData('schema_2_id'));
    $umbral = $this->getData('umbral');
    $dcs1 =  $schema1->dairy_controls();
    $dcs2 =  $schema2->dairy_controls();
    $map = array();
    /*Por cada vaca controlada en esquema1*/
    foreach ($dcs1 as $dc1) {
      $dc2 = null;
      /*Busca si esta la vaca en el esquema2*/
      foreach ($dcs2 as $dc2) { 
        if($dc1->cow_id == $dc2->cow_id){
          //encontro la vaca en el segundo control
          // si no tienen mc evaluarla
          if(!$dc1->hasMC() && !$dc2->hasMC()){
            if($dc1->rcs > $umbral && $dc2->rcs > $umbral)//si es cronica
              $map[] = [$dc1, $dc2];
          }
          break;
        }
      }
    }
    $an = new ReportCronicas($schema1, $schema2, $umbral, $map);
    $name = "cronicas_".$schema1->id."_".$schema2->id.".xlsx";
    $schema1->createDirectory();
    $folderpath = $schema1->folder_path().$name;
    $an->save($folderpath);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . $name);
    header('Pragma: no-cache');
    readfile($folderpath);  
  }
  
  /*Params: schema_id1 y schema_id2*/
  /*SI Boton="compare_costo" :Grafica la evolucion de MC, MSC y EROGACIONES*/
  /*SI Boton="compare_indicadores" :Grafica la evolucion de los INDICADORES*/
  public function evolucionEnfermedad(){
    if ($this->isGet())return $this->compare();
    $action = $this->getData('compare_costos') ? 1 : 2;
    $schema1 = Schema::find($this->getData('schema_id1'));
    $schema2 = Schema::find($this->getData('schema_id2'));
    $dairy = $schema1->dairy();

    global $_SQL;
    $query = sprintf("SELECT * FROM %s as dc WHERE dairy_id = %s AND (date BETWEEN '%s' AND '%s') order by date asc", Schema::$_table_name, $dairy->id,$schema1->date, $schema2->date);
    $result = $_SQL->get_results($query);
    if ($_SQL->last_error != null) {
      $this->flash->addError($_SQL->last_error);
      return $this->render('compare'); 
    }
    else{
      $schemas = array();
      //convierto result a Model Schema
      if($result){
        foreach ($result as $value) {
          $schemas[] = new Schema($value);
        }
      }
      if ($action == 1){
        //evolucion de costos
        $this->registry->dairy = $dairy;
        $this->registry->schemas = $schemas;
        return $this->render('result_evolucion_costos');
      }
      else{
        //evolucion de la enfermedad
        return $this->prepareEvolucionEnfermedad($dairy, $schemas);
      }
    }
  }

  private function prepareEvolucionEnfermedad($dairy, $schemas){
    $this->registry->dairy = $dairy;
    // $this->registry->schemas = $schemas;
    $cant = count($schemas);
    $matrix = Array();
    for($i = 0; $i < $cant-1; $i++){
      $schema1=$schemas[$i];
      $schema2=$schemas[$i+1];
      $matrix[$schema2->date] = $this->compareIndicadoresEnfermedad($schema1, $schema2, 200);
    }
    $this->registry->matrix = $matrix;

    return $this->render('result_evolucion_indicadores');
  }

  private function existAndValidFile(){
    $file = $_FILES['file_data'];
    if ($file['error'] == UPLOAD_ERR_NO_FILE)
      return false;
    return ($file['error'] == UPLOAD_ERR_OK);
  }

  private function compareIndicadoresEnfermedad($schema1, $schema2, $umbral){
    $dcs1 =  $schema1->dairy_controls();
    $dcs2 =  $schema2->dairy_controls();
    $sanas = 0;
    $nuevas_inf = 0;
    $cronicas = 0;
    $count_analizadas = 0;

    /*Por cada vaca controlada en esquema1*/
    foreach ($dcs1 as $dc1) {
      $dc2 = null;
      /*Busca si esta la vaca en el esquema2*/
      foreach ($dcs2 as $dc2 ) { 
        if($dc1->cow_id == $dc2->cow_id){
          //encontro la vaca en el segundo control
          // si no tienen mc evaluarlas
          if(!$dc1->hasMC() && !$dc2->hasMC()){
            $count_analizadas++;
            if($dc1->rcs > $umbral){//si enferma 1 control
              if($dc2->rcs > $umbral)//si cronica
                $cronicas++;
            }
            else{
              if($dc2->rcs > $umbral)//si nueva inf
                $nuevas_inf++;
              else
                $sanas++;
            }
          }
          break;
        }
      }
    }
    $result = Array('prevalencia' => 0, 'incidencia' => 0, 'proporcion' =>0);
    if($count_analizadas > 0){
      $result['prevalencia'] = ($cronicas + $nuevas_inf) / $count_analizadas;
      if (($nuevas_inf + $sanas) != 0 ) 
        $result['incidencia'] = $nuevas_inf / ($nuevas_inf + $sanas);
      $result['proporcion'] = $cronicas / $count_analizadas;
    }
    return $result;
  }

  public function canExecute($action, $user){
    return $user != null && (Security::is_dairy($user) || $user->is_veterinary());
  }
}
?>