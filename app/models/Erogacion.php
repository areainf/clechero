<?php 
class Erogacion extends Model{    
    #EROGACION VALORES POR TAMBO
    public static $APPLY_TO = Array('tambo' => 0, 'vacas' => 1, 'vacas_mc' => 2,
                           'vacas_msc' => 3, 'vacas_sin_mc' => 4);
    public static $_table_name = 'erogaciones';    

    function __construct($args = null){
        parent::__construct($args);
        $this->valid_cols = array('name', 'description',  'schema_id', 'price', 'days','apply_to');
    }

    public  function is_valid(){
        $this->validation->present($this, 'name');
        $this->validation->present($this, 'schema_id');
        $this->validation->present($this, 'price');
        return $this->validation->is_valid;
    }

    public function schema(){
        return Schema::find($this->schema_id);
    }

    public static function getApplyTo_VacasMC($schema_id){
      return static::where(array("conditions" =>array("schema_id = ? and apply_to = ?", $schema_id, static::$APPLY_TO['vacas_mc'])));
    }
    public static function getApplyTo_VacasMSC($schema_id){
      return static::where(array("conditions" =>array("schema_id = ? and apply_to = ?", $schema_id, static::$APPLY_TO['vacas_msc'])));
    }
    public static function getApplyTo_VacasSinMC($schema_id){
      return static::where(array("conditions" =>array("schema_id = ? and apply_to = ?", $schema_id, static::$APPLY_TO['vacas_sin_mc'])));
    }
    public static function getApplyTo_Tambo($schema_id){
      return static::where(array("conditions" =>array("schema_id = ? and (apply_to = ? or apply_to is NULL)", $schema_id, static::$APPLY_TO['tambo'])));
    }
    public static function getApplyTo_Vacas($schema_id){
      return static::where(array("conditions" =>array("schema_id = ? and apply_to = ?", $schema_id, static::$APPLY_TO['vacas'])));
    }
}
?>