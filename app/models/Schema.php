<?php 
require_once HELPERS_PATH.'Calculos.php';

class Schema extends Model{
    public static $_table_name = 'schema_controls';

    private $_cant_cow = null;
    private $_cant_cow_mc = null;
    private $_cant_cow_smc = null;

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
          'in_ordenio'
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
       return AnalisisSchema::first(array('conditions' => 
            array("schema_id = ?", $this->id)));
    }

    public function remove_analisis(){
        AnalisisSchema::remove(array('conditions' => 
            array("schema_id = ?", $this->id)));
    }

    public function remove_controls(){
        DairyControl::remove(array('conditions' => 
            array("schema_id = ?", $this->id)));
    }

    public function file_name(){
      return $this->year . '_'. $this->month.'_'.$this->id.'.csv';
    }

    public function path_file(){
        return join(DIRECTORY_SEPARATOR, array(UPLOAD_SCHEMA_PATH, $this->dairy_id, $this->id, $this->file_name()));
    }

    public function folder_path(){
        return join(DIRECTORY_SEPARATOR, array(UPLOAD_SCHEMA_PATH, $this->dairy_id, $this->id)).DIRECTORY_SEPARATOR;
    }

    public function hasFile(){
        return file_exists($this->path_file());
    }

    public function attr_to_json(){
        return $this->attrs;
    }
    public static function last($dairy_id){
        return  self::first(array('conditions' => array('dairy_id =? ',  $dairy_id), 'order' => 'date desc'));
    }

    public function dairy_controls(){
        return DairyControl::where(["conditions" =>["schema_id = ? ", $this->id]]);
    }

    /*Cantidad de animales analizados*/
    public function countCow($force=false){
      if(empty($this->_cant_cow) || $force)
        $this->_cant_cow =  DairyControl::count(['conditions' => ['schema_id =?',$this->id]]);
      return $this->_cant_cow;
    }

    /*Cantidad de animales analizados con MC*/
    public function countCowMC($force=false){
      if(empty($this->_cant_cow_mc) || $force)
        $this->_cant_cow_mc = DairyControl::count(['conditions' => ['schema_id = ? and mc = 0',$this->id]]);
      return $this->_cant_cow_mc;
    }

    /*Cantidad de animales analizados sin MC*/
    public function countCowSMC($force=false){
      if(empty($this->_cant_cow_smc) || $force)
        $this->_cant_cow_smc = DairyControl::count(['conditions' => ['schema_id = ? and mc = 1',$this->id]]);
      return $this->_cant_cow_smc;
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

    /*Realiza el calculo de Perdidas y Erogacines y actualiza o crea el modelo de analisis*/
    public function createAnalisis(){
      $this->remove_analisis();
      $perdida_msc = $this->calculoPerdidaPorMSC();
      $perdida_mc = $this->calculoPerdidaPorMC();
      $perdida_lts = $perdida_msc + $perdida_mc;
      $costo_total_perdida = $perdida_lts * $this->milk_price;

      $desinf_pre_o = Calculos::costo_sellador($this->desinf_pre_o_precio, $this->desinf_pre_o_dias);
      $desinf_pos_o = Calculos::costo_sellador($this->desinf_post_o_precio, $this->desinf_post_o_dias);
      $count_cow_mc = $this->countCowMC();
      $costo_tratamiento_mc = $this->costo_tratamiento_mc() * $count_cow_mc;
      $costo_tratamiento_secado = $this->costo_tratamiento_secado() * $count_cow_mc;    
      $costo_mantenimiento_maquina = $this->costo_mantenimiento_maquina();
      $total_erogacion = $desinf_pre_o + $desinf_pos_o + $costo_tratamiento_mc + $costo_tratamiento_secado + $costo_mantenimiento_maquina;

      $data = array ('schema_id'=>$this->id,
                     'perdida_msc'=>$perdida_msc,
                     'perdida_mc'=>$perdida_mc,
                     'perdida_lts'=>$perdida_lts,
                     'perdida_costo'=>$costo_total_perdida,
                     'costo_desinf_pre_o'=>$desinf_pre_o,
                     'costo_desinf_pos_o'=>$desinf_pos_o,
                     'costo_tratamiento_mc'=>$costo_tratamiento_mc,
                     'costo_tratamiento_secado'=>$costo_tratamiento_secado,
                     'costo_mantenimiento_maquina'=>$costo_mantenimiento_maquina,
                     'costo_total'=>$total_erogacion
                     );
      $as = new AnalisisSchema($data);
      return $as->save();
    }

  }
?>