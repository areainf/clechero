<?php 
class Dairy extends Model{
    public static $_table_name = 'dairies';
    protected static $belongs_to = array('Owner' => array('key'=> 'owner_id', 'table' => 'people'), 
                                         'Veterinary' => array('key'=> 'veterinary_id', 'table' => 'people'));

    function __construct($args=null){
        parent::__construct($args);
        $this->valid_cols = array ( 'name', 'owner_id', 'veterinary_id', 'email', 'phone', 'location', 'industry');
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
            $this->validation->present($this, 'name');
            $this->validation->present_at_least($this, array('owner_id','veterinary_id'));
            return $this->validation->is_valid;
        }
    }
    public function fullname(){
        return '['.$this->id.'] ' . $this->name;
    }

    public function countCattle(){
        return Cow::count(['conditions' => ['dairy_id =?',$this->id]]);
    }
    public function veterinary(){
        return Veterinary::find($this->veterinary_id);
    }
    public function owner(){
        return Owner::find($this->owner_id);
    }
    public function schemas(){
        return Schema::where(['conditions' => ['dairy_id =?',$this->id]]);
    }
    public function last_schema(){
        return Schema::first(['conditions' => ['dairy_id =?',$this->id], 'order' => 'date desc']);
    }

    public function last_n_schema($n){
      global $_SQL;
      $query = sprintf("SELECT * FROM (SELECT * FROM %s WHERE dairy_id = %s ORDER BY date DESC LIMIT %s) t ORDER BY date asc", Schema::$_table_name, $this->id, $n);
      $res = $_SQL->get_results($query);
      if ($_SQL->last_error != null) {
          throw new Exception($_SQL->last_error, 1);
      }
      $arr = array();
      if($res){
        foreach ($res as $value) {
          $arr[] = new Schema($value);
        }
      }
      return $arr;
    }

    public function schemasOrder($str_order){
        return Schema::where(['conditions' => ['dairy_id =?',$this->id], 'order' => $str_order ]);
    }
    
}
?>