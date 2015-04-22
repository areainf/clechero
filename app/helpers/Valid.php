<?php
class Valid{

  static function blank($var){
    if (empty($var)) return true;
    if (is_string($var) && strlen($var) == 0) return true;
    if (is_array($var)&& count($var) == 0) return true;
    return false;
  }
  static function is_integer($var){
    return is_numeric($var);
  }
}
?>