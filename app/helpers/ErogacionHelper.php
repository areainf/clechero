<?php 
require_once "FormHelper.php";
require_once MODELS_PATH."Erogacion.php";

class ErogacionHelper{
  public static function options_apply_to(){
    $hash = array();
    foreach (Erogacion::$APPLY_TO as $key => $value) {
      $hash[$value] = I18n::t($key);
    }
    return FormHelper::options_for($hash);
  }
  public static function i18n_apply_to($val){
    if(!$val)
      $val = 0;
    foreach (Erogacion::$APPLY_TO as $key => $value) {
      if ($val == $value)
        return I18n::t($key);
    }
    return "";
  }
}
?>