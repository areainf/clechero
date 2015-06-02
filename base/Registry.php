<?php

Class Registry {
  private $vars = array();
 
  public function __set($index, $value) {
    $this->vars[$index] = $value;
  }
 
  public function __get($index){
    return $this->vars[$index];
  }
  public function getVars(){
    return $this->vars;
  }
  public function getVar($name){
    if (in_array($name, $this->vars))
      return $this->vars[$name];
    return NULL;
  }
}

?>
