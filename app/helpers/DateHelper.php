<?php 
class DateHelper{

  public static function ar_to_db($str_fecha){
    $format1="d-m-Y";
    $format2="d/m/Y";
    $format3="d-m-y";
    $format4="d/m/y";
    if (strrpos($str_fecha, "-") === false && strrpos($str_fecha, "/")===false)
      return null;
    if (strrpos($str_fecha, "-") === false){
      $date1=DateTime::createFromFormat($format2, $str_fecha);
      $f1=$format2;
      $date2=DateTime::createFromFormat($format4, $str_fecha);
      $f2=$format4;
    }
    else{
      $date1=DateTime::createFromFormat($format1, $str_fecha);
      $f1=$format1;
      $date2=DateTime::createFromFormat($format3, $str_fecha); 
      $f2=$format3;
    }
    if($date1 !==false && DateHelper::is_correct_format($date1, $str_fecha, $f1)){
        return $date1->format('Y-m-d');
    }
    if($date2 !==false && DateHelper::is_correct_format($date2, $str_fecha, $f2)){
        return $date2->format('Y-m-d');
    }
    return null;
  }

  public static function db_to_ar($str_fecha){
    $format1="d-m-Y";
    if(empty($str_fecha) || $str_fecha == '0000-00-00') return "";
    try{
      $date = new DateTime($str_fecha);
      return $date->format($format1);
    }
    catch(Exception $e){
      return $str_fecha;
    }
  }

  public static function is_correct_format($fecha, $cadena, $format){
    try{
      return $fecha->format($format) == $cadena;
    }
    catch(Exception $e){
      return false;
    }

  }
}
?>