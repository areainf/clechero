<?php 
class User extends Model{
    #EROGACION VALORES POR TAMBO
    public static $_table_name = 'erogaciones';

    function __construct($args = null){
        parent::__construct($args);
        $this->valid_cols = array ('name', 'description',  'schema_id', 'price', 'days');
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
}
?>