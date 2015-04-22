<?php
require_once HELPERS_PATH.'FormHelper.php';
require_once 'Datatable.php';
class CowDatatable Extends Datatable{
  public $dairy_id;
  public function __construct($parameters){
    parent::__construct($parameters);
    $this->dtColumns = array('cow.id', 'caravana');
  }

  protected function _findData(){
    /*Realiza la busqueda de los datos en la BD*/
    global $_SQL;
    $this->dairy_id = $this->parameters['dairy_id'];
    $qWhere = " WHERE (cow.dairy_id = " . $this->dairy_id . ') ';
    if(!Valid::blank($this->dtSearch['value'])){
      $s = $_SQL->escape($this->dtSearch['value']);
      $qWhere .= sprintf(" and (cow.caravana = %s)",
      $s,$s,$s,$s,$s,$s);
    }
    $qLimit = "";
    if(!Valid::blank($this->dtLength)){
      $qLimit = " LIMIT ".$this->dtLength;
    }
    if(!Valid::blank($this->dtStart)){
      $qLimit .= " OFFSET ".$this->dtStart;
    }
    $qSql = sprintf("SELECT cow.* FROM %s as cow LEFT JOIN %s
      as dairy on dairy.id = cow.id ",
             Cow::$_table_name, Dairy::$_table_name);
    $qSqlCount = sprintf("SELECT count(cow.id) as cant FROM %s as cow LEFT JOIN %s
              as dairy on dairy.id = cow.id ",
              Cow::$_table_name, Dairy::$_table_name);

    $qSql .= $qWhere.$this->_getSqlOrder().$qLimit; 
    error_log($qSql);
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
          $res[]=['id' => $value->id,
                  'caravana' => $value->caravana,
                  'actions' => $this->buildLinks($value),
                 ];
        }
    }
    return  json_encode(array_merge($this->infoDataTable,["data" => $res]));
  }


  private function totalRecords(){
    global $_SQL;
    $qSqlCount = "SELECT count(id) as cant FROM ".DairyControl::$_table_name;
    /*Cantidad de items encontrados*/
    $res_count = $_SQL->get_row($qSqlCount);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return 0;            
    }
    return $res_count->cant;
  }
  private function buildLinks($dairy){
    $img_edit = '<span class="glyphicon glyphicon-edit"></span>';
    $img_del = '<span class="glyphicon glyphicon-remove-sign"></span>';
    $url_edit = Ctrl::getUrl(array('control'=>'cow', 'action'=>'edit', 'params'=>array('id'=>$dairy->id)));
    $url_del = Ctrl::getUrl(array('control'=>'cow', 'action'=>'delete', 'params'=>array('id'=>$dairy->id)));
    $a_edit = FormHelper::link_to($url_edit,$img_edit);
    $a_del = FormHelper::link_to($url_del,$img_del, array('confirm' => 'Confirma que desea eliminar el animal del tambo'));
    $div = '<div class="dt-action">';
    return $div.$a_edit.'</div>'.$div.$a_del.'</div>';
  }
}
?>