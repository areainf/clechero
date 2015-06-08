<?php
class Calculos{

  public static $tabla_perdidas = [
                                    0 => [ 0.49,  0.88],
                                    15 => [  0.46,  0.84],
                                    30 => [  0.43,  0.8],
                                    45 => [  0.4, 0.78],
                                    60 => [  0.38,  0.76],
                                    75 => [  0.36,  0.76],
                                    90 => [  0.35,  0.76],
                                    105 => [ 0.34,  0.78],
                                    120 => [ 0.34,  0.81],
                                    135 => [ 0.33,  0.84],
                                    150 => [ 0.33,  0.89],
                                    165 => [ 0.34,  0.95],
                                    180 => [ 0.35,  1.02],
                                    195 => [ 0.36,  1.1],
                                    210 => [ 0.38,  1.19],
                                    225 => [ 0.4, 1.29],
                                    240 => [ 0.42,  1.4],
                                    255 => [ 0.45,  1.52],
                                    270 => [ 0.48,  1.66],
                                    285 => [ 0.52,  1.8],
                                    300 => [ 0.52,  1.8]
                                  ];
  /*
  * Perdidas por dia de lactancia y numero ordinal de parto
  */
  public static function perdidaByDlNop($dl,$nop){
    $key_ant = 0;
    foreach (static::$tabla_perdidas as $key => $value) {
      if($key >= $dl){
        $row = static::$tabla_perdidas[$key_ant];
        return ($nop == 1) ? $row[0] : $row[1];
      }
      $key_ant = $key;
    }
    $row = static::$tabla_perdidas[$key_ant];
    return ($nop == 1) ? $row[0] : $row[1];
  }

  public static function dml($rcs, $perdida){
    $result = (log($rcs) -2) * $perdida;
    if ($result < 0) $result = 0;
    return $result;
  }

  public static function costo_sellador($precio, $dias){
    if (empty($precio) || empty($dias) || floatval($dias)==0.0) return 0;
    return $precio / $dias;
  }
  public static function mult($val1, $val2){
    if (empty($val1) || empty($val2)) return 0;
    return $val1 * $val2;
  }

  public static function divide($val1, $val2){
    if (empty($val2) || $val2 == 0) return 0;
    return $val1 / $val2;
  }

  public static function histrogramCantInterval($count_dairy){
    if($count_dairy < 50)
      return 7;
    if($count_dairy < 75)
      return 10;
    if($count_dairy < 100)
      return 12;
    return 15;
  }
}
?>