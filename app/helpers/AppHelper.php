<?php 
class AppHelper{
  public static function truncate($str, $size){
    if (empty($str)) return $str;
    return substr($str,0,$size);
  }
}
?>