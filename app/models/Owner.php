<?php 
class Owner extends Person{
    // public static $_table_name = 'people';
    public $_type = 'owner';

    function __construct($args=null){
        parent::__construct($args);
        $this->valid_cols = array ('first_name', 'last_name',  'email', 'phone');
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
            $this->validation->present_at_least($this, array('first_name','last_name'));
            $this->validation->present($this, 'email');
            return $this->validation->is_valid;
        }
    }
    /*
     * Output: int
     * Cantidad de tambos que tiene la persona
    */
    public function count_dairies(){
        global $_SQL;
        $qSqlCount = "SELECT count(id) as cant FROM ".Dairy::$_table_name . " WHERE owner_id = ".$this->id;
        $res_count = $_SQL->get_row($qSqlCount);
        if ($_SQL->last_error != null) {
          $this->fatal_error($_SQL->last_error);
          return 0;            
        }
        return $res_count->cant;
    }

}
?>