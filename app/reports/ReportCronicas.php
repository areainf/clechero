<?php

//ajuntar la libreria excel
require_once LIB_DIR.'excel/PHPExcel.php';
class ReportCronicas{
  private $objPHPExcel;
  private $schema1;
  private $schema2;
  private $map;
  function __construct($schema1, $schema2, $par_dairycontrol){
    $this->schema1 = $schema1;
    $this->schema2 = $schema2;
    $this->map = $par_dairycontrol;
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
    
    $sheet->mergeCells('A1:I1');
    $sheet->mergeCells('A4:A5');
    $sheet->mergeCells('B4:D4');
    $sheet->mergeCells('E4:G4');

    //write titulo
    $sheet->setCellValue('A1', 'LISTADO DE CRÓNICA')
        ->setCellValue('A2', 'Tambo')
        ->setCellValue('B2', 'Control Nº 1')
        ->setCellValue('C2', 'Control Nº 2')
        ->setCellValue('A3',$this->schema1->dairy()->name)
        ->setCellValue('B3', DateHelper::db_to_ar($this->schema1->date))
        ->setCellValue('C3', DateHelper::db_to_ar($this->schema2->date));
  }

  private function _setComparationData(){
    $sheet = $this->objPHPExcel->setActiveSheetIndex(0); //seleccionar hora
    $sheet->setCellValue('B4','Control Lechero Nº 1')
      ->setCellValue('E4','Control Lechero Nº 2')
      ->setCellValue('A4','Número')
      ->setCellValue('B5','RCS (cél/mL x1000)')
      ->setCellValue('C5','Lts. Leche')
      ->setCellValue('D5','Pérdida Diaria')
      ->setCellValue('E5','RCS (cél/mL x1000)')
      ->setCellValue('F5','Lts. Leche')
      ->setCellValue('G5','Pérdida Diaria');
      
    $index = 6;
    foreach ($this->map  as $dcs) {
      $dc1 = $dcs[0];
      $dc2 = $dcs[1];
      $sheet->setCellValue('A'.$index,$dc1->cow_id)
      ->setCellValue('B'.$index, $dc1->rcs)
      ->setCellValue('C'.$index, $dc1->liters_milk)
      ->setCellValue('D'.$index, $dc1->dml)
      ->setCellValue('E'.$index, $dc2->rcs)
      ->setCellValue('F'.$index, $dc2->liters_milk)
      ->setCellValue('G'.$index, $dc2->dml);
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
    foreach(range('A','G') as $columnID) {
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
    
    $sheet->setSharedStyle($subtitulo, "A4:G5");
    //text bold
    $sheet->getStyle("A2:D2")->getFont()->setBold(true);
  }

  public function save($filepath){
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $objWriter->save($filepath);
  }
    
}
?>