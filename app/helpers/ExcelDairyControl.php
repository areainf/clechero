<?php
require_once HELPERS_PATH.'DateHelper.php';
require_once LIB_DIR.'excel/PHPExcel.php';
include  LIB_DIR.'excel/PHPExcel/IOFactory.php';
class ExcelDairyControl{
  public $file = null;
  public $data = null;
  public $dairy_controls;
  public $count_cattles = 0;
  public $schema;
  public $errors;
  public $count_records_errors;
  public $count_mc;
  private $objPHPExcel;
  private $valid_fields = array('numero', 'rcs', 'nop', 'del', 'mc', 'litros','fecha_parto', 'evento','fecha_evento');
  private $strict_fields = array('numero', 'rcs', 'nop', 'mc');
  private $headerKeyColumn = array();


  public static $delimiter = ',';

  function __construct($schema, $file) {
    $this->file = $file;
    $this->schema = $schema;
    $this->data = array();
    $this->errors = array();
    $this->count_mc = 0;
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
  }

  public  function parseToArray(){
    $this->data = array();
    $this->errors = array();
    $this->count_mc = 0;
    try {
        $inputFileType = PHPExcel_IOFactory::identify($this->file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $this->objPHPExcel = $objReader->load($this->file);
    } catch(Exception $e) {
      $emsg = $e->getMessage();
      $this->errors[] = "El archivo no existe o no se puede leer( $emsg)";
      return FALSE;
    }

    //  Get worksheet dimensions
    $sheet = $this->objPHPExcel->getSheet(0); 
    // $highestRow = $sheet->getHighestRow(); 
    // $highestColumn = $sheet->getHighestColumn();

    /*CHECK ROW 0 para ver si estan las columnas necesarias*/
    $rowdata = $sheet->rangeToArray('A1:' . $sheet->getHighestDataColumn() . '1', NULL, TRUE, FALSE);
    $header =$rowdata[0];
    for($i = 0 ; $i < count($header); $i++)
      $header[$i] = strtolower($header[$i]);
    if(!$this->valid_and_buid_header($header)){
        $this->errors[] = "La cabecera del archivo no es valida. La misma tiene que tener los siguientes campos: " . implode(', ',$this->valid_fields);
        return FALSE;
    }
    //AQUI: Cabecera Correcta
    $highestRow = $sheet->getHighestDataRow(); 
    for ($i = 2; $i <= $highestRow; $i++) {
      $value = $sheet->rangeToArray('A'. $i.':' . $sheet->getHighestDataColumn() . $i, NULL, TRUE, FALSE);
      $this->data[] = array_combine($header, $value[0]);
    }
    $destination = $this->schema->folder_path().basename($this->file);
    $destination = $this->schema->path_file();
    $this->createDairyDirectory($this->schema->dairy_id, $this->schema->id);
    if (!move_uploaded_file($this->file, $destination)){
    // if (!copy($this->file, $destination)){
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
  private function valid_and_buid_header($header){
    /*CONVERT TO LOWER*/
    for($i = 0 ; $i < count($header); $i++)
      $header[$i] = strtolower($header[$i]);

    foreach ($this->strict_fields as $value) {
      if (!in_array ( $value , $header ))
        return FALSE;
    }
    if (!in_array ( 'fecha_parto' , $header ) && !in_array ( 'del' , $header ))
        return FALSE;

    // //aca todo esta bien convertir a key => COL(A1, B1....)
    // $this->headerKeyColumn = Array();
    // foreach ($this->valid_fields as $value) {
    //   for($i = 0 ; $i < count($header); $i++){
    //     if( $header[$i] == $value){
    //       $this->headerKeyColumn[$value] =  PHPExcel_Cell::stringFromColumnIndex($i+1)."1";
    //       break;
    //     }
    //   }
    // }
    return TRUE;
  }

  private function proccessRow($row){
    $new_row = array();
    foreach ($this->valid_fields as $key) {
      if($key == 'numero'){
        $cow = Cow::findOrCreate($row[$key], $this->schema->dairy_id);
        $new_row['cow_id'] = $cow->id;        
      }
      elseif($key == 'fecha_parto'){
        $new_row['date_dl'] = (array_key_exists($key, $row)) ? DateHelper::ar_to_db($row[$key]) : '';
      }
      elseif($key == 'litros'){
        $new_row['liters_milk'] = (array_key_exists($key, $row)) ? $this->replaceComaPunto($row[$key]) : '';
      }
      elseif($key == 'del'){
        $new_row['dl'] = $row['del'];
      }
      elseif($key == 'fecha_evento'){
        $new_row['fecha_baja'] = (array_key_exists($key, $row)) ? DateHelper::ar_to_db($row[$key]) : '';
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