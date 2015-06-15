<?php 
class Cow extends Model{
    public static $_table_name = 'cattle';
    protected static $belongs_to = array('Dairy' => array('key'=> 'dairy_id', 'table' => 'dairies'));

    function __construct($args=null){
        parent::__construct($args);
        $this->valid_cols = array ('caravana', 'dairy_id');
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
            return $this->validation->is_valid;
        }
    }

    public static function findOrCreate($caravana, $dairy_id){
        $obj =  self::first(array('conditions' => array('caravana =? and dairy_id = ?', $caravana, $dairy_id)));
        if(!$obj){
            $obj = new Cow(array('caravana' => $caravana, 'dairy_id'=> $dairy_id));
            $obj->save();
        }
        return $obj;
    }

    public function dairy(){
        return Dairy::find($this->dairy_id);
    }
}
?>