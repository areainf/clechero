<?php
require_once HELPERS_PATH.'FormHelper.php';
require_once 'Datatable.php';
class DairiesDatatable Extends Datatable{

  public function __construct($parameters){
    parent::__construct($parameters);
    $this->dtColumns = array('Dairy.id', 'Dairy.number', 'Dairy.name', array('Owner.last_name', 'Owner.first_name'), array('Veterinary.last_name', 'Veterinary.first_name'), 'email', 'phone');
  }

  protected function _findData(){
    /*Realiza la busqueda de los datos en la BD*/
    global $_SQL;
    $qWhere = '';
    if(!Valid::blank($this->dtSearch['value'])){
      $s = $_SQL->escape($this->dtSearch['value']);
      // $user_id = $this->parameters['user_id'];
      $qWhere .= sprintf(" WHERE (Dairy.industria LIKE '%%%s%%' or Dairy.ubicacion LIKE '%%%s%%' or Dairy.name LIKE '%%%s%%' 
                or Dairy.email LIKE '%%%s%%'  or Dairy.phone LIKE '%%%s%%' or 
                Owner.first_name LIKE '%%%s%%' or Owner.last_name LIKE '%%%s%%'
                or Veterinary.first_name LIKE '%%%s%%' or Veterinary.last_name LIKE '%%%s%%')",
      $s,$s,$s,$s,$s,$s,$s,$s,$s);
    }
    $qLimit = "";
    if(!Valid::blank($this->dtLength)){
      $qLimit = " LIMIT ".$this->dtLength;
    }
    if(!Valid::blank($this->dtStart)){
      $qLimit .= " OFFSET ".$this->dtStart;
    }
    $qSql = sprintf("SELECT Dairy.* FROM %s AS Dairy LEFT JOIN %s AS Owner ON Dairy.owner_id = Owner.id
             LEFT JOIN %s AS Veterinary ON Dairy.veterinary_id = Veterinary.id ",
             Dairy::$_table_name, Owner::$_table_name, Veterinary::$_table_name);
    $qSqlCount = sprintf("SELECT count(Dairy.id) as cant FROM %s AS Dairy LEFT JOIN %s AS Owner ON Dairy.owner_id = Owner.id
             LEFT JOIN %s AS Veterinary ON Dairy.veterinary_id = Veterinary.id ",
             Dairy::$_table_name, Owner::$_table_name, Veterinary::$_table_name);
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
          $obj = new Dairy($value);
          $own = $obj->Owner;
          $owner_name = "";
          if($own) $owner_name = $own->fullname();
          $vet = $obj->Veterinary;
          $vet_name = "";
          if($vet) $vet_name = $vet->fullname();
          $res[]=array('id' => $value->id,
                  'name' => $value->name,
                  'owner' => $owner_name,
                  'location' => $location,
                  'industry' => $industry,
                  'veterinary' => $vet_name,
                  'email' => $value->email,
                  'phone' => $value->phone,
                  'actions' => $this->buildLinks($value),
                 );
        }
    }
    return  json_encode(array_merge($this->infoDataTable, array("data" => $res)));
  }

  private function totalRecords(){
    global $_SQL;
    $qSqlCount = "SELECT count(id) as cant FROM ".Dairy::$_table_name;
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
    $url_edit = Ctrl::getUrl(array('control'=>'dairy', 'action'=>'edit', 'params'=>array('id'=>$dairy->id)));
    $url_del = Ctrl::getUrl(array('control'=>'dairy', 'action'=>'delete', 'params'=>array('id'=>$dairy->id)));
    $a_edit = FormHelper::link_to($url_edit,$img_edit);
    $a_del = FormHelper::link_to($url_del,$img_del, array('confirm' => 'Confirma que desea eliminar el Tambo'));
    $div = '<div class="dt-action">';
    return $div.$a_edit.'</div>'.$div.$a_del.'</div>';
  }
}
?>