<?php

//ajuntar la libreria excel
require_once LIB_DIR.'excel/PHPExcel.php';
class ReportCronicas{
  private $objPHPExcel;
  private $schema1;
  private $schema2;
  private $umbral;
  private $map;
  function __construct($schema1, $schema2, $umbral, $par_dairycontrol){
    $this->schema1 = $schema1;
    $this->schema2 = $schema2;
    $this->umbral = $umbral;
    $this->map = $par_dairycontrol;
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    // Create new PHPExcel object
    $this->objPHPExcel = new PHPExcel();
    $this->author();
    $this->_setDataSchemas();
    $this->_setComparationData();
    $this->_setStyles();
  }

  private function author(){
    // Set document properties
    $this->objPHPExcel->getProperties()->setCreator("Control Lechero - UNRC")
                             ->setLastModifiedBy("Control Lechero - UNRC")
                             ->setTitle("Análisis del Control Lechero")
                             ->setSubject("Listado de Vacas Crónicas")
                             ->setDescription("Listado de Vacas Crónicas")
                             ->setKeywords("Control Lechero, UNRC")
                             ->setCategory("Informe");
  }

  private function _setDataSchemas(){
    $sheet = $this->objPHPExcel->setActiveSheetIndex(0);
    $sheet->setTitle("Crónicas"); //establecer titulo de hoja
    
    // $sheet->mergeCells('A1:K1');
    $sheet->mergeCells('A1:L1');
    $sheet->mergeCells('A4:A5');
    $sheet->mergeCells('B4:F4');
    $sheet->mergeCells('G4:K4');

    //write titulo
    $sheet->setCellValue('A1', 'LISTADO DE CRÓNICA')
        ->setCellValue('A2', 'Tambo')
        ->setCellValue('B2', 'Control Nº 1')
        ->setCellValue('C2', 'Control Nº 2')
        ->setCellValue('D2', 'Umbral')
        ->setCellValue('A3',$this->schema1->dairy()->name)
        ->setCellValue('B3', DateHelper::db_to_ar($this->schema1->date))
        ->setCellValue('C3', DateHelper::db_to_ar($this->schema2->date))
        ->setCellValue('D3',$this->umbral);
        ;
  }

  private function _setComparationData(){
    $sheet = $this->objPHPExcel->setActiveSheetIndex(0); //seleccionar hora
    $sheet->setCellValue('B4','Control Lechero Nº 1')
      ->setCellValue('F4','Control Lechero Nº 2')
      ->setCellValue('A4','Número')
      ->setCellValue('B5','RCS (cél/mL x1000)')
      ->setCellValue('C5','Lts. Leche')
      ->setCellValue('D5','Pérdida Diaria')
      ->setCellValue('E5','NOP')
      ->setCellValue('F5','Fecha Parto')
      ->setCellValue('G5','RCS (cél/mL x1000)')
      ->setCellValue('H5','Lts. Leche')
      ->setCellValue('I5','Pérdida Diaria')
      ->setCellValue('J5','NOP')
      ->setCellValue('K5','Fecha Parto')
      ->setCellValue('L5','Estado');
      
    $index = 6;
    foreach ($this->map  as $dcs) {
      $dc1 = $dcs[0];
      $dc2 = $dcs[1];
      $sheet->setCellValue('A'.$index,$dc1->cow_id)
      ->setCellValue('B'.$index, $dc1->rcs)
      ->setCellValue('C'.$index, $dc1->liters_milk)
      ->setCellValue('D'.$index, $dc1->dml)
      ->setCellValue('E'.$index, $dc1->nop)
      ->setCellValue('F'.$index, DateHelper::db_to_ar($dc1->date_dl))      
      ->setCellValue('G'.$index, $dc2->rcs)
      ->setCellValue('H'.$index, $dc2->liters_milk)
      ->setCellValue('I'.$index, $dc2->dml)
      ->setCellValue('J'.$index, $dc2->nop)
      ->setCellValue('K'.$index, DateHelper::db_to_ar($dc2->date_dl))
      ->setCellValue('L'.$index, $this->textCompare($dc1, $dc2));
      $index++;
    }

 
  }

  private function _setStyles(){
    //inicio estilos
    $titulo = new PHPExcel_Style(); //nuevo estilo
    $titulo->applyFromArray(
      array('alignment' => array( //alineacion
          'wrap' => false,
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
        ),
        'font' => array( //fuente
          'bold' => true,
          'size' => 16
        )
    ));
    $sheet = $this->objPHPExcel->setActiveSheetIndex(0); //seleccionar hora
    $sheet->setSharedStyle($titulo, "A1");
    $sheet->getRowDimension(1)->setRowHeight(30);
    //ancho automatico de las columnas
    // foreach(range('A','K') as $columnID) {
    foreach(range('A','L') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $subtitulo = new PHPExcel_Style(); //nuevo estilo
    $subtitulo->applyFromArray(
      array('fill' => array( //relleno de color
          'type' => PHPExcel_Style_Fill::FILL_SOLID,
          'color' => array('argb' => 'FFCCFFCC')
        ),
        'borders' => array( //bordes
          'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
          'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
          'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
          'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
        ),
        'font' => array( //fuente
          'bold' => true,
          'size' => 12
        )
    ));
    
    // $sheet->setSharedStyle($subtitulo, "A4:K5");
    $sheet->setSharedStyle($subtitulo, "A4:L5");
    //text bold
    $sheet->getStyle("A2:D2")->getFont()->setBold(true);
  }

  public function save($filepath){
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $objWriter->save($filepath);
  }
    
  private function textCompare($dc1, $dc2){
    $result = "";
    if($dc1->rcs > $this->umbral){//si enferma 1 control
      if($dc2->rcs > $this->umbral)//si cronica
        $result = 'Crónica';
      else
        $result = 'Curada';
    }
    else{
      if($dc2->rcs > $this->umbral)//si nueva inf
        $result = 'Nueva Infección';
      else
        $result = 'Sana';
    }
    return $result;
  }
}
?>