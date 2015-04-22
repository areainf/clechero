<?php
require_once HELPERS_PATH.'FormHelper.php';
require_once 'Datatable.php';
class SchemaDatatable Extends Datatable{
  public $user;
  public $dairies_ids;

  public function __construct($parameters){
    parent::__construct($parameters);
    // $this->dtColumns = array('id', 'date', 'sealant_price', 'sealant_treatment_days',
    //                          'abmc_treatment_price', 'abmc_treatment_days', 'abs_treatment_price',
    //                          'machine_control_price', 'machine_control_days', 'liters_milk', 'dairy_id');
    $this->dtColumns = array('id', 'date', 'desinf_pre_o_producto', 
      'desinf_pre_o_precio', 'desinf_pre_o_dias', 'desinf_post_o_producto', 
      'desinf_post_o_precio', 'desinf_post_o_dias', 'tmc_ab_pomo_cantidad',
      'tmc_ab_pomo_precio', 'tmc_ab_inyect_cantidad', 'tmc_ab_inyect_precio',
      'tmc_ai_inyect_cantidad', 'tmc_ai_inyect_precio', 'ts_pomo_precio',
      'machine_control_price', 'machine_control_days', 'liters_milk', 'dairy_id');
    $this->user = Security::current_user();
    $this->setDairiesIds();
  }

  protected function _findData(){
    if(!$this->dairies_ids){
      $this->infoDataTable['recordsTotal'] = 0;
      $this->infoDataTable['recordsFiltered']= 0;
      return;
    }
    /*Realiza la busqueda de los datos en la BD*/
    global $_SQL;
    $qWhere = 'WHERE( dairy_id in (' . $this->dairies_ids . '))';

    if(!empty($this->dtSearch['value']) && !Valid::blank($this->dtSearch['value'])){
      $s = $_SQL->escape($this->dtSearch['value']);
      $qWhere .= " and (date like  '%$s%')";
    }
    $qLimit = "";
    if(!Valid::blank($this->dtLength)){
      $qLimit = " LIMIT ".$this->dtLength;
    }
    if(!Valid::blank($this->dtStart)){
      $qLimit .= " OFFSET ".$this->dtStart;
    }
    $qSql = "SELECT * FROM ".Schema::$_table_name.' ';
    $qSqlCount = "SELECT count(id) as cant FROM ".Schema::$_table_name.' ';
    $qSql .= $qWhere.$this->_getSqlOrder().$qLimit; 
    $qSqlCount .= $qWhere;

    /*Cantidad de items encontrados*/
    $res_count = $_SQL->get_row($qSqlCount);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return NULL;            
    }
    $this->infoDataTable['recordsTotal'] = $this->totalRecords();
    $this->infoDataTable['recordsFiltered']= $res_count->cant;

    /*Listado de items encontrados*/
    $this->data = $_SQL->get_results($qSql);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return NULL;            
    }
  }
  
  protected function _serializeResult(){
    $res = array();
    if(!empty($this->data)){
        foreach ($this->data as $value) {
          $sch = new Schema($value);
          $res[]=['id' => $value->id,
                  'dairy' => $sch->dairy()->name,
                  'date' => $value->date,
                  'liters_milk' => $value->liters_milk,
                  'milk_price' => $value->milk_price,
                  'desinf_pre_o' => $this->getPrecioDias($value->desinf_pre_o_precio, $value->desinf_pre_o_dias),
                  'desinf_post_o' => $this->getPrecioDias($value->desinf_post_o_precio, $value->desinf_post_o_dias),
                  'tmc_ab_pomo_pc' => $this->getPrecioCantidad($value->tmc_ab_pomo_precio , $value->tmc_ab_pomo_cantidad),
                  'tmc_ab_inyect_pc' => $this->getPrecioCantidad($value->tmc_ab_inyect_precio, $value->tmc_ab_inyect_cantidad),
                  'tmc_ai_inyect_pc' => $this->getPrecioCantidad($value->tmc_ai_inyect_precio, $value->tmc_ai_inyect_cantidad),
                  'ts_pomo_precio' => $this->getPrecio($value->ts_pomo_precio),
                  'machine_control_pd' => $this->getPrecioDias($value->maquina_control_precio, $value->maquina_control_dias),
                  'actions' => $this->buildLinks($value),
                 ];
                 error_log("MAQUINA: ".$value->maquina_control_precio .' '. $value->maquina_control_dias. ' = '.$this->getPrecioDias($value->maquina_control_precio, $value->maquina_control_dias));
          // $res[]=['id' => $value->id,
          //         'dairy' => $sch->dairy()->name,
          //         'date' => $value->date,
          //         'desinf_pre_o_producto' => $value->desinf_pre_o_producto,
          //         'desinf_pre_o_precio' => $value->desinf_pre_o_precio,
          //         'desinf_pre_o_dias' => $value->desinf_pre_o_dias,
          //         'desinf_post_o_producto' => $value->desinf_post_o_producto,
          //         'desinf_post_o_precio' => $value->desinf_post_o_precio,
          //         'desinf_post_o_dias' => $value->desinf_post_o_dias,
          //         'tmc_ab_pomo_cantidad' => $value->tmc_ab_pomo_cantidad,
          //         'tmc_ab_pomo_precio' => $value->tmc_ab_pomo_precio,
          //         'tmc_ab_inyect_cantidad' => $value->tmc_ab_inyect_cantidad,
          //         'tmc_ab_inyect_precio' => $value->tmc_ab_inyect_precio,
          //         'tmc_ai_inyect_cantidad' => $value->tmc_ai_inyect_cantidad,
          //         'tmc_ai_inyect_precio' => $value->tmc_ai_inyect_precio,
          //         'ts_pomo_precio' => $value->ts_pomo_precio,
          //         'machine_control_price' => $value->machine_control_price,
          //         'machine_control_days' => $value->machine_control_days,
          //         'liters_milk' => $value->liters_milk,
          //         'actions' => $this->buildLinks($value),
          //        ];
        }
    }
    return  json_encode(array_merge($this->infoDataTable,["data" => $res]));
  }

  private function totalRecords(){
    global $_SQL;
    $qSqlCount = "SELECT count(id) as cant FROM ".Schema::$_table_name;
    $qWhere = ' dairy_id in (' . $this->dairies_ids .')';
    $qSqlCount .= ' WHERE (' . $qWhere .') ';

    /*Cantidad de items encontrados*/
    $res_count = $_SQL->get_row($qSqlCount);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return 0;            
    }
    return $res_count->cant;
  }
  private function buildLinks($schema){
    $img_edit = '<span class="glyphicon glyphicon-edit"></span>';
    $img_del = '<span class="glyphicon glyphicon-remove-sign"></span>';
    $img_down = '<span class="glyphicon glyphicon-file"></span>';
    $img_cow = '<span class="glyphicon glyphicon-tag"></span>';
    $img_analisis = '<span class="glyphicon glyphicon-stats"></span>';
    $url_edit = Ctrl::getUrl(array('control'=>'schema', 'action'=>'edit', 'params'=>array('id'=>$schema->id)));
    $url_del = Ctrl::getUrl(array('control'=>'schema', 'action'=>'delete', 'params'=>array('id'=>$schema->id)));
    $url_down = Ctrl::getUrl(array('control'=>'schema', 'action'=>'downloadFile', 'params'=>array('id'=>$schema->id)));
    $url_cow = Ctrl::getUrl(array('control'=>'dairycontrol', 'action'=>'index', 'params'=>array('schema_id'=>$schema->id)));
    $url_analisis = Ctrl::getUrl(array('control'=>'dairycontrol', 'action'=>'analisis', 'params'=>array('schema_id'=>$schema->id)));
    $a_edit = FormHelper::link_to($url_edit,$img_edit, array('title' => "Editar Esquema de Control"));
    $a_del = FormHelper::link_to($url_del,$img_del, array('confirm' => 'Confirma que desea eliminar el Esquema', 'title' => 'Eliminar el Esquema de Control del Sistema'));
    $a_cow = FormHelper::link_to($url_cow,$img_cow, array('title' => "Listado de Vacas"));
    $a_analisis = FormHelper::link_to($url_analisis,$img_analisis,  array('title' => "Análisis y Erogaciones"));
    $sch = new Schema($schema);
    if($sch->hasFile())
      $a_down = FormHelper::link_to($url_down,$img_down,  array('title' => "Ver el Archivo con  la información provista por el Control Lechero"));
    else
      $a_down = $img_down;
    $div = '<div class="dt-action">';
    return $div.$a_edit.'</div>'.$div.$a_cow.'</div>'.$div.$a_analisis.'</div>'.$div.$a_down.'</div>'.$div.$a_del.'</div>';
  }

  private function setDairiesIds(){
    $tambos = $this->user->dairies();
    $ids=[];
    foreach ($tambos as $t) {
      $ids[] = $t->id;
    }
    $this->dairies_ids =  implode(',', $ids);  
  }
  private function getPrecioDias($p, $d){
    if (empty($p) || empty($d)) return "";
    return "$".$p." / ".$d;
  }
  private function getPrecioCantidad($p, $c){
    if (empty($p) || empty($c)) return "";
    return "$".$p." c/".$c;
  }
  private function getPrecio($p){
    if (empty($p)) return "";
    return "$".$p;
  }
}
?>