<?php
require_once HELPERS_PATH.'FormHelper.php';
require_once 'Datatable.php';
class OwnerDatatable Extends Datatable{
  private $user = null;
  public function __construct($parameters){
    parent::__construct($parameters);
    $this->dtColumns = array('id', 'first_name', 'last_name', 'email', 'phone','');
    $this->user = Security::current_user();
  }

  protected function _findData(){
    /*Realiza la busqueda de los datos en la BD*/
    global $_SQL;
    $qWhere = ' WHERE (type="owner" and created_by = "'.$this->user->id.'") ';

    if(!Valid::blank($this->dtSearch['value'])){
      $s = $_SQL->escape($this->dtSearch['value']);
      $qWhere .= '  and (first_name like "%'.$s.'%" or last_name like "%'.$s.'%" or email like "%'.$s.'%" or phone like "%'.$s.'%")';
    }
    $qLimit = "";
    if(!Valid::blank($this->dtLength)){
      $qLimit = " LIMIT ".$this->dtLength;
    }
    if(!Valid::blank($this->dtStart)){
      $qLimit .= " OFFSET ".$this->dtStart;
    }
    $qSql = "SELECT * FROM ".Owner::$_table_name.' ';
    $qSqlCount = "SELECT count(id) as cant FROM ".Owner::$_table_name.' ';
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
          $res[] = array('id' => $value->id,
                    'first_name' => $value->first_name,
                    'last_name' => $value->last_name,
                    'email' => $value->email,
                    'phone' => $value->phone,
                    'dairies' => $this->buildDairies($value),
                    'actions' => $this->buildLinks($value),
                   );
        }
    }
    return  json_encode(array_merge($this->infoDataTable, array("data" => $res)));
  }

  private function totalRecords(){
    global $_SQL;
    $qSqlCount = "SELECT count(id) as cant FROM ".Owner::$_table_name;
    /*Cantidad de items encontrados*/
    $res_count = $_SQL->get_row($qSqlCount);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return 0;            
    }
    return $res_count->cant;
  }

  private function buildDairies($value){
    $owner = new Owner($value);
    $cant_dairies = $owner->count_dairies();
    return '<a href="#"><span class="badge">'.$cant_dairies.'</span></a>';
  }

  private function buildLinks($owner){
    $img_edit = '<span class="glyphicon glyphicon-edit"></span>';
    $img_del = '<span class="glyphicon glyphicon-remove-sign"></span>';
    $url_edit = Ctrl::getUrl(array('control'=>'owner', 'action'=>'edit', 'params'=>array('id'=>$owner->id)));
    $url_del = Ctrl::getUrl(array('control'=>'owner', 'action'=>'delete', 'params'=>array('id'=>$owner->id)));
    // $owner_obj = new Owner($owner);    
    // $user = $owner_obj->user();
    // if($user != null){
    //   $url_user = Ctrl::getUrl(array('control'=>'user', 'action'=>'edit', 'params'=>array('id'=>$user->id)));
    //   $img_user = '<span class="glyphicon glyphicon-eye-open"></span>';
    // }
    // else{
    //   $url_user = Ctrl::getUrl(array('control'=>'user', 'action'=>'add', 'params'=>array('user[person_id]'=>$owner->id)));
    //   $img_user = '<span class="glyphicon glyphicon-user"></span>';
    // }
    $a_edit = FormHelper::link_to($url_edit,$img_edit);
    $a_del = FormHelper::link_to($url_del,$img_del, array('confirm' => 'Confirma que desea eliminar el Tambero'));
    // $a_user = FormHelper::link_to($url_user,$img_user);
    $div = '<div class="dt-action">';
    return $div.$a_edit.'</div>'.$div.$a_del.'</div>';
    // return $div.$a_edit.'</div>'.$div.$a_user.'</div>'.$div.$a_del.'</div>';
  }
}
?>


