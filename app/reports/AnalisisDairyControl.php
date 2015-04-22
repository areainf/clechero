<?php

//ajuntar la libreria excel
require_once LIB_DIR.'excel/PHPExcel.php';
class AnalisisDairyControl{
  private $objPHPExcel;
  private $schema;
  function __construct($schema){
    $this->schema = $schema;
    // Create new PHPExcel object
    $this->objPHPExcel = new PHPExcel();
    $this->author();
    $this->_setDataSchema();
  }

  private function author(){
    // Set document properties
    $this->objPHPExcel->getProperties()->setCreator("Control Lechero - UNRC")
                             ->setLastModifiedBy("Control Lechero - UNRC")
                             ->setTitle("Análisis del Control Lechero")
                             ->setSubject("Análisis y Erogaciones")
                             ->setDescription("Resultado del Análisis del Control Lechero..")
                             ->setKeywords("Control Lechero, UNRC")
                             ->setCategory("Informe");
  }

  private function _setDataSchema(){
    $sheet = $this->objPHPExcel->setActiveSheetIndex(0);
    //write titulo
    $sheet->setCellValue('A1', 'Datos Generales')
        ->setCellValue('A2', 'Tambo')
        ->setCellValue('B2', 'Vacas Analizados')
        ->setCellValue('C2', 'Vacas en ordeño')
        ->setCellValue('D2', 'Vacas con MC')
        ->setCellValue('E2', 'Producción')
        ->setCellValue('F2', 'Precio por Litro')
        ->setCellValue('G2', 'Período Evaluado');
    $sheet->setCellValue('A3',$this->schema->dairy()->name)
        ->setCellValue('B3', $this->schema->countCow())
        ->setCellValue('C3', $this->schema->in_ordenio)
        ->setCellValue('D3', $this->schema->countCowMC())
        ->setCellValue('E3', $this->schema->liters_milk)
        ->setCellValue('F3', $this->schema->milk_price)
        ->setCellValue('G3', DateHelper::db_to_ar($this->schema->date));

    //write perdidas
    $sheet->setCellValue('A4', 'Análisis del Control Lechero')
        ->setCellValue('B4', 'Para el Tambo')
        ->setCellValue('C4', 'Por Vaca en ordeñe')
        ->setCellValue('B5', 'Litros')
        ->setCellValue('C5', 'Precio')
        ->setCellValue('D5', 'Litros')
        ->setCellValue('E5', 'Precio')
        ->setCellValue('A6', 'Pérdida por MSC')
        ->setCellValue('A7', 'Pérdida por MC')
        ->setCellValue('A8', 'Total de Pérdidas')
        ->setCellValue('A9', 'Efecto biológico de la Enfermedad');
    $analisis = $this->schema->analisis();
    $count_cow = $this->schema->in_ordenio;
    $precio_leche = $this->schema->milk_price;
    $perdida_msc_v = $analisis->perdida_msc / $count_cow;
    $perdida_mc_v = $analisis->perdida_mc / $count_cow;
    $perdida_lts_v = $analisis->perdida_lts / $count_cow;
    $costo_total_perdida_vaca = $analisis->perdida_costo / $count_cow;
    $sheet->setCellValue('B6', $analisis->perdida_msc)
          ->setCellValue('C6', round($analisis->perdida_msc * $precio_leche, 2))
          ->setCellValue('D6', round($perdida_msc_v, 2))
          ->setCellValue('E6', round($perdida_msc_v * $precio_leche, 2))
          ->setCellValue('B7', $analisis->perdida_mc)
          ->setCellValue('C7', round($analisis->perdida_mc * $precio_leche, 2))
          ->setCellValue('D7', round($perdida_mc_v, 2))
          ->setCellValue('E7', round($perdida_mc_v * $precio_leche, 2))
          ->setCellValue('B8', $analisis->perdida_lts)
          ->setCellValue('D8', round($perdida_lts_v, 2))
          ->setCellValue('B9', $analisis->perdida_costo)
          ->setCellValue('D9', round($costo_total_perdida_vaca, 2));
        

    //write erogaciones
    $sheet->setCellValue('A11', 'Erogaciones por esquema de control')
        ->setCellValue('B11', 'Para el Tambo')
        ->setCellValue('C11', 'Por vaca en ordeñe')
        ->setCellValue('A12', 'Desinfección Pre-ordeñe')
        ->setCellValue('A13', 'Desinfección Post-ordeñe')
        ->setCellValue('A14', 'Tratamiento MC')
        ->setCellValue('A15', 'Tratamiento al Secado')
        ->setCellValue('A16', 'Costo Mantenimiento Máquina')
        ->setCellValue('A17', 'Erogaciones por esquema de control');
    //Costo total de la enfermedad (Efecto biológico + Erogaciones por esquema de control)
    $sheet->setCellValue('A19','Costo total de la enfermedad (Efecto biológico + Erogaciones por esquema de control)')
        ->setCellValue('A20', 'Por Tambo')
        ->setCellValue('B20', 'Por Vaca');

  }
  public function save($filepath){
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $objWriter->save($filepath);
  }
    
}
?>