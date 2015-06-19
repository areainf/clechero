<?php
require_once HELPERS_PATH.'Valid.php';
require_once HELPERS_PATH.'Calculos.php';
class DairyControl  extends Model{
    const BAJA_SECADO = "secado";
    const BAJA_MUERTE ="muerte";
    const BAJA_DESCARTE ="descarte";
    const BAJA_OTRO ="otro";
    public static $_table_name = 'dairy_control';

    function __construct($args=null){
        parent::__construct($args);
        if($this->nop !='1') $this->nop = '2'; //default value for nop
        $this->valid_cols = array('nop', 'dl',  'rcs', 'mc', 'liters_milk', 'cow_id','schema_id', 'date_dl', 'perdida', 'dml', 'baja', 'fecha_baja');
    }

    public  function is_valid(){
        $this->validation->present($this, 'cow_id');
        $this->validation->present($this, 'schema_id');
        //$this->validation->present($this, 'dl');
        if(!$this->hasMC())
            $this->validation->minInteger($this, 'rcs', 0);
        $this->validation->integer($this, 'liters_milk');
        $this->validation->date($this, 'date_dl');
        $this->validation->date($this, 'fecha_baja');
        $this->validation->presentIn($this, 'baja',array("",null, self::BAJA_SECADO, self::BAJA_MUERTE, self::BAJA_DESCARTE, self::BAJA_OTRO));
        $this->validateNoBloqueada();
        return $this->validation->is_valid;
    }

    /*Antes de crear, si tiene baja, secado o muerte, actualizar cow*/
    protected function before_create(){
        if ($this->baja == self::BAJA_DESCARTE){
            $cow = $this->cow();
            $cow->updateDescarte(true, $this->fecha_baja);
        }
        elseif ($this->baja == self::BAJA_MUERTE){
            $cow = $this->cow();
            $cow->updateMuerta(true, $this->fecha_baja);
        }

    }

    public function cow(){
        return Cow::find($this->cow_id);
    }
    public function schema(){
        return Schema::find($this->schema_id);
    }

    /*
     * SI TIENE date_dl calcular y setear dl
     */
    public function calculateDL($str_date){
        $schema_date = Datetime::createFromFormat('Y-m-d', $str_date);
        if(!Valid::blank($this->date_dl)){
            $date = Datetime::createFromFormat('Y-m-d', $this->date_dl);
            $interval = date_diff($schema_date, $date);
            $dias = $interval->format('%a');
            $this->dl = $dias;
            return $dias;
        }
        elseif(!Valid::blank($this->dl)){
            $schema_date->sub(new DateInterval('P'.$this->dl.'D'));
            $this->date_dl = $schema_date->format('Y-m-d');
        }
        else{
            $this->date_dl = $schema_date;
            $this->dl = 0;
        }
        return false;
    }

    /*
     * Calcular Perdida y DML
    */
    public function calculatePerdDML(){
        $dl = $this->dl;
        if(empty($dl)) $dl = 0;
        $nop = $this->nop;
        if(empty($nop)) $nop = 0;
        $this->perdida = Calculos::perdidaByDlNop($dl, $nop);
        $rcs = $this->rcs;
        if(empty($rcs)) $rcs = 0;
        $this->dml = Calculos::dml($rcs, $this->perdida);
    }

    public function hasMC(){
        return $this->mc == 0;
    }

    public static function find_cow($cow_id, $schema_id){
      return DairyControl::first(array('conditions' => array('cow_id = ? and schema_id = ?', $cow_id, $schema_id)));
    }

    /*Si la vaca en algun momento estuvo Muerta, o se Descarto no puede volver a aparecer*/
    private function validateNoBloqueada(){
        $cow =$this->cow();
        if ($cow != NULL){
            if($cow->isMuerta() && $this->baja != self::BAJA_MUERTE){
                $this->validation->add("La vaca ".$cow->caravana." esta marcada como Muerta, no puede volver a aparecer");
                $this->validation->is_valid = false;
            }
            elseif($cow->isDescarte() && $this->baja != self::BAJA_DESCARTE){
                $this->validation->add("La vaca ".$cow->caravana." esta marcada como Descartada, no puede volver a aparecer");
                $this->validation->is_valid = false;
            }
        }
    }
}
?>