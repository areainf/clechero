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
    $this->_setDairyControlData();
    $this->_setStyles();

    //dejamos activa la hoja 0
    $this->objPHPExcel->setActiveSheetIndex(0);
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
    $sheet->setTitle("General"); //establecer titulo de hoja
    
    $sheet->mergeCells('A1:G1');
    $sheet->mergeCells('A5:E5');
    $sheet->mergeCells('B6:C6');
    $sheet->mergeCells('D6:E6');
    $sheet->mergeCells('A13:C13');
    $sheet->mergeCells('A22:B22');

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
    
    $sheet->setCellValue('A5', 'Análisis del Control Lechero')
        ->setCellValue('B6', 'Para el Tambo')
        ->setCellValue('D6', 'Por Vaca en ordeñe')
        ->setCellValue('B7', 'Litros')
        ->setCellValue('C7', 'Precio')
        ->setCellValue('D7', 'Litros')
        ->setCellValue('E7', 'Precio')
        ->setCellValue('A8', 'Pérdida por MSC')
        ->setCellValue('A9', 'Pérdida por MC')
        ->setCellValue('A10', 'Total de Pérdidas')
        ->setCellValue('A11', 'Efecto biológico de la Enfermedad');
    $analisis = $this->schema->analisis();
    $count_cow = $this->schema->in_ordenio;
    $precio_leche = $this->schema->milk_price;
    $perdida_msc_v = $analisis->perdida_msc / $count_cow;
    $perdida_mc_v = $analisis->perdida_mc / $count_cow;
    $perdida_lts_v = $analisis->perdida_lts / $count_cow;
    $costo_total_perdida_vaca = $analisis->perdida_costo / $count_cow;
    $sheet->setCellValue('B8', $analisis->perdida_msc)
          ->setCellValue('C8', round($analisis->perdida_msc * $precio_leche, 2))
          ->setCellValue('D8', round($perdida_msc_v, 2))
          ->setCellValue('E8', round($perdida_msc_v * $precio_leche, 2))
          ->setCellValue('B9', $analisis->perdida_mc)
          ->setCellValue('C9', round($analisis->perdida_mc * $precio_leche, 2))
          ->setCellValue('D9', round($perdida_mc_v, 2))
          ->setCellValue('E9', round($perdida_mc_v * $precio_leche, 2))
          ->setCellValue('B10', $analisis->perdida_lts)
          ->setCellValue('D10', round($perdida_lts_v, 2))
          ->setCellValue('C11', $analisis->perdida_costo)
          ->setCellValue('E11', round($costo_total_perdida_vaca, 2));
    
    
    //write erogaciones
    $sheet->setCellValue('A13', 'Erogaciones por esquema de control')
        ->setCellValue('B14', 'Para el Tambo')
        ->setCellValue('C14', 'Por vaca en ordeñe')
        ->setCellValue('A15', 'Desinfección Pre-ordeñe')
        ->setCellValue('A16', 'Desinfección Post-ordeñe')
        ->setCellValue('A17', 'Tratamiento MC')
        ->setCellValue('A18', 'Tratamiento al Secado')
        ->setCellValue('A19', 'Costo Mantenimiento Máquina')
        ->setCellValue('A20', 'Erogaciones por esquema de control');

    $desinf_pre_o = $analisis->costo_desinf_pre_o;
    $desinf_pre_o1 = $desinf_pre_o / $count_cow;
    $desinf_pos_o = $analisis->costo_desinf_pos_o;
    $desinf_pos_o1  = $desinf_pos_o / $count_cow;
    $count_cow_mc = $this->schema->countCowMC();
    $costo_tratamiento_mc = $analisis->costo_tratamiento_mc;
    $costo_tratamiento_mc1 = $count_cow_mc == 0 ? 0 : $costo_tratamiento_mc / $count_cow_mc;
    $costo_tratamiento_secado = $analisis->costo_tratamiento_secado;    
    $costo_tratamiento_secado1 = $count_cow_mc == 0 ? 0 : $costo_tratamiento_secado / $count_cow_mc;
    $costo_mantenimiento_maquina = $analisis->costo_mantenimiento_maquina;
    $costo_mantenimiento_maquina1 = $costo_mantenimiento_maquina / $count_cow;
    $total_erogacion = $analisis->costo_total;
    $total_erogacion1 = $desinf_pre_o1 + $desinf_pos_o1 + $costo_tratamiento_mc1 + $costo_tratamiento_secado1 + $costo_mantenimiento_maquina1;    
    $sheet->setCellValue('B15',round($desinf_pre_o, 2))
        ->setCellValue('C15',round($desinf_pre_o1, 2))
        ->setCellValue('B16',round($desinf_pos_o, 2))
        ->setCellValue('C16',round($desinf_pos_o1, 2))
        ->setCellValue('B17',round($costo_tratamiento_mc, 2))
        ->setCellValue('C17',round($costo_tratamiento_mc1, 2))
        ->setCellValue('B18',round($costo_tratamiento_secado, 2))
        ->setCellValue('C18',round($costo_tratamiento_secado1, 2))
        ->setCellValue('B19',round($costo_mantenimiento_maquina, 2))
        ->setCellValue('C19',round($costo_mantenimiento_maquina1, 2))
        ->setCellValue('B20',round($total_erogacion, 2))
        ->setCellValue('C20',round($total_erogacion1, 2));


    //Costo total de la enfermedad (Efecto biológico + Erogaciones por esquema de control)
    $sheet->setCellValue('A22',"Costo total de la Enfermedad")
        ->setCellValue('A23', 'Por Tambo')
        ->setCellValue('B23', 'Por Vaca');
    $costo_total = $total_erogacion + $analisis->perdida_costo;
    $costo_total1 = $total_erogacion1 + ($analisis->perdida_costo / $count_cow);
    $sheet->setCellValue('A24', round($total_erogacion, 2))
        ->setCellValue('B24', round($total_erogacion1, 2));
  }

  private function _setDairyControlData(){
    $this->objPHPExcel->createSheet(1); //crear hoja
    $sheet = $this->objPHPExcel->setActiveSheetIndex(1); //seleccionar hora
    $sheet->setTitle("Vacas"); //establecer titulo de hoja
    $sheet->setCellValue('A1','Nº Vaca')
      ->setCellValue('B1','dl')
      ->setCellValue('C1','rcs')
      ->setCellValue('D1','mc')
      ->setCellValue('E1','Lts. Leche')
      ->setCellValue('F1','Nop')
      ->setCellValue('G1','Fecha Lactancia')
      ->setCellValue('H1','Coeficiente')
      ->setCellValue('I1','DML');
    $dairycontrols = $this->schema->dairy_controls();
    $index = 2;
    foreach ($dairycontrols as $dc) {
      $sheet->setCellValue('A'.$index,$dc->cow_id)
      ->setCellValue('B'.$index, $dc->dl)
      ->setCellValue('C'.$index, $dc->rcs)
      ->setCellValue('D'.$index, $dc->hasMC() ? 'Si' : 'No')
      ->setCellValue('E'.$index, $dc->liters_milk)
      ->setCellValue('F'.$index, $dc->nop)
      ->setCellValue('G'.$index, DateHelper::db_to_ar($dc->date_dl))
      ->setCellValue('H'.$index, $dc->perdida)
      ->setCellValue('I'.$index, $dc->dml);
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
    $sheet->setSharedStyle($titulo, "A5");
    $sheet->getRowDimension(5)->setRowHeight(30);
    $sheet->setSharedStyle($titulo, "A13");
    $sheet->getRowDimension(13)->setRowHeight(30);
    $sheet->setSharedStyle($titulo, "A22");
    $sheet->getRowDimension(22)->setRowHeight(30);
    //ancho automatico de las columnas
    foreach(range('A','G') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
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
    
    $sheet->setSharedStyle($subtitulo, "A6:E7");
    $sheet->setSharedStyle($subtitulo, "A14:C14");

    //pie de tablas
    $footer = new PHPExcel_Style(); //nuevo estilo
    $footer->applyFromArray(
      array('fill' => array( //relleno de color
          'type' => PHPExcel_Style_Fill::FILL_SOLID,
          'color' => array('argb' => 'C9C9C9C9')
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
    //set footers
    $sheet->setSharedStyle($footer, "A11:E11");
    $sheet->setSharedStyle($footer, "A20:C20");
    $sheet->setSharedStyle($footer, "A24:B24");


    //text bold
    $sheet->getStyle("A2:G2")->getFont()->setBold(true);
    $sheet->getStyle("A8:A10")->getFont()->setBold(true);
    $sheet->getStyle("B14:C14")->getFont()->setBold(true);
    $sheet->getStyle("A15:A19")->getFont()->setBold(true);
    $sheet->getStyle("A23:B23")->getFont()->setBold(true);

     //HOJA 1
    $sheet = $this->objPHPExcel->setActiveSheetIndex(1); //seleccionar hora
    $sheet->getStyle("A1:I1")->getFont()->setBold(true);
    //ancho automatico de las columnas
    foreach(range('A','I') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }

  }

  public function save($filepath){
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $objWriter->save($filepath);
  }
    
}
?>