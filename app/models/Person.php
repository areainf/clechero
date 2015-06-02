<?php 
class Person extends Model{
    public static $_table_name = 'people';

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
    public function fullname(){
        $name = Valid::blank($this->last_name) ? '' : $this->last_name;
        if (!Valid::blank($this->first_name)){
            if(!empty($name))
                $name .= ', ';
            $name .= $this->first_name;
        }
        return $name;
    }

    public function user(){
        if ($this->id == null) return null;
        return User::first(array('conditions'=>array('person_id = ?',$this->id)));
    }

    public static function all_without_user(){
        return Person::where(array('conditions'=>array('id not in (SELECT person_id from '.User::$_table_name.' where person_id is not null)')));
    }
}
?>