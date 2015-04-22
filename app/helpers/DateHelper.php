<?php 
class DateHelper{

  public static function ar_to_db($str_fecha){
    $format1="d-m-Y";
    $format2="d/m/Y";
    $currentf = $format1;
    if (strrpos($str_fecha, "-") === false && strrpos($str_fecha, "/")===false)
      return null;
    if (strrpos($str_fecha, "-") === false)
      $currentf = $format2;
    return DateTime::createFromFormat($currentf, $str_fecha)->format('Y-m-d');
  }
  public static function db_to_ar($str_fecha){
    $format1="d-m-Y";
    try{
      $date = new DateTime($str_fecha);
      return $date->format($format1);
    }
    catch(Exception $e){
      return $str_fecha;
    }
  }
}
?>