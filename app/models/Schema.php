<?php
require_once HELPERS_PATH.'Calculos.php';

class Schema extends Model{
    public static $_table_name = 'schema_controls';

    private $_cant_cow = null;
    private $_cant_cow_mc = null;
    private $_cant_cow_smc = null;
    private $_cant_cow_msc = null;
    function __construct($args=null){
        parent::__construct($args);
        $this->valid_cols = array ('date', 'liters_milk', 'dairy_id', 'milk_price',
          'desinf_pre_o_producto',
          'desinf_pre_o_precio',
          'desinf_pre_o_dias',
          'desinf_post_o_producto',
          'desinf_post_o_precio',
          'desinf_post_o_dias',
          'tmc_ab_pomo_cantidad',
          'tmc_ab_pomo_precio',
          'tmc_ab_inyect_cantidad',
          'tmc_ab_inyect_precio',
          'tmc_ai_inyect_cantidad',
          'tmc_ai_inyect_precio',
          'ts_pomo_precio',
          'maquina_control_precio',
          'maquina_control_dias',
          'in_ordenio',
          'filename',
          'filetype'
        );
    }

    public  function is_valid($params=null){
        if ($params != null){
            $copia = clone $this;
            foreach ($params as $key => $value) {
                $copia->$key = $value;
            }
            return $copia->is_valid();
        }
        else{
            $this->validation->present($this, 'date');
            $this->validation->present($this, 'dairy_id');
            $this->validation->minInteger($this, 'in_ordenio', 1);
            return $this->validation->is_valid;
        }
    }

    public function dairy(){
        return Dairy::find($this->dairy_id);
    }

    public function analisis(){
      $analisis =  AnalisisSchema::first(array('conditions' =>
            array("schema_id = ?", $this->id)));
      if(!$analisis)
        $analisis = $this->createAnalisis();
      return $analisis;
    }

    public function remove_analisis(){
        AnalisisSchema::remove(array('conditions' =>
            array("schema_id = ?", $this->id)));
    }

    public function remove_controls(){
        DairyControl::remove(array('conditions' =>
            array("schema_id = ?", $this->id)));
    }

    public function remove_erogaciones(){
        Erogacion::remove(array('conditions' =>
            array("schema_id = ?", $this->id)));
    }

    public function file_name(){
      return $this->filename;
      //return $this->year . '_'. $this->month.'_'.$this->id.'.csv';
    }

    public function path_file(){
        return join(DIRECTORY_SEPARATOR, array(UPLOAD_SCHEMA_PATH, $this->dairy_id, $this->id, $this->file_name()));
    }

    public function folder_path(){
      $d = UPLOAD_SCHEMA_PATH . DIRECTORY_SEPARATOR . $this->dairy_id;
      if(!file_exists($d)){
        mkdir($d, 0777, true);
        chmod($d, 0777);
     }
      $s = $d . DIRECTORY_SEPARATOR . $this->id;
      if(!file_exists($s)){
        mkdir($s, 0777, true);
        chmod($d, 0777);
      }
      return join(DIRECTORY_SEPARATOR, array(UPLOAD_SCHEMA_PATH, $this->dairy_id, $this->id)).DIRECTORY_SEPARATOR;
    }

    public function hasFile(){
        return !Valid::blank($this->file_name()) && file_exists($this->path_file());
    }

    public static function last($dairy_id){
        return  self::first(array('conditions' => array('dairy_id =? ',  $dairy_id), 'order' => 'date desc'));
    }

    public function dairy_controls(){
        return DairyControl::where(array("conditions" =>array("schema_id = ? ", $this->id)));
    }

    public function erogaciones(){
        return Erogacion::where(array("conditions" =>array("schema_id = ? ", $this->id)));
    }

    /*Cantidad de animales analizados*/
    public function countCow($force=false){
      if(empty($this->_cant_cow) || $force)
        $this->_cant_cow =  DairyControl::count(array('conditions' => array('schema_id =?',$this->id)));
      return $this->_cant_cow;
    }

    /*Cantidad de animales analizados con MC*/
    public function countCowMC($force=false){
      if(empty($this->_cant_cow_mc) || $force)
        $this->_cant_cow_mc = DairyControl::count(array('conditions' => array('schema_id = ? and mc = 0 and (baja is null || (baja != ? and baja != ?))',$this->id, DairyControl::BAJA_MUERTE, DairyControl::BAJA_DESCARTE)));
      return $this->_cant_cow_mc;
    }
    /*Animales analizados con MC*/
    public function cowMC(){
      return DairyControl::where(array('conditions' => array('schema_id = ? and mc = 0 and (baja is null || (baja != ? and baja != ?))',$this->id, DairyControl::BAJA_MUERTE, DairyControl::BAJA_DESCARTE)));
    }

    /*Cantidad de animales analizados sin MC*/
    public function countCowSMC($force=false){
      if(empty($this->_cant_cow_smc) || $force)
        $this->_cant_cow_smc = DairyControl::count(array('conditions' => array('schema_id = ? and mc = 1',$this->id)));
      return $this->_cant_cow_smc;
    }

    /*Cantidad de animales analizados con MC*/
    public function countCowMSC($umbral, $force=false){
      if(empty($this->_cant_cow_msc) || $force)
        $this->_cant_cow_msc = DairyControl::count(array('conditions' => array('schema_id = ? and rcs > ?',$this->id, $umbral)));
      return $this->_cant_cow_msc;
    }

    /*Total de perdidas diarias por RCS*/
  /*  public function calculoPerdidaDiariaRCS(){
      global $_SQL;
      $query = sprintf("SELECT SUM(dml) as suma FROM %s WHERE schema_id = '%s'", DairyControl::$_table_name, $this->id);
      $res_sum = $_SQL->get_row($query);
      if ($_SQL->last_error != null) {
        $this->fatal_error($_SQL->last_error);
        return NULL;
      }
      return floatval($res_sum->suma);
    }*/
    /*total de perdidas diarias si no tuvo MC*/
    /*public function calculoPerdidaDiariaSinTenerMC(){
      global $_SQL;
      $query = sprintf("SELECT SUM(dml) as suma FROM %s WHERE schema_id = '%s' and mc = 0", DairyControl::$_table_name, $this->id);
      $res_sum = $_SQL->get_row($query);
      if ($_SQL->last_error != null) {
        $this->fatal_error($_SQL->last_error);
        return NULL;
      }
      return floatval($res_sum->suma);
    }*/

    /*1 Perdida por MSC. sumatoria de `(rcs-2) * perdida' si mc = 1*/
    public function calculoPerdidaPorMSC(){
      global $_SQL;
      $query = sprintf("SELECT SUM(dml) as suma FROM %s WHERE schema_id = '%s' and mc = 1", DairyControl::$_table_name, $this->id);
      $res_sum = $_SQL->get_row($query);
      if ($_SQL->last_error != null) {
        $this->fatal_error($_SQL->last_error);
        return NULL;
      }
      return floatval($res_sum->suma);
    }
    /*2 Perdida por MC. sumatoria de `perdida' si mc = 0*/
    public function calculoPerdidaPorMC(){
      global $_SQL;
      $query = sprintf("SELECT SUM(liters_milk) as suma FROM %s WHERE schema_id = '%s' and mc = 0", DairyControl::$_table_name, $this->id);
      $res_sum = $_SQL->get_row($query);
      if ($_SQL->last_error != null) {
        $this->fatal_error($_SQL->last_error);
        return NULL;
      }
      return floatval($res_sum->suma);
    }

    /*Costo del tratamiento por mastitis clinica
      Suma de antibioticos * cantidad de vacas
    */
    public function costo_tratamiento_mc(){
      //$cmc = $this->countCowMC();
      //if(empty($cmc) || $cmc == 0) return 0;
      $pomo_intra = $this->tmc_ab_pomo_precio; //Calculos::mult($this->tmc_ab_pomo_precio, $this->tmc_ab_pomo_cantidad);
      $antib_iny = $this->tmc_ab_inyect_precio; //Calculos::mult($this->tmc_ab_inyect_precio, $this->tmc_ab_inyect_cantidad);
      $antiinf_iny = $this->tmc_ai_inyect_precio; //Calculos::mult($this->tmc_ai_inyect_precio, $this->tmc_ai_inyect_cantidad);
      return ($pomo_intra + $antib_iny + $antiinf_iny);// * $cmc;
    }

    /*El precio del antibiótico x4 x(número de vacas en ordeñe/365)*/
    public function costo_tratamiento_secado(){
      return $this->ts_pomo_precio * 4 * ($this->in_ordenio / 365);
    }

    /*El precio del chequeo/la frecuencia con que realiza el chequeo (en días)*/
    public function costo_mantenimiento_maquina(){
      if(Valid::blank($this->maquina_control_dias)) return 0;
      return $this->maquina_control_precio / $this->maquina_control_dias;
    }

    /*Listado de dairy_control con mc = 1 ordenado de menor a mayor por perdida
      para ser usado en histograma de Distribucion de perdida de leche por MSC */
    function getDataDistrPerdidaMSC(){
      global $_SQL;
      $query = sprintf("SELECT * FROM %s as dc WHERE schema_id = '%s' and mc = 1 order by dml asc", DairyControl::$_table_name, $this->id);
      $res = $_SQL->get_results($query);
      if ($_SQL->last_error != null) {
        throw new Exception($_SQL->last_error, 1);
      }
      $arr = array();
      if($res){
        foreach ($res as $value) {
          $arr[] = new DairyControl($value);
        }
      }
      return $arr;
    }

    /*Realiza el calculo de Perdidas y Erogaciones y actualiza o crea el modelo de analisis*/
    public function createAnalisis(){
      $this->remove_analisis();
      $perdida_msc = $this->calculoPerdidaPorMSC();
      $perdida_mc = $this->calculoPerdidaPorMC();
      $perdida_lts = $perdida_msc + $perdida_mc;
      $costo_total_perdida = $perdida_lts * $this->milk_price;
      $desinf_pre_o = Calculos::costo_sellador($this->desinf_pre_o_precio, $this->desinf_pre_o_dias);
      $desinf_pos_o = Calculos::costo_sellador($this->desinf_post_o_precio, $this->desinf_post_o_dias);
      $count_cow_mc = $this->countCowMC();
      $count_cow_smc = $this->countCowSMC();
      $count_cow_msc = $this->countCowMSC(200);
      $costo_tratamiento_mc = $this->costo_tratamiento_mc() * $count_cow_mc;
      $costo_tratamiento_secado = $this->costo_tratamiento_secado() ;//* $count_cow_mc;
      $costo_mantenimiento_maquina = $this->costo_mantenimiento_maquina();

      $costo_extra_mc = $this->calculoExtraPorMC();
      $costo_extra_msc = $this->calculoExtraPorMSC();
      $costo_extra_sin_mc = $this->calculoExtraPorNoMC();
      $costo_extra_tambo = $this->calculoExtraPorTambo();
      $costo_extra_vaca = $this->calculoExtraPorVaca();

      $total_erogacion = $desinf_pre_o + $desinf_pos_o +
                         $costo_tratamiento_mc +
                         $costo_tratamiento_secado +
                         $costo_mantenimiento_maquina +
                         ($costo_extra_mc * $count_cow_mc) +
                         ($costo_extra_msc * $count_cow_msc) +
                         ($costo_extra_sin_mc * $count_cow_smc) +
                         $costo_extra_tambo +
                         ($costo_extra_vaca * $this->in_ordenio);

      $data = array('schema_id'=>$this->id,
                     'perdida_msc'=>round($perdida_msc, 2),
                     'perdida_mc'=>round($perdida_mc, 2),
                     'perdida_lts'=>round($perdida_lts, 2),
                     'perdida_costo'=>round($costo_total_perdida, 2),
                     'costo_desinf_pre_o'=>round($desinf_pre_o, 2),
                     'costo_desinf_pos_o'=>round($desinf_pos_o, 2),
                     'costo_tratamiento_mc'=>round($costo_tratamiento_mc, 2),
                     'costo_tratamiento_secado'=>round($costo_tratamiento_secado, 2),
                     'costo_mantenimiento_maquina'=>round($costo_mantenimiento_maquina, 2),
                     'costo_total'=>round($total_erogacion, 2),
                     'costo_extra_mc' => round($costo_extra_mc,2),
                     'costo_extra_msc' => round($costo_extra_msc,2),
                     'costo_extra_sin_mc' => round($costo_extra_sin_mc,2),
                     'costo_extra_tambo' => round($costo_extra_tambo,2),
                     'costo_extra_vaca' => round($costo_extra_vaca,2)
                     );
      $as = new AnalisisSchema($data);
      $as->save();
      return $as;
    }

    public function createDirectory (){
      $d = UPLOAD_SCHEMA_PATH . DIRECTORY_SEPARATOR . $this->dairy_id;
      $s = $d . DIRECTORY_SEPARATOR . $this->id;
      if(!file_exists($d))
         mkdir($d, 0777, true);
      if(!file_exists($s))
         mkdir($s, 0777, true);
    }

    private function calculoExtraPorMC(){
      $data = Erogacion::getApplyTo_VacasMC($this->id);
      $suma=0;
      if($data && count($data) > 0){
        foreach ($data as $erogacion) {
          $days = $erogacion->days;
          if(!$days) $days = 1;
          $suma += $erogacion->price/$days;
        }
      }
      return $suma;
    }
    private function calculoExtraPorMSC(){
      $data = Erogacion::getApplyTo_VacasMSC($this->id);
      $suma=0;
      if($data && count($data) > 0){
        foreach ($data as $erogacion) {
          $days = $erogacion->days;
          if(!$days) $days = 1;
          $suma += $erogacion->price/$days;
        }
      }
      return $suma;
    }
    private function calculoExtraPorNoMC(){
      $data = Erogacion::getApplyTo_VacasSinMC($this->id);
      $suma=0;
      if($data && count($data) > 0){
        foreach ($data as $erogacion) {
          $days = $erogacion->days;
          if(!$days) $days = 1;
          $suma += $erogacion->price/$days;
        }
      }
      return $suma;
    }
    private function calculoExtraPorTambo(){
      $data = Erogacion::getApplyTo_Tambo($this->id);
      $suma=0;
      if($data && count($data) > 0){
        foreach ($data as $erogacion) {
          $days = $erogacion->days;
          if(!$days) $days = 1;
          $suma += $erogacion->price/$days;
        }
      }
      return $suma;
    }
    private function calculoExtraPorVaca(){
      $data = Erogacion::getApplyTo_Vacas($this->id);
      if($data && count($data) > 0){

      }
      return 0;
    }

    public function delete(){
      $table_name = static::getTableName();
      global $_SQL;
      $_SQL->query("START TRANSACTION");
      $_SQL->query("DELETE FROM ".DairyControl::$_table_name." WHERE schema_id = ".$this->id);
      if ($_SQL->last_error != null) {
        $this->validation->add($_SQL->last_error);
        $_SQL->query("ROLLBACK");
        return NULL;
      }
      $_SQL->query("DELETE FROM ".AnalisisSchema::$_table_name." WHERE schema_id = ".$this->id);
      if ($_SQL->last_error != null) {
        $this->validation->add($_SQL->last_error);
        $_SQL->query("ROLLBACK");
        return NULL;
      }
      $_SQL->query("DELETE FROM ".DairyControl::$_table_name." WHERE schema_id = ".$this->id);
      if ($_SQL->last_error != null) {
        $this->validation->add($_SQL->last_error);
        $_SQL->query("ROLLBACK");
        return NULL;
      }
      $res = $_SQL->query("DELETE FROM ".static::$_table_name." WHERE id = ".$this->id);
      if ($_SQL->last_error != null) {
        $this->validation->add($_SQL->last_error);
        $_SQL->query("ROLLBACK");
        return NULL;
      }
      $_SQL->query("COMMIT");
      return $res;
    }
  }
?>
