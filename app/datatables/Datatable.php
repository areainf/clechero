<?php
abstract class Datatable{
  protected $parameters;
  protected $data;
  public $dtColumns = array();
  public $dtIndexColumn = "id";
  public $dtStart = 0;//offset pagination
  public $dtLength = 0;//limit 
  public $dtDraw = 0;
  public $dtOrder;//index column sort array(array(column=>'',dir=>''))
  public $dtSearch;//Text to search array(search=>'', regex=>'boolean')
  public $infoDataTable = array();

  protected $default_order="";


  abstract protected function _findData();
  abstract protected function _serializeResult();
  
  public function __construct($parameters){
    $this->parameters = $parameters;
    //$this->dtColumns = array('Dairy.id', 'Dairy.number', 'Dairy.name', array('Owner.last_name', 'Owner.first_name'), array('Veterinary.last_name', 'Veterinary.first_name'), 'email', 'phone');
    $this->dtStart = $this->getParams('start');
    $this->dtLength = $this->getParams('length');
    $this->dtDraw = $this->getParams('draw');
    $this->dtOrder = $this->getParams('order');
    $this->dtSearch = $this->getParams('search');
    $this->setInfoDatatable();
  }
  function getJsonData(){
    $this->_findData();
    return $this->_serializeResult();
  }

  
  protected function fatal_error ( $sErrorMessage = '' ){
    header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
    die( $sErrorMessage );
  }

  protected function getParams($name){
    if(empty($this->parameters[$name]))
      return '';
    return $this->parameters[$name];
  }
  protected function _getSqlOrder(){
    if (empty($this->dtOrder))
      return $this->default_order;
    $arr = array();
    foreach ($this->dtOrder as $value) {
      $col = $value['column'];
      if(is_array($this->dtColumns[$col])){
        foreach ($this->dtColumns[$col] as $c)
          $arr[]=$c." ".$value['dir'];
      }
      else
        $arr[]=$this->dtColumns[$col]." ".$value['dir'];
    }
    $var = implode(",",$arr);
    if(Valid::blank($var)) return "";
    return " order by ".$var;
  }
  protected function setInfoDatatable(){
    return array(
      "draw" => $this->dtDraw,
      "start" => $this->dtStart,
      "length" => $this->dtLength,
      "aOrder" => $this->dtOrder,
      // "aCols" => $this->dtCols,
      "search" => $this->dtSearch,
    );
  }
}
?>