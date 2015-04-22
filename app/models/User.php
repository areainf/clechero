<?php 
class User extends Model{
    public static $_table_name = 'users';

    function __construct($args = null){
        parent::__construct($args);
        $this->valid_cols = array ('username', 'password',  'email', 'role', 'disable', 'person_id');
    }

    public  function is_valid(){
        $this->validation->present($this, 'username');
        $this->validation->present($this, 'password');
        return $this->validation->is_valid;
    }

    public function person(){
        if ($this->person_id == null) return null;
        return Person::find($this->person_id);
    }
    public function veterinarians(){
        if ($this->person_id == null) return null;
        return Veterinary::where(array("conditions"=>array('created_by = ?',$this->person_id)));
    }

    public function dairies(){
        if ($this->person_id == null) return [];
        return Dairy::where(array("conditions"=>array('owner_id = ?',$this->person_id)));
    }

    public function isOwn($dairy){
        return $dairy->owner_id == $this->person_id;
    }
}
?>