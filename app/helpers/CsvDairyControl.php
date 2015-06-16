<?php
require_once HELPERS_PATH.'DateHelper.php';
class CsvDairyControl{
  public $file = null;
  public $data = null;
  public $dairy_controls;
  public $count_cattles = 0;
  public $schema;
  public $errors;
  public $count_records_errors;
  public $count_mc;
  public static $delimiter = ',';
  private $valid_fields = array('numero', 'rcs', 'nop', 'del', 'mc', 'litros','fecha_dl', 'evento','fecha_evento');
  private $strict_fields = array('numero', 'rcs', 'nop', 'mc');

  function __construct($schema, $file) {
    $this->file = $file;
    $this->schema = $schema;
    $this->data = array();
    $this->errors = array();
    $this->count_mc = 0;
  }

  public  function parseToArray(){
    /* procesa el archivo csv */
    if(!file_exists($this->file) || !is_readable($this->file)){
      $this->errors[] = "El archivo no existe o no se puede leer";
      return FALSE;
    }
    $header = NULL;
    if (($handle = fopen($this->file, 'r')) !== FALSE){
        while (($row = fgetcsv($handle, 1000, self::$delimiter)) !== FALSE){
          if(!$header){
            $header = $row;
            if(!$this->valid_header($header)){
              $this->errors[] = "La cabecera del archivo no es valida. La misma tiene que tener los siguientes campos: " . implode(', ',$this->valid_fields);
              return FALSE;
            }
          }
          else
            $this->data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    $destination = $this->schema->path_file();
    $this->createDairyDirectory($this->schema->dairy_id, $this->schema->id);
    if (!move_uploaded_file($this->file, $destination)){
        throw new RuntimeException('Failed to move uploaded file.');
    }

    return TRUE;
  }
  
  public function parseToDairyControl(){
    $this->dairy_controls = array();
    foreach ($this->data as $row) { 
      $row_dc = $this->proccessRow($row);           
      $dc = new DairyControl($row_dc);
      if (!$dc->is_valid())
        $this->count_records_errors++;
      if($dc->hasMC())
        $this->count_mc++;
      $this->dairy_controls[] = $dc;
    }
    $this->count_cattles = count($this->dairy_controls);
  }

  private function createDairyDirectory ($dairy_id, $schema_id){
    $d = UPLOAD_SCHEMA_PATH . DIRECTORY_SEPARATOR . $dairy_id;
    $s = $d . DIRECTORY_SEPARATOR . $schema_id;
    if(!file_exists($d))
       mkdir($d, 0777, true);
    if(!file_exists($s))
       mkdir($s, 0777, true);
  }

  private function valid_header($header){
    foreach ($this->strict_fields as $value) {
      if (!in_array ( $value , $header ))
        return FALSE;
    }
    if (!in_array ( 'fecha_dl' , $header ) && !in_array ( 'del' , $header ))
        return FALSE;
    return TRUE;
  }

  private function proccessRow($row){
    $new_row = array();
    foreach ($this->valid_fields as $key) {
      if($key == 'numero'){
        $cow = Cow::findOrCreate($row[$key], $this->schema->dairy_id);
        $new_row['cow_id'] = $cow->id;        
      }
      elseif($key == 'fecha_dl'){
        $new_row['date_dl'] = (array_key_exists($key, $row)) ? DateHelper::ar_to_db($row[$key]) : '';
      }
      elseif($key == 'litros'){
        $new_row['liters_milk'] = (array_key_exists($key, $row)) ? $this->replaceComaPunto($row[$key]) : '';
      }
      elseif($key == 'fecha_evento'){
        $new_row['fecha_baja'] = (array_key_exists($key, $row)) ? DateHelper::ar_to_db($row[$key]) : '';
      }
      elseif($key == 'del'){
        $new_row['dl'] = (array_key_exists($key, $row)) ? $row[$key] : '';
      }
      elseif($key == 'evento'){
        $new_row['baja'] = (array_key_exists($key, $row)) ? $row[$key] : '';
      }
      else
        if( array_key_exists($key, $row))
          $new_row[$key] = $row[$key];
    }
    $new_row['schema_id'] = $this->schema->id;
    return $new_row;
  }

  private function replaceComaPunto($cad){
    return str_replace(',', '.', $cad);
  }
}
?>