<?php 
require_once HELPERS_PATH.'Validation.php';
class Model{

  protected $errors = array();
  protected static $belongs_to;
  protected $has_manys = array();
  protected $attrs = array();
  protected $attrs_update = array();
  protected $_open_transaction = false;
  protected $validation;
  public static $_last_query;

  public $valid_cols;

  function __construct($args=null){
    $this->validation = new Validation();
    if(!empty($args))
      foreach ($args as $key => $value)
        $this->__set($key, $value);
  }
  /* cuales son los campos en la db que definen la primary key*/
  static function primary_keys(){
    return array('id');
  }

  function getValuesPrimaryKeys(){
    $arr = array();
    $model = get_called_class();
    foreach ($model::primary_keys() as $value) {
      $arr[$value] = static::value_or_null($this->$value);
    }
    return $arr;
  }

  public function __get($name){
    if ($name == 'validation') return $this->validation;
    if(isset($this->attrs[$name]))
      return $this->attrs[$name];
    if($this->is_belongs_to($name))
      return $this->getAssociation($name);
    return  null;
  }

  public function __set($name, $value){
    if ($name != 'validation'){
      $this->attrs_update[$name] = $value;
      $this->attrs[$name] = $value;
    }
    else
        $this->validation = $value;
  }

  function save(){
    // if ($this->isPersistent())
    //   return $this->_update_attributes();
    // else
      return $this->_create();
  }

  function _create(){
    $table_name = static::getTableName();
    $array_info = $this->columns();
    if(isset($this->_type))
      $array_info[] = 'type';

    $info = implode(',', $array_info);
    $this->_last_query = sprintf("INSERT INTO %s ( %s ) values ", $table_name, $info);
    $array_values = array();
    foreach ($this->columns() as $key) {
      array_push($array_values, static::formatField($this->attrs[$key]));
    }
    if(isset($this->_type))
      array_push($array_values, static::formatField($this->_type));
    $this->_last_query .= ' ('.implode(', ', $array_values).')';
    global $_SQL;
    $res = $_SQL->query($this->_last_query);
    if ($_SQL->last_error != null) {
      $this->validation->add($_SQL->last_error);
      return NULL;            
    }
    else{
      $this->id = $_SQL->insert_id;
    }
    return $res;
  }

  function update_attributes($params){
    $table_name = static::getTableName();
    $this->_last_query = sprintf("UPDATE %s set ", $table_name);
    $upd_attr = array();
    foreach ($params as $key => $value) {
      if (in_array($key, $this->valid_cols)){
        if(empty($this->attrs[$key]) || $value != $this->attrs[$key]){
          $upd_attr[$key] = static::formatField($value);
        }
      }
    }
    if(count($upd_attr)>0){
      $sets = implode(', ', array_map(function ($v, $k) { return $k . '=' . $v; }, $upd_attr, array_keys($upd_attr)));
      $this->_last_query .= $sets.' where ('.static::_condition_primary_key($this->getValuesPrimaryKeys()).')';
      global $_SQL;
      $res = $_SQL->query($this->_last_query);
      if ($_SQL->last_error != null) {
        $this->validation->add($_SQL->last_error);
        return NULL;            
      }
      else{
        foreach ($params as $key => $value) {
          $this->$key = $value;
        }
      }
    }
    return true;
  }

  function update_attribute($field, $value){
    $table_name = static::getTableName();
    $this->_last_query = sprintf("UPDATE %s set %s = %s", $table_name,$field, static::formatField($value));
    $this->_last_query .= ' where ('.static::_condition_primary_key($this->getValuesPrimaryKeys()).')';
    global $_SQL;
    $res = $_SQL->query($this->_last_query);
    if ($_SQL->last_error != null) {
      $this->validation->add($_SQL->last_error);
      return NULL;            
    }
    return true;
  }

  function delete(){
    $table_name = static::getTableName();
    $this->_last_query = sprintf("DELETE FROM %s ", $table_name);
    $this->_last_query .= ' where ('.static::_condition_primary_key($this->getValuesPrimaryKeys()).')';


    global $_SQL;
    $res = $_SQL->query($this->_last_query);
    if ($_SQL->last_error != null) {
      $this->validation->add($_SQL->last_error);
      return NULL;            
    }
    return $res;
  }

/*-------------------------------------------------------------------
------------------------          DB QUERYS        ------------------
---------------------------------------------------------------------*/
  static function find($pk){
    $table_name = static::getTableName();
    $model = get_called_class();
    $type = "";
    if(isset($model->_type))
      $type= ' and (type="'.$model->_type.'") ';
    $query = sprintf("SELECT * FROM %s ", $table_name);
    $query .= ' where ('.static::_condition_primary_key($pk).$type.')';
    $model::$_last_query = $query;
    
    global $_SQL;
    $res = $_SQL->get_row($query);
    if ($_SQL->last_error != null) {
      throw new Exception($_SQL->last_error, 1);
    }
    elseif($res == null){
      return null;
    }
    $inst = new $model($res);
    return $inst; 
  }

  static function first($params){
    $table_name = static::getTableName();
    $model = get_called_class();
    $query = sprintf("SELECT * FROM %s ", $table_name);
    $where = static::getWhere($params);
    $order = static::getOrder($params);
    if(isset($model->_type))
      $where .= ' and (type="'.$model->_type.'") ';
    $query .= ' where ('.$where.') ' . $order;
    $model::$_last_query = $query;
    global $_SQL;
    $res = $_SQL->get_row($query);
    if ($_SQL->last_error != null) {
      throw new Exception($_SQL->last_error, 1);
    }
    if($res)
      return new $model($res);
    return null;
  }

  static function where($params = null){
    $table_name = static::getTableName();
    $model = get_called_class();
    $query = sprintf("SELECT * FROM %s ", $table_name);
    $where = static::getWhere($params);
    if(isset($model->_type))
      $where .= ' and (type="'.$model->_type.'") ';

    $query .= ' where ('.$where.') '. static::getOrder($params);
    $model::$_last_query = $query;
    global $_SQL;
    $res = $_SQL->get_results($query);
    if ($_SQL->last_error != null) {
      //$this->errors[] = $_SQL->last_error;
      throw new Exception($_SQL->last_error, 1);
    }
    $arr = array();
    if($res){
      foreach ($res as $value) {
        $arr[] = new $model($value);
      }
    }
    return $arr;
  }

  static function remove($params = null){
    $table_name = static::getTableName();
    $model = get_called_class();
    $query = sprintf("DELETE FROM %s ", $table_name);
    $where = static::getWhere($params);
    if(isset($model->_type))
      $where .= ' and (type="'.$model->_type.'") ';

    $query .= ' where ('.$where.')';

    $model::$_last_query = $query;
    global $_SQL;
    $res = $_SQL->get_results($query);
    if ($_SQL->last_error != null) {
      throw new Exception($_SQL->last_error, 1);
    }
    return TRUE;
  }

  static function count($params = null, $attr='id'){
    $table_name = static::getTableName();
    $model = get_called_class();
    $query = sprintf("SELECT count(%s) as cant FROM %s ", $attr, $table_name);
    $where = static::getWhere($params);
    if(isset($model->_type))
      $where .= ' and (type="'.$model->_type.'") ';

    $query .= ' where ('.$where.')';

    $model::$_last_query = $query;
    global $_SQL;
    $res_count = $_SQL->get_row($query);
    if ($_SQL->last_error != null) {
      $this->fatal_error($_SQL->last_error);
      return NULL;            
    }
    return intval($res_count->cant);
  }

/*-------------------------------------------------------------------
------------------------          PRIVATES         ------------------
---------------------------------------------------------------------*/
  private static function value_or_null($value){
    return (empty($value)) ? null : $value;
  }
  public function isPersistent(){
    foreach ($this->getValuesPrimaryKeys() as $value) {
      if ($value == null)
        return false;
    }
    return true;
  }
  private static function getTableName(){
    $class = get_called_class();
    if (empty($class::$_table_name)){
      return strtolower(get_called_class());
    }
    return  $class::$_table_name;
  }

  /*deberia obtener el tipo de campo en la base de datos,
    y asi determinar si va quote, number*/
  protected static function formatField($field){
    if ($field === null)
      return 'NULL';
    if (is_string($field))
      return "'" . str_replace("'","''",$field) . "'";
    if ($field instanceof Datetime){
      return "'" . $field->format('Y-m-d') . "'";
    }
    return  $field;

  }

  private static function _condition_primary_key($pk){
    $arr = array();
    $model = get_called_class();
    if(is_array($pk)){
      foreach ($pk as $key => $value) {
        $arr[] = "$key = ".static::formatField($value);
      }
      return implode(" and ", $arr);
    }
    return 'id = '.static::formatField($pk);
  
  }

  private static function getWhere($params){
    if (empty($params['conditions']))
      return "1 = 1";
    $conditions = $params['conditions'];
    $query = $conditions[0];
    for ($i=1; $i < count($conditions); $i++) { 
      $query =preg_replace("/\?/", static::formatField($conditions[$i]), $query, 1);
    }
    return $query;

  }
  private static function getOrder($params){
    if (empty($params['order']))
      return "";
    return " ORDER BY ". $params['order'];

  }

  protected function  columns(){
    $res = array();
    foreach (array_keys($this->attrs) as $key) {
      if(in_array($key, $this->valid_cols))
        $res[] = $key;
    }
    return $res;

  }
  private function is_belongs_to($model_name){
    /*
  protected static $belongs_to = array('owner' => array('key'=> 'owner_id', 'table' => 'people'), 
                                  'veterinary' => array('key'=> 'veterinary_id', 'table' => 'people'));
    */

    $class = get_called_class();
    if (empty($class::$belongs_to))
      return false;
    $relation = $class::$belongs_to;
    return isset($relation[$model_name]);
  }

  private function getAssociation($model_name){
    $class = get_called_class();
    $def = $class::$belongs_to[$model_name];
     
    if(is_array($def)){
      if (Valid::blank($this->$def['key'])) return null;
      $table= isset($def['table']) ? $def['table'] : $model_name::getTableName();
      $query = sprintf("SELECT * FROM %s where id = %s", $table, $this->$def['key']);
      $class::$_last_query = $query;
      global $_SQL;
      $res = $_SQL->get_row($query);
      if ($_SQL->last_error != null) {
        $this->errors[] = $_SQL->last_error;
        throw new Exception($_SQL->last_error, 1);
      }
      if($res)
        return new $model_name($res);
      return null;
    }
    else{
      return $model_name::find($def);
    }

  }
}
?>