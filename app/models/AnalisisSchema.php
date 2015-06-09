<?php 
class AnalisisSchema extends Model{
    public static $_table_name = 'analisis_schema';
    
    function __construct($args=null){
        parent::__construct($args);
        $this->valid_cols = array ('schema_id', 'perdida_msc', 'perdida_mc', 'perdida_lts', 'perdida_costo', 
                                   'costo_desinf_pre_o', 'costo_desinf_pos_o', 'costo_tratamiento_mc',
                                   'costo_tratamiento_secado', 'costo_mantenimiento_maquina', 'costo_total',
                                   'costo_extra_mc', 'costo_extra_msc', 'costo_extra_sin_mc', 'costo_extra_tambo',
                                    'costo_extra_vaca');
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
            $this->validation->present($this, 'dairy_id');
            $this->validation->present($this, 'schema_id');
            $this->validation->present($this, 'perdida_msc');
            $this->validation->present($this, 'perdida_mc');
            $this->validation->present($this, 'perdida_lts');
            $this->validation->present($this, 'perdida_costo');
            $this->validation->present($this, 'costo_desinf_pre_o');
            $this->validation->present($this, 'costo_desinf_pos_o');
            $this->validation->present($this, 'costo_tratamiento_mc');
            $this->validation->present($this, 'costo_tratamiento_secado');
            $this->validation->present($this, 'costo_mantenimiento_maquina');
            $this->validation->present($this, 'costo_total');
            return $this->validation->is_valid;
        }
    }
    public function schema(){
        return Schema::find($this->schema_id);
    }
}
?>