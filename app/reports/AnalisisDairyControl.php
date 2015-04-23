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
    //write titulo
    $sheet->mergeCells('A1:G1');
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
    $sheet->mergeCells('A4:E4');
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
          ->setCellValue('C9', $analisis->perdida_costo)
          ->setCellValue('E9', round($costo_total_perdida_vaca, 2));
    
    
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
    $sheet->setCellValue('B12',round($desinf_pre_o, 2))
        ->setCellValue('C12',round($desinf_pre_o1, 2))
        ->setCellValue('B13',round($desinf_pos_o, 2))
        ->setCellValue('C13',round($desinf_pos_o1, 2))
        ->setCellValue('B14',round($costo_tratamiento_mc, 2))
        ->setCellValue('C14',round($costo_tratamiento_mc1, 2))
        ->setCellValue('B15',round($costo_tratamiento_secado, 2))
        ->setCellValue('C15',round($costo_tratamiento_secado1, 2))
        ->setCellValue('B16',round($costo_mantenimiento_maquina, 2))
        ->setCellValue('C16',round($costo_mantenimiento_maquina1, 2))
        ->setCellValue('B17',round($total_erogacion, 2))
        ->setCellValue('C17',round($total_erogacion1, 2));


    //Costo total de la enfermedad (Efecto biológico + Erogaciones por esquema de control)
    $sheet->mergeCells('A19:B19');
    $sheet->setCellValue('A19','Costo total de la enfermedad (Efecto biológico + Erogaciones por esquema de control)')
        ->setCellValue('A20', 'Por Tambo')
        ->setCellValue('B20', 'Por Vaca');

    $costo_total = $total_erogacion + $analisis->perdida_costo;
    $costo_total1 = $total_erogacion1 + ($analisis->perdida_costo / $count_cow);
    $sheet->setCellValue('A21', round($total_erogacion, 2))
        ->setCellValue('B21', round($total_erogacion1, 2));
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
      ->setCellValue('D'.$index, $dc->mc ? 'Si' : 'No')
      ->setCellValue('E'.$index, $dc->liters_milk)
      ->setCellValue('F'.$index, $dc->nop)
      ->setCellValue('G'.$index, DateHelper::db_to_ar($dc->date_dl))
      ->setCellValue('H'.$index, $dc->perdida)
      ->setCellValue('I'.$index, $dc->dml);
      $index++;
    }

 
  }

  public function save($filepath){
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
    $objWriter->save($filepath);
  }
    
}
?>