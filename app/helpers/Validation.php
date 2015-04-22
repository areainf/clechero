<?php
require_once HELPERS_PATH.'Valid.php';
class Validation{
  protected $errors = array();
  public $is_valid = true;

  function present($obj, $field, $message=null){
    if (Valid::blank($obj->$field)){
      $this->errors[$field] = Valid::blank($message) ? "No puede estar vacio" : $message; 
      $this->is_valid = false;
      return false;
    }
    $this->is_valid &= true;
    return true;
  }

  function present_at_least($obj, $fields, $message=null){
    $exist = false;
    foreach ($fields as $field) {
      if (!Valid::blank($obj->$field)){
        $this->is_valid &= true;
        return true;
      }
      # code...
    }
    $fields_name = implode(", ", $fields);
    $this->errors[$fields_name] = Valid::blank($message) ? "No puede estar vacio" : $message; 
    $this->is_valid = false;
    return false;
  }
  
  function unique($obj, $field, $message=null){
    $exist = false;
    $key_for_errors = $field;
    if( is_array($field)){
      $cond = array(implode(" = ? && ", $field) . ' = ?');
      foreach ($field as $key) {
        $cond[] = $obj->$key;
      }
      $key_for_errors = join(', ', $field);
    }
    else
      $cond = array($field.' = ?', $obj->$field);
    $find = $obj::first(array('conditions' => $cond));
    if ($find && $find->id != $obj->id){
      $this->errors[$key_for_errors] = Valid::blank($message) ? "No puede repetirse" : $message; 
      $this->is_valid = false;
      return false;
    }
    $this->is_valid &= true;
    return true;
  }

  function integer($obj, $field, $message=null){
    if (Valid::blank($obj->$field)){
      $this->is_valid &= true;
      return true;
    }
    if(Valid::is_integer($obj->$field)){
      $this->is_valid &= true;
      return true;
    }
    $this->errors[$field] = Valid::blank($message) ? "No es un numero" : $message; 
    $this->is_valid = false;
    return false;
  }
  function minInteger($obj, $field, $min, $message=null){
    if (Valid::blank($obj->$field)){
      $this->errors[$field] = Valid::blank($message) ? "No es un numero mayor que $min" : $message; 
      $this->is_valid = false;
      return false;
    }
    if(Valid::is_integer($obj->$field) && $obj->$field > $min){
      $this->is_valid &= true;
      return true;
    }
    $this->errors[$field] = Valid::blank($message) ? "No es un numero mayor que $min" : $message; 
    $this->is_valid = false;
    return false;
  }

  function date($obj, $field, $message=null){
    if (Valid::blank($obj->$field)){
      $this->is_valid &= true;
      return true;
    }
    if($obj->$field instanceof Datetime){
      $this->is_valid &= true;
      return true;
    }
    try{
      $d = explode('-', $obj->$field);
      if(count($d)== 3 && checkdate ($d[1], $d[2], $d[0])){
        $this->is_valid &= true;
        return true;
      }
    }catch(Exception $e){}
    $this->errors[$field] = Valid::blank($message) ? "Fecha invalida" : $message; 
    $this->is_valid = false;
    return false;
  }

  function add($value, $key = null){
    if($key == null)
      $this->errors[] = $value;
    else
      $this->errors[$key] = $value;
  }
  function getErrors(){
    return $this->errors;
  }
}
?>