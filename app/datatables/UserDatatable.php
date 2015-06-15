<?php
require_once HELPERS_PATH.'FormHelper.php';
require_once HELPERS_PATH.'Role.php';
require_once 'Datatable.php';
class UserDatatable Extends Datatable{

  public function __construct($parameters){
    parent::__construct($parameters);
    $this->dtColumns = array('id', 'username', 'email', array('person.last_name', 'person.first_name'),'role', 'disable','');
  }

  protected function _findData(){
    /*Realiza la busqueda de los datos en la BD*/
    global $_SQL;
    $qWhere = "";

    if(!Valid::blank($this->dtSearch['value'])){
      $s = $_SQL->escape($this->dtSearch['value']);
      $qWhere = ' WHERE (user.username like "%'.$s.'%" or user.email like "%'.$s.'%" or
                         person.first_name like "%'.$s.'%" or person.last_name like "%'.$s.'%")';
    }
    $qLimit = "";
    if(!Valid::blank($this->dtLength)){
      $qLimit = " LIMIT ".$this->dtLength;
    }
    if(!Valid::blank($this->dtStart)){
      $qLimit .= " OFFSET ".$this->dtStart;
    }
    $qSql = "SELECT user.* FROM ".User::$_table_name.' as user LEFT JOIN people as person on user.person_id = person.id ';
    $qSqlCount = "SELECT count(user.id) as cant FROM ".User::$_table_name.' as user LEFT JOIN people as person on user.person_id = person.id ';
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
    foreach ($this->data as $value) {
      $user = new User($value);
      $person = $user->person();
      $person_name = "";
      if($person)
        $person_name = $person->fullname();
      $res[]= array('id' => $value->id,
                'username' => $value->username,
                'email' => $value->email,
                'person' => $person_name,
                'role' => Role::$roles[$user->role],
                'disable' => $value->disable ? 'Si' :  'No',
                'actions' => $this->buildLinks($value),
             );
    }
    return  json_encode(array_merge($this->infoDataTable, array("data" => $res)));
  }

  private function totalRecords(){
    global $_SQL;
    $qSqlCount = "SELECT count(id) as cant FROM ".User::$_table_name;
    /*Cantidad de items encontrados*/
    $res_count = $_SQL->get_row($qSqlCount);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return 0;            
    }
    return $res_count->cant;
  }
  private function buildLinks($user){
    $img_edit = '<span class="glyphicon glyphicon-edit"></span>';
    $img_del = '<span class="glyphicon glyphicon-remove-sign"></span>';
    $url_edit = Ctrl::getUrl(array('control'=>'user', 'action'=>'edit', 'params'=>array('id'=>$user->id)));
    $url_del = Ctrl::getUrl(array('control'=>'user', 'action'=>'delete', 'params'=>array('id'=>$user->id)));
    $a_edit = FormHelper::link_to($url_edit,$img_edit);
    $a_del = FormHelper::link_to($url_del,$img_del, array('confirm' => 'Confirma que desea eliminar el usuario'));
    $div = '<div class="dt-action">';
    return $div.$a_edit.'</div>'.$div.$a_del.'</div>';
  }
}
?>