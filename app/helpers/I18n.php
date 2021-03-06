<?php
class I18n{
  private static $attr = array('first_name' => "Nombre", 
                         'last_name'=> "Apellido", 
                         'date' => "Fecha", 
                         'dairy_id' => "Tambo",
                         'in_ordenio' => "Vacas en Ordeño",
                         'tambo' => "Tambo",
                         'vacas' => "Vacas",
                         'vacas_mc' => "Vacas con MC",
                         'vacas_msc' => "Vacas con MSC",
                         'vacas_sin_mc' => "Vacas sin MC");

  /*
   * INPUT: String, claves separadas por coma
   */
  public static function t($strkeys){
    /* Si esta separado por ,<espacio> o , */
    $keys = explode(", ", $strkeys);
    if(count($keys)==0)
      $keys = explode(",", $strkeys);
    $res = array();
    foreach ($keys as $k) {
      if(!empty(static::$attr[$k]))
        $res[] = static::$attr[$k];
      else
        $res[] = $k;

    }
    return implode(', ',$res);
  }
}
?>