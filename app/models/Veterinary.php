<?php 
class Veterinary extends Person{
    public static $_table_name = 'people';
    public $_type = 'veterinary';

    function __construct($args=null){
        parent::__construct($args);
        $this->valid_cols = array ('first_name', 'last_name',  'email', 'phone', 'created_by');
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
            return $this->validation->is_valid;
        }
    }
    public function fullname(){
        $name = Valid::blank($this->last_name) ? '' : $this->last_name;
        if (!Valid::blank($this->first_name)){
            if(!empty($name))
                $name .= ', ';
            $name .= $this->first_name;

        }
        return $name;
    }

}
?>